<?php

namespace Tests\Feature;

use Symfony\Component\HttpFoundation\Response;

class ConfigApiTest extends ApiTestCase
{
    public function test_get_tax_types(): void
    {
        $res = $this->getAuth('/api/configuration/tax-types');
        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_get_discount_types(): void
    {
        $res = $this->getAuth('/api/configuration/discount-types');
        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message', 'data']);
    }

    public function test_get_order_statuses(): void
    {
        $res = $this->getAuth('/api/configuration/order-status');
        $res->assertStatus(Response::HTTP_OK);
        $res->assertJsonStructure(['success', 'message', 'data']);
    }
}
