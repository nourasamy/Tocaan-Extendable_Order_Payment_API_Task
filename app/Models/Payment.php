<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'gateway_id',
        'status',
        'amount',
    ];

    protected function casts()
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function gateway()
    {
        return $this->belongsTo(PaymentGateway::class, 'gateway_id');
    }

    public function scopeOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }
}
