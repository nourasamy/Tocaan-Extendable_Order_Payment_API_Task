<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;

class ClientsApiTest extends ApiTestCase
{
    public function test_index_clients(): void
    {
        $this->makeClient();
        $res = $this->getAuth('/api/clients');

        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message']);
    }

    public function test_store_client_validation_error(): void
    {
        $res = $this->postAuth('/api/clients', [
            'name' => '',
            'phone' => '',
        ]);

        $res->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_store_client(): void
    {
        $payload = [
            'name'  => 'Client A',
            'phone' => '01012345678',
        ];

        $store = $this->postAuth('/api/clients', $payload);
        $store->assertStatus(Response::HTTP_CREATED);
    }

    public function test_show_client(): void
    {
        $client = $this->makeClient([
            'name'  => 'Client B',
            'phone' => '01087654321',
        ]);

        $show = $this->getAuth("/api/clients/{$client->id}");
        $show->assertStatus(Response::HTTP_OK);
    }

    public function test_update_client(): void
    {
        $client = $this->makeClient([
            'name'  => 'Client C',
            'phone' => '01055556666',
        ]);

        $updatePayload = [
            'name'  => 'Client C Updated',
            'phone' => '01099998888',
        ];

        $update = $this->putAuth("/api/clients/{$client->id}", $updatePayload);
        $update->assertStatus(Response::HTTP_OK);
    }

    public function test_delete_client(): void
    {
        $client = $this->makeClient();

        $del = $this->deleteAuth("/api/clients/{$client->id}");
        $del->assertStatus(Response::HTTP_OK);
    }

    public function test_cannot_delete_client_with_orders(): void
    {
        $client = $this->makeClient();
        $this->makeOrder(['data' => ['client_id' => $client->id]]);

        $del = $this->deleteAuth("/api/clients/{$client->id}");
        $del->assertStatus(Response::HTTP_BAD_REQUEST);
    }
}
