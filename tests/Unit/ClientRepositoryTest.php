<?php

namespace Tests\Unit;

use App\Repositories\ClientRepository;

class ClientRepositoryTest extends ApiUnitTestCase
{

    public function test_index_clients():void
    {
        $repo = new ClientRepository();

        $repo->create([
            'name'  => 'Client X',
            'phone' => '01011112222',
        ]);

        $clients = $repo->all();
        $this->assertTrue($clients->count() >= 1);
    }


    public function test_create_client(): void
    {
        $repo = new ClientRepository();

        $client = $repo->create([
            'name' => 'Client A',
            'phone' => '01012345678',
        ]);

        $this->assertNotNull($client->id);
    }

    public function test_update_client(): void
    {
        $repo = new ClientRepository();

        $client = $repo->create([
            'name' => 'Client B',
            'phone' => '01087654321',
        ]);

        $updatedClient = $repo->update($client->id, [
            'name' => 'Client B Updated',
            'phone' => '01099998888',
        ]);

        $this->assertEquals('Client B Updated', $updatedClient->name);
        $this->assertEquals('01099998888', $updatedClient->phone);
    }

    public function test_delete_client(): void
    {
        $repo = new ClientRepository();

        $client = $repo->create([
            'name' => 'Client C',
            'phone' => '01055556666',
        ]);

        $deleted = $repo->delete($client->id);
        $this->assertTrue($deleted);
    }

    public function test_show_client(): void
    {
        $repo = new ClientRepository();

        $client = $repo->create([
            'name' => 'Client D',
            'phone' => '01033334444',
        ]);

        $fetchedClient = $repo->findById($client->id);
        $this->assertEquals($client->name, $fetchedClient->name);
        $this->assertEquals($client->phone, $fetchedClient->phone);
    }

}
