<?php

namespace Tests\Unit;

use App\Repositories\GatewayRepository;

class GatewayRepositoryTest extends ApiUnitTestCase
{
    public function test_create_gateway(): void
    {
        $repo = new GatewayRepository();

        $gateway = $repo->create([
            'name' => 'Gateway A',
            'handler_key' => 'unit_gateway_key',
            'handler_class' => 'App\\Services\\Gateways\\DummyGateway',
            'active' => true,
            'client_id' => null,
            'secret_key' => null,
        ]);

        $this->assertNotNull($gateway->id);
    }

    public function test_find_gateway_by_id(): void
    {
        $repo = new GatewayRepository();

        $gateway = $repo->create([
            'name' => 'Gateway A',
            'handler_key' => 'unit_gateway_key',
            'handler_class' => 'App\\Services\\Gateways\\DummyGateway',
            'active' => true,
            'client_id' => null,
            'secret_key' => null,
        ]);

        $foundGateway = $repo->findById($gateway->id);

        $this->assertEquals($gateway->id, $foundGateway->id);
    }

    public function test_update_gateway(): void
    {
        $repo = new GatewayRepository();

        $gateway = $repo->create([
            'name' => 'Gateway B',
            'handler_key' => 'unit_gateway_key_b',
            'handler_class' => 'App\\Services\\Gateways\\DummyGateway',
            'active' => true,
            'client_id' => null,
            'secret_key' => null,
        ]);

        $updatedGateway = $repo->update($gateway->id, [
            'name' => 'Gateway B Updated',
            'handler_key' => 'unit_gateway_key_b_updated',
            'handler_class' => 'App\\Services\\Gateways\\DummyGateway',
            'active' => false,
            'client_id' => null,
            'secret_key' => null,
        ]);

        $this->assertEquals('Gateway B Updated', $updatedGateway->name);
        $this->assertEquals('unit_gateway_key_b_updated', $updatedGateway->handler_key);
        $this->assertFalse($updatedGateway->active);
    }

    public function test_delete_gateway(): void
    {
        $repo = new GatewayRepository();

        $gateway = $repo->create([
            'name' => 'Gateway C',
            'handler_key' => 'unit_gateway_key_c',
            'handler_class' => 'App\\Services\\Gateways\\DummyGateway',
            'active' => true,
            'client_id' => null,
            'secret_key' => null,
        ]);

        $deleted = $repo->delete($gateway->id);
        $this->assertTrue($deleted);
    }   
}
