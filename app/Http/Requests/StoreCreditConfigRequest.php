<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditConfigRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'expiration_months' => ['required', 'integer', 'min:1', 'max:24'],
            'cancel_grace_minutes' => ['required', 'integer', 'min:0'],
            'penalty_mode' => ['required', 'in:none,flat,percent'],
            'penalty_value' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
