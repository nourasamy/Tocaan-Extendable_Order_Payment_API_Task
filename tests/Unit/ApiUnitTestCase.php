<?php

namespace Tests\Unit;

use App\Enums\DiscountType;
use App\Enums\TaxType;
use App\Models\Client;
use App\Models\Item;
use App\Models\PaymentGateway;
use App\Models\User;
use Database\Seeders\PaymentGatewaySeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

abstract class ApiUnitTestCase extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->create([
            'name' => 'Unit User',
            'email' => 'unit@example.com',
            'password' => bcrypt('password123'),
        ]);
    }

    protected function makeClient($data = []): Client
    {
        return Client::query()->create(array_merge([
            'name'  => 'Client ' . Str::random(6),
            'phone' => '010' . random_int(10000000, 99999999),
        ], $data));
    }

    protected function makeItem($data = ['price' => 50.00]): Item
    {
        return Item::query()->create(array_merge(['name'  => 'Item ' . Str::random(6)], $data));
    }

    protected function getGateway(): PaymentGateway
    {
        return PaymentGateway::first();
    }

    protected function getOrder($order = []): array
    {
        $client = $order['client'] ?? $this->makeClient();
        $item1  = $order['item1'] ?? $this->makeItem(['price' => 100.00]);
        $item2  = $order['item2'] ?? $this->makeItem(['price' => 50.00]);

        return array_merge([
            'client_id'      => $client->id,
            'tax'            => 10,
            'tax_type'       => TaxType::Percent->value,
            'discount'       => 5,
            'discount_type'  => DiscountType::Fixed->value,
            'items' => [
                ['item_id' => $item1->id, 'qty' => 2],
                ['item_id' => $item2->id, 'qty' => 1],
            ],
        ], $order['data'] ?? []);
    }
}
