<?php

namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\OrderStatusRequest;
use App\Http\Requests\PayOrderRequest;
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

    public function index()
    {
        try {
            $orders = $this->orderRepository->paginate(15);
            return ResponseHelper::JsonWithPagination($orders,OrderResource::collection($orders),'Data retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {

            $order = $this->orderRepository->findById($id);
            if(!$order){
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }
            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order retrieved successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(OrderRequest $request)
    {
        try {

            $order = $this->orderRepository->create($request->validated());

            if(!$order){
                return ResponseHelper::JsonWithError('Failed to create order', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order created successfully', Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(OrderRequest $request, $id)
    {
        try {
            $order = $this->orderRepository->findById($id);

            if (!$order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->payments()->exists()) {
                return ResponseHelper::JsonWithError('Cannot update order with associated payments', Response::HTTP_BAD_REQUEST);
            }

            $order = $this->orderRepository->update($id, $request->validated());

            if(!$order){
                return ResponseHelper::JsonWithError('Failed to update order', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order updated successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus($id, OrderStatusRequest $request)
    {
        try {
            $data = $request->validated();

            $order = $this->orderRepository->findById($id);
            if(!$order){
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            $order = $this->orderRepository->changeStatus($id, $data);

            if(!$order){
                return ResponseHelper::JsonWithError('Failed to update order status', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order status updated successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function payOrder($id, PayOrderRequest $request)
    {
        try {
            $order = $this->orderRepository->findById($id);
            if(!$order){
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->status !== OrderStatus::Confirmed) {
                return ResponseHelper::JsonWithError('Only confirmed orders can be paid', Response::HTTP_BAD_REQUEST);
            }

            if($order->grand_total != $request->amount){
                return ResponseHelper::JsonWithError('Payment amount does not match order total', Response::HTTP_BAD_REQUEST);
            }

            $payment = $this->paymentRepository->create([
                'order_id' => $order->id,
                'gateway_id' => $request->payment_gateway_id,
                'amount' => $request->amount,
            ]);

            if(!$payment){
                return ResponseHelper::JsonWithError('Failed to process payment', Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            if($payment->status != PaymentStatus::Successful){
                return ResponseHelper::JsonWithError('Payment was not successful', Response::HTTP_PAYMENT_REQUIRED);
            }

            $order = $this->orderRepository->changeStatus($id, ['status' => OrderStatus::Confirmed]);

            return ResponseHelper::jsonWithSuccess(new OrderResource($order), 'Order payment processed successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function destroy($id)
    {
        try {

            $order = $this->orderRepository->findById($id);

            if (!$order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }

            if ($order->payments()->exists()) {
                return ResponseHelper::JsonWithError('Cannot delete order with associated payments', Response::HTTP_BAD_REQUEST);
            }

            $this->orderRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Order deleted successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
