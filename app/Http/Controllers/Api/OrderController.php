<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PayOrderRequest;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private OrderRepository $orderRepository;

    private PaymentRepository $paymentRepository;

    public function __construct(OrderRepository $orderRepository, PaymentRepository $paymentRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->paymentRepository = $paymentRepository;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $orders = $this->orderRepository->paginate(
                status: $request->integer('status') ?: null
            );

            return ResponseHelper::JsonWithPagination($orders, OrderResource::collection($orders), 'Data retrieved successfully');
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve orders.');
        }
    }

    public function show(int|string $id): JsonResponse
    {
        try {

            $order = $this->orderRepository->findById($id);
            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order retrieved successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve order.');
        }
    }

    public function store(OrderRequest $request): JsonResponse
    {
        try {

            $order = $this->orderRepository->create($request->validated());

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order created successfully', Response::HTTP_CREATED);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to create order.');
        }
    }

    public function update(OrderRequest $request, int|string $id): JsonResponse
    {
        try {
            $order = $this->orderRepository->findById($id);

            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->payments()->exists()) {
                return ResponseHelper::JsonWithError('Cannot update order with associated payments', Response::HTTP_BAD_REQUEST);
            }

            $order = $this->orderRepository->update($id, $request->validated());

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order updated successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to update order.');
        }
    }

    public function changeStatus(int|string $id, OrderStatusRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $order = $this->orderRepository->findById($id);
            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            $order = $this->orderRepository->changeStatus($id, $data);

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order status updated successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to update order status.');
        }

    }

    public function payOrder(int|string $id, PayOrderRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $order = $this->orderRepository->findById($id);
            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->status !== OrderStatus::Confirmed) {
                return ResponseHelper::JsonWithError('Only confirmed orders can be paid', Response::HTTP_BAD_REQUEST);
            }

            if ($order->payments()->where('status', PaymentStatus::Successful->value)->exists()) {
                return ResponseHelper::JsonWithError('Order has already been paid', Response::HTTP_CONFLICT);
            }

            if ($this->amountInCents($order->grand_total) !== $this->amountInCents($validated['amount'])) {
                return ResponseHelper::JsonWithError('Payment amount does not match order total', Response::HTTP_BAD_REQUEST);
            }

            $payment = $this->paymentRepository->create([
                'order_id' => $order->id,
                'gateway_id' => $validated['payment_gateway_id'],
                'amount' => $validated['amount'],
            ]);

            if ($payment->status !== PaymentStatus::Successful) {
                return ResponseHelper::JsonWithError('Payment was not successful', Response::HTTP_PAYMENT_REQUIRED);
            }

            $order = $this->orderRepository->changeStatus($id, ['status' => OrderStatus::Confirmed]);

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order payment processed successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to process payment.');
        }

    }

    public function destroy(int|string $id): JsonResponse
    {
        try {

            $order = $this->orderRepository->findById($id);

            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->payments()->exists()) {
                return ResponseHelper::JsonWithError('Cannot delete order with associated payments', Response::HTTP_BAD_REQUEST);
            }

            $this->orderRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Order deleted successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to delete order.');
        }
    }

    private function amountInCents(string|int|float $amount): int
    {
        return (int) round((float) $amount * 100);
    }
}
