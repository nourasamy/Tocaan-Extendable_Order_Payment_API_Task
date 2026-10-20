<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gateways = [
            [
                'name' => 'Credit Card',
                'handler_key' => 'credit_card',
                'handler_class' => 'App\Services\Gateways\CreditCardGateway',
                'active' => true,
                'client_id' => 'credit_card_client_id',
                'secret_key' => 'credit_card_secret_key',
            ],
            [
                'name' => 'PayPal',
                'handler_key' => 'paypal',
                'handler_class' => 'App\Services\Gateways\PayPalGateway',
                'active' => true,
                'client_id' => 'paypal_client_id',
                'secret_key' => 'paypal_secret_key',
            ]
        ];

        foreach ($gateways as $gateway) {
            DB::table('payment_gateways')->insert([
                'name' => $gateway['name'],
                'handler_key' => $gateway['handler_key'],
                'handler_class' => $gateway['handler_class'],
                'active' => $gateway['active'],
                'client_id' => $gateway['client_id'],
                'secret_key' => $gateway['secret_key'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
