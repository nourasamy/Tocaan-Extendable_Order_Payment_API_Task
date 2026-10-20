<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
        $rules = [
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|integer|min:1',
            'tax' => 'sometimes|numeric|min:0|decimal:0,2',
            'tax_type' => 'sometimes|integer|in:1,2',
            'discount' => 'sometimes|numeric|min:0|decimal:0,2',
            'discount_type' => 'sometimes|integer|in:1,2',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Client is required.',
            'client_id.exists' => 'Selected client does not exist.',
            'items.required' => 'Order items are required.',
            'items.array' => 'Items must be an array.',
            'items.min' => 'At least one item is required.',
            'items.*.item_id.required' => 'Each item must have an item_id.',
            'items.*.item_id.exists' => 'Selected item does not exist.',
            'items.*.qty.required' => 'Each item must have a qty.',
            'items.*.qty.integer' => 'Item qty must be an integer.',
            'items.*.qty.min' => 'Item qty must be at least 1.',
            'tax.numeric' => 'Tax must be numeric.',
            'tax_type.in' => 'Tax type must be 1 (fixed) or 2 (percent).',
            'discount.numeric' => 'Discount must be numeric.',
            'discount_type.in' => 'Discount type must be 1 (fixed) or 2 (percent).'
        ];
    }
}
