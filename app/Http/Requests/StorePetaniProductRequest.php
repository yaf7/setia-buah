<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePetaniProductRequest extends FormRequest
{
    public function authorize(): bool {
        return $this->user()?->role === 'petani';
    }

    public function rules(): array {
        return [
            'fruit_type' => ['required', 'string', 'max:255'],
            'grade' => ['required', 'in:A,B,C'],
            'estimated_weight_kg' => ['required', 'numeric', 'min:0.1'],
            'price_per_kg' => ['required', 'numeric', 'min:100'],
            'harvest_date' => ['required', 'date'],
            'image' => ['required', 'image', 'max:2048'],
        ];
    }
}
