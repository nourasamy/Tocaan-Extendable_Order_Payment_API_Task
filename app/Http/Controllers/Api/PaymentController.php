<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentResource;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentController extends Controller
{
    protected PaymentRepository $paymentRepository;

    protected OrderRepository $orderRepository;

    public function __construct(PaymentRepository $paymentRepository, OrderRepository $orderRepository)
    {
        $this->paymentRepository = $paymentRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $payments = $this->paymentRepository->paginate(
                orderId: $request->integer('order_id') ?: null
            );

            return ResponseHelper::jsonWithSuccess(PaymentResource::collection($payments), 'Payments retrieved successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve payments.');

        }
    }

    public function show(int|string $id): JsonResponse
    {
        try {

            $payment = $this->paymentRepository->findById($id);
            if (! $payment) {
                return ResponseHelper::JsonWithError('Payment not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new PaymentResource($payment), 'Payment retrieved successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve payment.');

        }
    }

    public function getPaymentsByOrder(int|string $orderId): JsonResponse
    {
        try {
            $order = $this->orderRepository->findById($orderId);
            if (! $order) {
                return ResponseHelper::JsonWithError('Order not found', Response::HTTP_NOT_FOUND);
            }
            $payments = $this->paymentRepository->getByOrders($orderId);

            return ResponseHelper::JsonWithPagination($payments, PaymentResource::collection($payments), 'Payments for order retrieved successfully');

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve order payments.');

        }
    }
}
