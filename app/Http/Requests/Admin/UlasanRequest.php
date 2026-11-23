<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UlasanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Admin hanya mengirim satu isian: ulasan ditampilkan (1) atau disembunyikan (0).
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tampil' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tampil.required' => 'Pilih tampilkan atau sembunyikan ulasan.',
            'tampil.boolean' => 'Pilihan tampil ulasan tidak dikenal.',
        ];
    }
}
