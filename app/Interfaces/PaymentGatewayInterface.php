<?php

namespace App\Interfaces;

use App\Models\Payment;

interface PaymentGatewayInterface
{
    public function pay(Payment $payment): bool;
}
