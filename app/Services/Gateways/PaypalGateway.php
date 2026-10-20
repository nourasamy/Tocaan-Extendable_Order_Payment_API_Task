<?php

namespace App\Services\Gateways;
use App\Interfaces\PaymentGatewayInterface;
use App\Models\Payment;

class PaypalGateway implements PaymentGatewayInterface
{
    public function pay(Payment $data)
    {
         $gateway = $data->gateway;
        if($gateway?->client_id && $gateway?->secret_key){
            // Implement paypal payment processing logic here
            return true;
        }
        return false;
    }
}
