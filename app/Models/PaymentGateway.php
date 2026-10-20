<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    protected $fillable = [
        'name',
        'handler_key',
        'handler_class',
        'active',
        'client_id',
        'secret_key',
    ];

    protected function casts()
    {
        return [
            'active' => 'boolean',
        ];
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'gateway_id');
    }
}
