<?php

namespace App\Services;

use App\Enums\PaymentStatus;
use App\Factories\PaymentGatewayFactory ;
use App\Models\Payment;

class PaymentService
{
    public function makePayment(Payment $payment)
    {

        $method = $payment->gateway?->handler_key;

        if (! $method) {
            $payment->status = PaymentStatus::Failed;
            $payment->save();
            throw new \Exception('Payment method not specified');
        }

        $gatewayResult = PaymentGatewayFactory::make($method);

        $gatewayResult = $gatewayResult->pay($payment);

        if ($gatewayResult) {
            $payment->status = PaymentStatus::Successful;
            $payment->save();
        }
        else {
            $payment->status = PaymentStatus::Failed;
            $payment->save();
        }

        return $payment;

    }
}
