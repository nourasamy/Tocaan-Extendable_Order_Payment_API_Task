<?php

namespace Tests\Feature;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use Symfony\Component\HttpFoundation\Response;

class PaymentsApiTest extends ApiTestCase
{
    public function test_index_payments(): void
    {
        $order = $this->makeOrder();
        $gateway = $this->getGateway();

        Payment::query()->create([
            'order_id'   => $order->id,
            'gateway_id' => $gateway->id,
            'status'     => PaymentStatus::Successful,
            'amount'     => 10,
        ]);

        $res = $this->getAuth('/api/payments');
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_show_payment(): void
    {
        $order = $this->makeOrder();
        $gateway = $this->getGateway();

        $payment = Payment::query()->create([
            'order_id'   => $order->id,
            'gateway_id' => $gateway->id,
            'status'     => PaymentStatus::Successful,
            'amount'     => 10,
        ]);

        $res = $this->getAuth("/api/payments/{$payment->id}");
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_show_payment_not_found(): void
    {
        $res = $this->getAuth("/api/payments/999999");
        $res->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_get_payments_by_order(): void
    {
        $order = $this->makeOrder();
        $gateway = $this->getGateway();

        Payment::query()->create([
            'order_id'   => $order->id,
            'gateway_id' => $gateway->id,
            'status'     => PaymentStatus::Successful,
            'amount'     => 10,
        ]);

        $res = $this->getAuth("/api/payments/order/{$order->id}");
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_get_payments_by_order_not_found(): void
    {
        $res = $this->getAuth("/api/payments/order/999999");
        $res->assertStatus(Response::HTTP_NOT_FOUND);
    }

}
