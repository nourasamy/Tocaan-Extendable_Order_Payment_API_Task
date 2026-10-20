<?php

namespace Tests\Unit;

use App\Repositories\ItemRepository;

class ItemRepositoryTest extends ApiUnitTestCase
{
    public function test_create_item(): void
    {
        $repo = new ItemRepository();

        $item = $repo->create([
            'name' => 'Item A',
            'price' => 12.50,
        ]);

        $this->assertNotNull($item->id);

    }

    public function test_find_item_by_id(): void
    {
        $repo = new ItemRepository();

        $item = $repo->create([
            'name' => 'Item B',
            'price' => 15.00,
        ]);

        $foundItem = $repo->findById($item->id);

        $this->assertEquals($item->id, $foundItem->id);
    }

    public function test_update_item(): void
    {
        $repo = new ItemRepository();

        $item = $repo->create([
            'name' => 'Item C',
            'price' => 20.00,
        ]);

        $updatedItem = $repo->update($item->id, [
            'name' => 'Item C Updated',
            'price' => 25.00,
        ]);

        $this->assertEquals('Item C Updated', $updatedItem->name);
        $this->assertEquals(25.00, $updatedItem->price);
    }

    public function test_delete_item(): void
    {
        $repo = new ItemRepository();

        $item = $repo->create([
            'name' => 'Item D',
            'price' => 30.00,
        ]);

        $deleted = $repo->delete($item->id);

        $this->assertTrue($deleted);
    }
}
