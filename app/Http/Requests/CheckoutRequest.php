<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Boleh guest atau user
    }

    public function rules(): array
    {
        return [
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:20'],
            'shipping_address' => ['required', 'string'],
            'shipping_province' => ['required', 'string', 'max:100'],
            'shipping_city' => ['required', 'string', 'max:100'],
            'shipping_postal_code' => ['required', 'string', 'max:10'],
            'payment_method' => ['required', 'in:midtrans'],
            'courier_name' => ['required', 'string', 'max:50'],
            'courier_service' => ['required', 'string', 'max:50'],
            'shipping_cost' => ['required', 'numeric', 'min:0'],
        ];
    }
}