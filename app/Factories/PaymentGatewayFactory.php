<?php

namespace App\Factories;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use Exception;

class PaymentGatewayFactory
{
    public static function make(string $key): PaymentGatewayInterface
    {
        $gateway = PaymentGateway::where('handler_key', $key)
            ->where('active', true)
            ->firstOrFail();

        $handlerClass = $gateway?->handler_class;

        if (! class_exists($handlerClass) || ! is_a($handlerClass, PaymentGatewayInterface::class, true)) {
            throw new Exception('Payment gateway handler must implement PaymentGatewayInterface.');
        }

        return new $handlerClass;

    }
}
