<?php

namespace Tests\Unit;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentRepository;
use App\Services\PaymentService;
use Mockery;
use RuntimeException;

class PaymentRepositoryTest extends ApiUnitTestCase
{
    public function test_create_payment(): void
    {
        $orderRepo = new OrderRepository;
        $order = $orderRepo->create($this->getOrder());
        $this->assertNotNull($order);

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('makePayment')
            ->once()
            ->andReturnUsing(function ($payment) {
                $payment->status = PaymentStatus::Successful;
                $payment->save();

                return $payment;
            });

        $repo = new PaymentRepository($mock);

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
        $orderRepo = new OrderRepository;
        $order = $orderRepo->create($this->getOrder());

        Payment::query()->create([
            'order_id' => $order->id,
            'gateway_id' => $this->getGateway()->id,
            'status' => PaymentStatus::Successful,
            'amount' => 10,
        ]);

        $repo = new PaymentRepository(Mockery::mock(PaymentService::class));
        $list = $repo->getByOrders($order->id);

        $this->assertTrue($list->count() >= 1);
    }

    public function test_payment_is_rolled_back_when_processing_throws(): void
    {
        $orderRepo = new OrderRepository;
        $order = $orderRepo->create($this->getOrder());
        $gateway = $this->getGateway();

        $mock = Mockery::mock(PaymentService::class);
        $mock->shouldReceive('makePayment')
            ->once()
            ->andThrow(new RuntimeException('Gateway failed.'));

        $repo = new PaymentRepository($mock);

        try {
            $repo->create([
                'order_id' => $order->id,
                'gateway_id' => $gateway->id,
                'amount' => 100,
            ]);

            $this->fail('Expected payment processing to throw.');
        } catch (RuntimeException $exception) {
            $this->assertSame('Gateway failed.', $exception->getMessage());
        }

        $this->assertDatabaseMissing('payments', [
            'order_id' => $order->id,
            'gateway_id' => $gateway->id,
        ]);
    }
}
