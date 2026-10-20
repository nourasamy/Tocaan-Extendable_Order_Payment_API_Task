<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Support\Facades\DB;
class PaymentRepository
{

    public function all()
    {
        return Payment::all();
    }

    public function paginate($perPage = 15)
    {
        $payments = Payment::query();
        if(request()->has('order_id')){
            $payments = $payments->order(request()->get('order_id'));
        }
        return $payments->with('order', 'gateway')->paginate($perPage);
    }

    public function findById($id)
    {
        return Payment::with('order', 'gateway')->find($id);
    }

    public function create(array $data)
    {
       try {
            DB::beginTransaction();
            $payment = Payment::create($data);
            DB::commit();

            $paymentProcess = (new PaymentService())->makePayment($payment);

            return $paymentProcess;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }


    public function getByOrders($orderId, $perPage = 15)
    {
        return Payment::where('order_id', $orderId)
            ->with('order', 'gateway')
            ->paginate($perPage);
    }

}
