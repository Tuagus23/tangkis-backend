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

            'perangkat' => ['required', 'array'],
            'perangkat.installation_id' => ['required', 'string', 'max:100'],
            'perangkat.manufacturer' => ['nullable', 'string', 'max:100'],
            'perangkat.brand' => ['nullable', 'string', 'max:100'],
            'perangkat.model' => ['nullable', 'string', 'max:150'],
            'perangkat.device' => ['nullable', 'string', 'max:150'],
            'perangkat.android_version' => ['nullable', 'string', 'max:50'],
            'perangkat.sdk_int' => ['nullable', 'integer', 'between:1,999'],
            'perangkat.architecture' => ['nullable', 'string', 'max:100'],
            'perangkat.is_emulator' => ['nullable', 'boolean'],

            'aplikasi' => ['required', 'array'],
            'aplikasi.app_name' => ['nullable', 'string', 'max:150'],
            'aplikasi.package_name' => ['nullable', 'string', 'max:200'],
            'aplikasi.version_name' => ['nullable', 'string', 'max:50'],
            'aplikasi.version_code' => ['nullable', 'integer', 'min:1'],
            'aplikasi.build_type' => ['nullable', 'string', 'max:30'],

            'jaringan' => ['nullable', 'array'],
            'jaringan.connection_type' => ['nullable', 'string', 'max:30'],
            'jaringan.local_ip' => ['nullable', 'ip'],

            'lokasi' => ['nullable', 'array'],
            'lokasi.permission_granted' => ['nullable', 'boolean'],
            'lokasi.latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'lokasi.longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'lokasi.accuracy_m' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tanda_bahaya' => $this->input('tanda_bahaya', []),
        ]);
    }
}
