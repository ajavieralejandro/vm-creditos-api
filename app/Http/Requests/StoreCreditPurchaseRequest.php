<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCreditPurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'pack_id' => ['required_without:pack_code', 'nullable', 'integer', 'exists:credit_packs,id'],
            'pack_code' => ['required_without:pack_id', 'nullable', 'string', 'exists:credit_packs,code'],
        ];
    }
}
