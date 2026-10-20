<?php

namespace App\Services\Gateways;

use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;

class CreditCardGateway implements PaymentGatewayInterface
{
    public function pay(Payment $payment): bool
    {
        $gateway = $payment->gateway;

        if ($gateway?->client_id && $gateway?->secret_key) {
            // Implement credit card payment processing logic here
            return true;
        }

        return false;
    }
}
