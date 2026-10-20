<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Factories\PaymentGatewayFactory;
use App\Models\Payment;
use RuntimeException;

class PaymentService
{
    public function makePayment(Payment $payment): Payment
    {
        $method = $payment->gateway?->handler_key;

        if (! $method) {
            $payment->status = PaymentStatus::Failed;
            $payment->save();
            throw new RuntimeException('Payment method not specified');
        }

        $successful = PaymentGatewayFactory::make($method)->pay($payment);

        $payment->status = $successful
            ? PaymentStatus::Successful
            : PaymentStatus::Failed;
        $payment->save();

        return $payment;
    }
}
