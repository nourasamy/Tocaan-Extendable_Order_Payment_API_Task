<?php

namespace Tests\Unit;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use Mockery;

class PaymentRepositoryTest extends ApiUnitTestCase
{
    public function test_create_payment(): void
    {
        $orderRepo = new OrderRepository();
        $order = $orderRepo->create($this->getOrder());
        $this->assertNotNull($order);

        $mock = Mockery::mock('overload:App\Services\PaymentService');
        $mock->shouldReceive('makePayment')
            ->once()
            ->andReturnUsing(function ($payment) {
                $payment->status = PaymentStatus::Successful;
                $payment->save();
                return $payment;
            });

        $repo = new PaymentRepository();

        $processed = $repo->create([
            'order_id' => $order->id,
            'gateway_id' => $this->getGateway()->id,
            'amount' => (float) $order->grand_total,
        ]);

        $this->assertNotNull($processed);
        $this->assertEquals(PaymentStatus::Successful, $processed->status);

        $this->assertDatabaseHas('payments', [
            'order_id' => $order->id,
            'gateway_id' => $this->getGateway()->id,
        ]);
    }

    public function test_get_by_orders(): void
    {
        $orderRepo = new OrderRepository();
        $order = $orderRepo->create($this->getOrder());

        Payment::query()->create([
            'order_id' => $order->id,
            'gateway_id' => $this->getGateway()->id,
            'status' => PaymentStatus::Successful,
            'amount' => 10,
        ]);

        $repo = new PaymentRepository();
        $list = $repo->getByOrders($order->id);

        $this->assertTrue($list->count() >= 1);
    }
}
