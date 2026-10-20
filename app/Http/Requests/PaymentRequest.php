<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'gateway_id' => 'required|exists:payment_gateways,id',
            'amount' => 'required|numeric|min:0|decimal:0,2',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'The order is required.',
            'order_id.exists' => 'The selected order does not exist.',
            'gateway_id.required' => 'The payment gateway is required.',
            'gateway_id.exists' => 'The selected payment gateway does not exist.',
            'status.required' => 'The payment status is required.',
            'status.integer' => 'The payment status must be an integer.',
            'status.in' => 'The payment status must be 1 (pending), 2 (successful), or 3 (failed).',
            'amount.required' => 'The payment amount is required.',
            'amount.numeric' => 'The payment amount must be a numeric value.',
            'amount.min' => 'The payment amount must be at least 0.',
            'amount.decimal' => 'The payment amount must have a maximum of 2 decimal places.',
        ];
    }
}
