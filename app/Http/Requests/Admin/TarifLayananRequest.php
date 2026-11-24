<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TarifLayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tarif' => ['required', 'array'],
            'tarif.*' => ['array'],
            'tarif.*.*' => ['nullable', 'integer', 'min:0', 'max:100000000'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tarif.required' => 'Tidak ada data tarif yang dikirim.',
            'tarif.array' => 'Format matriks tarif tidak sesuai.',
            'tarif.*.array' => 'Format tarif untuk satu layanan tidak sesuai.',
            'tarif.*.*.integer' => 'Tarif harus berupa angka tanpa titik atau koma.',
            'tarif.*.*.min' => 'Tarif tidak boleh kurang dari 0.',
            'tarif.*.*.max' => 'Tarif terlalu besar.',
        ];
    }
}
