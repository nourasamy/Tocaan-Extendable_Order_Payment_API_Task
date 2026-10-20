<?php

namespace App\Models;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'client_id',
        'status',
        'subtotal',
        'tax',
        'tax_type',
        'discount',
        'discount_type',
        'grand_total',
    ];

    protected function casts()
    {
        return [
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'discount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'status' => OrderStatus::class,
            'tax_type' => TaxType::class,
            'discount_type' => DiscountType::class,
        ];
    }


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

     public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
