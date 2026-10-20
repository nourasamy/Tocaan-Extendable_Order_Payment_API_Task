<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\ConfigController;
use Symfony\Component\HttpFoundation\Response;

class ConfigControllerTest extends ApiUnitTestCase
{
    public function test_get_tax_types(): void
    {
        $controller = new ConfigController();
        $res = $controller->getTaxTypes();

        $this->assertEquals(Response::HTTP_OK, $res->getStatusCode());

        $json = $res->getData(true);
        $this->assertTrue((bool)($json['success'] ?? false));
        $this->assertIsArray($json['data'] ?? null);
    }

    public function test_get_discount_types(): void
    {
        $controller = new ConfigController();
        $res = $controller->getDiscountTypes();

        $this->assertEquals(Response::HTTP_OK, $res->getStatusCode());

        $json = $res->getData(true);
        $this->assertTrue((bool)($json['success'] ?? false));
        $this->assertIsArray($json['data'] ?? null);
    }

    public function test_get_order_statuses(): void
    {
        $controller = new ConfigController();
        $res = $controller->getOrderStatuses();

        $this->assertEquals(Response::HTTP_OK, $res->getStatusCode());

        $json = $res->getData(true);
        $this->assertTrue((bool)($json['success'] ?? false));
        $this->assertIsArray($json['data'] ?? null);
    }
}
