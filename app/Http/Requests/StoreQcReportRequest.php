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
            'final_grade' => ['required', 'in:A,B,C'],
            'status' => ['required', 'in:accepted,rejected'],
            'notes' => ['nullable', 'string'],
        ];
    }
}