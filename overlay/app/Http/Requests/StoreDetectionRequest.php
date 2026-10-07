<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDetectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'pesan' => ['required', 'string', 'max:10000'],
            'kategori' => ['required', Rule::in(['NORMAL', 'PENIPUAN', 'PROMO'])],
            'keyakinan' => ['required', 'numeric', 'between:0,1'],
            'probabilitas' => ['required', 'array'],
            'probabilitas.NORMAL' => ['required', 'numeric', 'between:0,1'],
            'probabilitas.PENIPUAN' => ['required', 'numeric', 'between:0,1'],
            'probabilitas.PROMO' => ['required', 'numeric', 'between:0,1'],
            'tanda_bahaya' => ['nullable', 'array', 'max:20'],
            'tanda_bahaya.*' => ['string', 'max:100'],
            'waktu' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tanda_bahaya' => $this->input('tanda_bahaya', []),
        ]);
    }
}
