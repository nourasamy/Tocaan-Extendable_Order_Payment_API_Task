<?php

namespace Tests\Feature;

use App\Models\OrderDetail;
use Symfony\Component\HttpFoundation\Response;

class ItemsApiTest extends ApiTestCase
{
    public function test_index_items(): void
    {
        $this->makeItem(['price' => 10.00]);
        $res = $this->getAuth('/api/items');

        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_store_item(): void
    {
        $store = $this->postAuth('/api/items', [
            'name'  => 'Item A',
            'price' => 12.50,
        ]);

        $store->assertStatus(Response::HTTP_CREATED);

    }

    public function test_store_item_validation_error(): void
    {
        $res = $this->postAuth('/api/items', [
            'name'  => '',
            'price' => -5,
        ]);

        $res->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_show_item(): void
    {
        $item = $this->makeItem([
            'name'  => 'Item A',
            'price' => 10.00,
        ]);

        $show = $this->getAuth("/api/items/{$item->id}");
        $show->assertStatus(Response::HTTP_OK);
    }

    public function test_update_item(): void
    {
        $item = $this->makeItem([
            'name'  => 'Item B',
            'price' => 15.00,
        ]);

        $update = $this->putAuth("/api/items/{$item->id}", [
            'name'  => 'Item B Updated',
            'price' => 20.00,
        ]);

        $update->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_item(): void
    {
        $item = $this->makeItem([
            'name'  => 'Item C',
            'price' => 18.00,
        ]);

        $del = $this->deleteAuth("/api/items/{$item->id}");
        $del->assertStatus(Response::HTTP_OK);
    }

    public function test_cannot_delete_item_if_used_in_order_details(): void
    {
        $order = $this->makeOrder();
        $detail = $order->orderDetails()->first();
        $this->assertNotNull($detail);

        $itemId = $detail->item_id;

        $del = $this->deleteAuth("/api/items/{$itemId}");
        $del->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
