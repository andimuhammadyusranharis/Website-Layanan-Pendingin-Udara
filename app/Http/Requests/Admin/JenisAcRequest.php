<?php

namespace App\Http\Requests\Admin;

use App\Models\JenisAc;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JenisAcRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => Str::squish((string) $this->input('nama')),
            'keterangan' => Str::squish((string) $this->input('keterangan')),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var JenisAc|null $jenisAc */
        $jenisAc = $this->route('jenisAc');

        return [
            'nama' => ['required', 'string', 'max:80', Rule::unique(JenisAc::class, 'nama')->ignore($jenisAc?->getKey(), 'id_jenis_ac')],
            'keterangan' => ['nullable', 'string', 'max:160'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Isi nama jenis AC.',
            'nama.max' => 'Nama jenis AC maksimal 80 huruf.',
            'nama.unique' => 'Jenis AC ini sudah ada.',
            'keterangan.max' => 'Keterangan maksimal 160 huruf.',
        ];
    }
}
