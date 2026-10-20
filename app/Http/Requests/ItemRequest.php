<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|decimal:0,2',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The item name is required.',
            'name.string' => 'The item name must be a string.',
            'name.max' => 'The item name may not be greater than 255 characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a numeric value.',
            'price.decimal' => 'The price must have a maximum of 2 decimal places.',
        ];
    }
}
