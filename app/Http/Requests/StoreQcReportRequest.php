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
            'actual_weight_kg' => ['required', 'numeric', 'min:0.1'],
            'rejected_weight_kg' => ['nullable', 'numeric', 'min:0'],
            'final_grade' => ['required', 'in:A,B,C'],
            'final_price_per_kg' => ['required', 'numeric', 'min:100'],
            'status' => ['required', 'in:accepted,rejected'],
            'inventory_status' => ['required', 'in:catalog,warehouse'],
            'notes' => ['nullable', 'string'],
        ];
    }
}