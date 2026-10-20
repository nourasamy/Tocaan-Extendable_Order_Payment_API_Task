<?php

namespace Tests\Feature;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Models\Client;
use App\Models\Item;
use App\Models\Order;
use App\Models\PaymentGateway;
use App\Models\User;
use Database\Seeders\PaymentGatewaySeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::query()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $res = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ])->assertStatus(200);

        $this->token = data_get($res->json(), 'data.access_token')
            ?? data_get($res->json(), 'access_token')
            ?? '';

        $this->assertNotEmpty($this->token);
    }

    protected function authHeaders(array $extra = []): array
    {
        return array_merge([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept'        => 'application/json',
        ], $extra);
    }

    protected function getAuth(string $uri, array $headers = [])
    {
        return $this->getJson($uri, $this->authHeaders($headers));
    }

    protected function postAuth(string $uri, array $data = [], array $headers = [])
    {
        return $this->postJson($uri, $data, $this->authHeaders($headers));
    }

    protected function putAuth(string $uri, array $data = [], array $headers = [])
    {
        return $this->putJson($uri, $data, $this->authHeaders($headers));
    }

    protected function deleteAuth(string $uri, array $headers = [])
    {
        return $this->deleteJson($uri, [], $this->authHeaders($headers));
    }

    protected function assertSuccess($response, int $status = 200): void
    {
        $response->assertStatus($status);

        $response->assertJsonStructure([
            'success',
            'message',
            'data',
        ]);
    }

    protected function assertError($response, int $status): void
    {
        $response->assertStatus($status);
        $response->assertJsonStructure([
            'success',
            'message',
            'errors'
        ]);
    }

    protected function makeClient(array $override = []): Client
    {
        return Client::query()->create(array_merge([
            'name'  => 'Client ' . Str::random(6),
            'phone' => '010' . random_int(10000000, 99999999),
        ], $override));
    }

    protected function makeItem($data = ['price' => 150.00]): Item
    {
        return Item::query()->create(array_merge(['name'  => 'Item ' . Str::random(6) ], $data));
    }

    protected function getGateway(): PaymentGateway
    {
        return PaymentGateway::first();
    }

    protected function makeOrder($order = []): Order
    {
        $client = $order['client'] ?? $this->makeClient();
        $item1  = $order['item1'] ?? $this->makeItem(['price' => 100.00]);
        $item2  = $order['item2'] ?? $this->makeItem(['price' => 50.00]);

        $data = array_merge([
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

        $res = $this->postAuth('/api/orders', $data);
        $res->assertStatus(201);

        $orderId = data_get($res->json(), 'data.id');
        $this->assertNotEmpty($orderId);

        return Order::query()->findOrFail($orderId);
    }
}
