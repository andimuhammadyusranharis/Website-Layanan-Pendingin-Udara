<?php

namespace App\Http\Requests\Akun;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Nomor dirapikan lebih dulu supaya pengecekan unik memakai format yang sama
     * dengan yang tersimpan di database (08xxxxxxxxxx).
     */
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('no_telepon'))) {
            $this->merge([
                'no_telepon' => User::rapikanNomorTelepon($this->input('no_telepon')),
            ]);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'no_telepon' => [
                'required',
                'string',
                'regex:/^08\d{8,11}$/',
                Rule::unique('users', 'no_telepon')->ignore($this->user()->id),
            ],
            'alamat' => ['nullable', 'string', 'max:500'],
            'patokan' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'no_telepon.required' => 'Nomor telepon wajib diisi.',
            'no_telepon.regex' => 'Nomor telepon tidak valid. Contoh: 081234567890.',
            'no_telepon.unique' => 'Nomor telepon ini sudah dipakai akun lain.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'patokan.max' => 'Patokan maksimal 255 karakter.',
        ];
    }
}
