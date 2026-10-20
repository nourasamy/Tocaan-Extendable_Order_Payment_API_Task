<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GatewayRequest extends FormRequest
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
            'handler_key' => 'required|string|unique:payment_gateways,handler_key' . ($this->method() === 'PUT' ? ',' . $this->route('gateway') : ''),
            'handler_class' => 'required|string',
            'active' => 'sometimes|boolean',
            'client_id' => 'nullable|string|max:255',
            'secret_key' => 'nullable|string|max:255',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'The gateway name is required.',
            'name.string' => 'The gateway name must be a string.',
            'name.max' => 'The gateway name may not be greater than 255 characters.',
            'handler_key.required' => 'The handler key is required.',
            'handler_key.string' => 'The handler key must be a string.',
            'handler_key.unique' => 'This handler key is already registered.',
            'active.boolean' => 'The active field must be a boolean value.',
            'client_id.string' => 'The client ID must be a string.',
            'client_id.max' => 'The client ID may not be greater than 255 characters.',
            'secret_key.string' => 'The secret key must be a string.',
            'secret_key.max' => 'The secret key may not be greater than 255 characters.',
        ];
    }
}
