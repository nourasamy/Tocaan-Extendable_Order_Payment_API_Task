<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PaymentRepository
{
    public function __construct(private readonly PaymentService $paymentService) {}

    public function all()
    {
        return Payment::all();
    }

    public function paginate(int $perPage = 15, ?int $orderId = null): LengthAwarePaginator
    {
        $payments = Payment::query()->with('order', 'gateway');

        if ($orderId !== null) {
            $payments->order($orderId);
        }

        return $payments->paginate($perPage);
    }

    public function findById($id)
    {
        return Payment::with('order', 'gateway')->find($id);
    }

    public function create(array $data): Payment
    {
        return DB::transaction(function () use ($data): Payment {
            $payment = Payment::create($data);

            return $this->paymentService->makePayment($payment);
        });
    }

    public function getByOrders($orderId, $perPage = 15)
    {
        return Payment::where('order_id', $orderId)
            ->with('order', 'gateway')
            ->paginate($perPage);
    }
}
