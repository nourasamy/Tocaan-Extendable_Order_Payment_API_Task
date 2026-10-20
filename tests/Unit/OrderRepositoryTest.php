<?php

namespace Tests\Unit;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Repositories\OrderRepository;

class OrderRepositoryTest extends ApiUnitTestCase
{
    public function test_calculate_tax(): void
    {
        $repo = new OrderRepository();

        $tax = $repo->calculateTax(100, 10, TaxType::Percent);
        $this->assertEquals(10, $tax);

        $taxFixed = $repo->calculateTax(100, 15, TaxType::Fixed);
        $this->assertEquals(15, $taxFixed);
    }

    public function test_calculate_discount(): void
    {
        $repo = new OrderRepository();
        $disc = $repo->calculateDiscount(200, 10, DiscountType::Percent);
        $this->assertEquals(20, $disc);

        $discFixed = $repo->calculateDiscount(200, 30, DiscountType::Fixed);
        $this->assertEquals(30, $discFixed);
    }

    public function test_create_order(): void
    {
        $repo = new OrderRepository();

        $payload = $this->getOrder([
            'payload' => [
                'tax' => 10,
                'tax_type' => TaxType::Percent->value,
                'discount' => 5,
                'discount_type' => DiscountType::Fixed->value,
            ],
        ]);

        $order = $repo->create($payload);

        $this->assertNotNull($order);
        $this->assertEquals(OrderStatus::Pending, $order->status);

        $this->assertEquals('250.00', number_format((float)$order->subtotal, 2, '.', ''));
        $this->assertEquals('270.00', number_format((float)$order->grand_total, 2, '.', ''));
    }

    public function test_update_order(): void
    {
        $repo = new OrderRepository();

        $order = $repo->create($this->getOrder());
        $this->assertNotNull($order);

        $newItem = $this->makeItem(['price' => 200.00]);

        $updated = $repo->update($order->id, [
            'client_id' => $order->client_id,
            'tax' => 0,
            'tax_type' => TaxType::Fixed->value,
            'discount' => 0,
            'discount_type' => DiscountType::Fixed->value,
            'items' => [
                ['item_id' => $newItem->id, 'qty' => 1],
            ],
        ]);

        $this->assertNotNull($updated);
        $this->assertEquals('200.00', number_format((float)$updated->subtotal, 2, '.', ''));
        $this->assertEquals('200.00', number_format((float)$updated->grand_total, 2, '.', ''));
    }

    public function test_change_status(): void
    {
        $repo = new OrderRepository();

        $order = $repo->create($this->getOrder());
        $this->assertNotNull($order);

        $changed = $repo->changeStatus($order->id, ['status' => OrderStatus::Confirmed]);
        $this->assertNotNull($changed);
        $this->assertEquals(OrderStatus::Confirmed, $changed->status);
    }

    public function test_delete_order_deletes_details(): void
    {
        $repo = new OrderRepository();

        $order = $repo->create($this->getOrder());
        $this->assertNotNull($order);

        $this->assertTrue($order->orderDetails()->count() > 0);

        $ok = $repo->delete($order->id);
        $this->assertTrue($ok);

        $this->assertDatabaseMissing('orders', ['id' => $order->id]);
    }
}
