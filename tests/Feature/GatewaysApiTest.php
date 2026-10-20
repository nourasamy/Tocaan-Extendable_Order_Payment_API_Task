<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;

class GatewaysApiTest extends ApiTestCase
{
    public function test_index_gateways(): void
    {
        $this->getGateway();
        $res = $this->getAuth('/api/gateways');
        $res->assertStatus(Response::HTTP_OK);
    }

    public function test_store_gateway_validation_error(): void
    {
        $res = $this->postAuth('/api/gateways', [
            'name'          => '',
            'handler_key'   => '',
            'handler_class' => '',
            'active'        => '',
        ]);

        $res->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_store_gateway(): void
    {
        $store = $this->postAuth('/api/gateways', [
            'name'          => 'Gateway A',
            'handler_key'   => 'stripe_test',
            'handler_class' => 'App\\Services\\Gateways\\StripeGateway',
            'active'        => true,
        ]);

        $store->assertStatus(Response::HTTP_CREATED);
    }

    public function test_show_gateway(): void
    {
        $gateway = $this->getGateway();

        $show = $this->getAuth("/api/gateways/{$gateway->id}");
        $show->assertStatus(Response::HTTP_OK);
    }

    public function test_update_gateway(): void
    {
        $gateway = $this->getGateway();

        $update = $this->putAuth("/api/gateways/{$gateway->id}", [
            'name'          => 'Gateway A Updated',
            'handler_key'   => 'stripe_live',
            'handler_class' => 'App\\Services\\Gateways\\StripeGateway',
            'active'        => false,
        ]);

        $update->assertStatus(Response::HTTP_OK);
    }

    public function test_destroy_gateway(): void
    {
        $gateway = $this->getGateway();

        $del = $this->deleteAuth("/api/gateways/{$gateway->id}");
        $del->assertStatus(Response::HTTP_OK);
    }

}
