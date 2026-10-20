<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\TaxType;
use App\Models\Payment;
use Mockery;
use Symfony\Component\HttpFoundation\Response;

class OrdersApiTest extends ApiTestCase
{
    public function test_index_orders(): void
    {
        $order = $this->makeOrder();

        $all = $this->getAuth('/api/orders');
        $all->assertStatus(Response::HTTP_OK);
    }

    public function test_index_orders_with_filters(): void
    {
        $client = $this->makeClient();
        $this->makeOrder(['client_id' => $client->id, 'status' => OrderStatus::Pending]);

        $res = $this->getAuth('/api/orders?client_id=' . $client->id . '&status=' . OrderStatus::Pending->value);
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_store_order_validation_error(): void
    {
        $res = $this->postAuth('/api/orders', [
            'client_id'     => '',
            'tax'           => -5,
            'tax_type'      => '',
            'discount'      => -10,
            'discount_type' => '',
            'items'         => [],
        ]);

        $res->assertStatus(422);
    }

    public function test_show_order_not_found(): void
    {
        $res = $this->getAuth('/api/orders/999999');
        $res->assertStatus(404);
    }

    public function test_show_order(): void
    {
        $order = $this->makeOrder();

        $res = $this->getAuth("/api/orders/{$order->id}");
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_store_order(): void
    {
        $client = $this->makeClient();

        $payload = [
            'client_id'     => $client->id,
            'tax'           => 5,
            'tax_type'      => TaxType::Percent->value,
            'discount'      => 2,
            'discount_type' => DiscountType::Fixed->value,
            'items'         => [
                ['item_id' => $this->makeItem(['price' => 10])->id, 'qty' => 2],
                ['item_id' => $this->makeItem(['price' => 20])->id, 'qty' => 1],
            ],
        ];

        $res = $this->postAuth('/api/orders', $payload);
        $res->assertStatus(Response::HTTP_CREATED);
    }

    public function test_update_order(): void
    {
        $order = $this->makeOrder();

        $payload = [
            'client_id'     => $order->client_id,
            'tax'           => 0,
            'tax_type'      => TaxType::Fixed->value,
            'discount'      => 0,
            'discount_type' => DiscountType::Fixed->value,
            'items'         => [
                ['item_id' => $this->makeItem(['price' => 15])->id, 'qty' => 3],
            ],
        ];

        $res = $this->putAuth("/api/orders/{$order->id}", $payload);
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_update_order_blocked_if_has_payments(): void
    {
        $order = $this->makeOrder();

        Payment::query()->create([
            'order_id'   => $order->id,
            'gateway_id' => $this->getGateway()->id,
            'status'     => PaymentStatus::Successful,
            'amount'     => 10,
        ]);

        $res = $this->putAuth("/api/orders/{$order->id}", [
            'client_id' => $order->client_id,
            'tax' => 0,
            'tax_type' => TaxType::Fixed->value,
            'discount' => 0,
            'discount_type' => DiscountType::Fixed->value,
            'items' => [
                ['item_id' => $this->makeItem(['price' => 10])->id, 'qty' => 1],
            ],
        ]);

        $res->assertStatus(400);
    }

    public function test_change_status_success(): void
    {
        $order = $this->makeOrder();

        $res = $this->postAuth("/api/orders/{$order->id}/change-status", [
            'status' => OrderStatus::Confirmed->value,
        ]);

        $res->assertStatus(200);
    }

    public function test_pay_order_not_confirmed(): void
    {
        $order = $this->makeOrder();

        $res = $this->postAuth("/api/orders/{$order->id}/pay", [
            'payment_gateway_id' => $this->getGateway()->id,
            'amount' => $order->grand_total,
        ]);
        $res->assertStatus(Response::HTTP_BAD_REQUEST);
    }


    public function test_pay_order_not_found(): void
    {

        $res = $this->postAuth("/api/orders/8888888/pay", [
            'payment_gateway_id' => $this->getGateway()->id,
            'amount' => 100,
        ]);
        $res->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_pay_order_with_wrong_amount(): void
    {

        $order = $this->makeOrder();

        $res = $this->postAuth("/api/orders/{$order->id}/pay", [
            'payment_gateway_id' => $this->getGateway()->id,
            'amount' => $order->grand_total + 100,
        ]);

        $res->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_pay_order_success_with_mocked_payment_service(): void
    {
        $order = $this->makeOrder();

        $this->postAuth("/api/orders/{$order->id}/change-status", [
            'status' => OrderStatus::Confirmed->value,
        ])->assertStatus(200);

        $gateway = $this->getGateway();

        $mock = Mockery::mock('overload:App\Services\PaymentService');
        $mock->shouldReceive('makePayment')
            ->once()
            ->andReturnUsing(function ($payment) {
                $payment->status = \App\Enums\PaymentStatus::Successful;
                $payment->save();
                return $payment;
            });

        $res = $this->postAuth("/api/orders/{$order->id}/pay", [
            'payment_gateway_id' => $gateway->id,
            'amount' => (float) $order->grand_total,
        ]);

        $res->assertStatus(Response::HTTP_OK);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_id' => $gateway->id,
        ]);
    }

    public function test_destroy_order_blocked_if_has_payments(): void
    {
        $order = $this->makeOrder();

        Payment::query()->create([
            'order_id'   => $order->id,
            'gateway_id' => $this->getGateway()->id,
            'status'     => \App\Enums\PaymentStatus::Successful,
            'amount'     => 10,
        ]);

        $res = $this->deleteAuth("/api/orders/{$order->id}");
        $res->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
