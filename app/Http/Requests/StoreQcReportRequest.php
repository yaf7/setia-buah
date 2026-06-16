<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQcReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'petani_product_id' => ['required', 'exists:petani_products,id'],
            'weight_a' => ['nullable', 'numeric', 'min:0'],
            'price_a' => ['nullable', 'numeric', 'min:0'],
            'weight_b' => ['nullable', 'numeric', 'min:0'],
            'price_b' => ['nullable', 'numeric', 'min:0'],
            'weight_c' => ['nullable', 'numeric', 'min:0'],
            'price_c' => ['nullable', 'numeric', 'min:0'],
            'rejected_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:accepted,rejected'],
            'inventory_status' => ['required', 'in:catalog,warehouse'],
            'notes' => ['nullable', 'string'],
        ];
    }
}