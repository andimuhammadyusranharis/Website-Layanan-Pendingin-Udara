<?php

namespace App\Http\Requests\Admin;

use App\Enums\KategoriLayanan;
use App\Models\Layanan;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LayananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => Str::squish((string) $this->input('nama')),
            'ringkasan' => Str::squish((string) $this->input('ringkasan')),
            'fitur' => $this->daftarFitur(),
            'urutan' => (int) $this->input('urutan', 0),
            'perlu_survei' => $this->boolean('perlu_survei'),
            'hapus_gambar' => $this->boolean('hapus_gambar'),
            'aktif' => $this->boolean('aktif'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Layanan|null $layanan */
        $layanan = $this->route('layanan');

        return [
            'nama' => ['required', 'string', 'max:100', Rule::unique(Layanan::class, 'nama')->ignore($layanan?->getKey(), 'id_layanan')],
            'ringkasan' => ['required', 'string', 'max:160'],
            'deskripsi' => ['required', 'string', 'max:3000'],
            'kategori' => ['required', Rule::enum(KategoriLayanan::class)],
            'jenis_ac' => ['nullable', 'string', 'max:255'],
            'durasi' => ['nullable', 'string', 'max:60'],
            'fitur' => ['array', 'max:8'],
            'fitur.*' => ['string', 'max:120'],
            'tarif_mulai' => [Rule::requiredIf(! $this->boolean('perlu_survei')), 'nullable', 'integer', 'min:0', 'max:100000000'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'keterangan_harga' => ['required', 'string', 'max:50'],
            'catatan_harga' => ['nullable', 'string', 'max:255'],
            'perlu_survei' => ['boolean'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['boolean'],
            'urutan' => ['integer', 'min:0', 'max:65535'],
            'bagi_hasil' => ['required', 'integer', 'between:0,100'],
            'aktif' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Isi nama layanan.',
            'nama.max' => 'Nama layanan maksimal 100 huruf.',
            'nama.unique' => 'Nama layanan ini sudah ada.',
            'ringkasan.required' => 'Isi ringkasan singkat untuk kartu layanan.',
            'ringkasan.max' => 'Ringkasan maksimal 160 huruf.',
            'deskripsi.required' => 'Isi deskripsi layanan.',
            'deskripsi.max' => 'Deskripsi maksimal 3000 huruf.',
            'kategori.required' => 'Pilih kategori layanan.',
            'kategori.enum' => 'Kategori yang dipilih tidak dikenal.',
            'durasi.max' => 'Durasi maksimal 60 huruf.',
            'fitur.max' => 'Poin pekerjaan maksimal 8 baris.',
            'fitur.*.max' => 'Tiap poin pekerjaan maksimal 120 huruf.',
            'tarif_mulai.required' => 'Isi tarif mulai, atau centang "Perlu survei dulu".',
            'tarif_mulai.integer' => 'Tarif mulai harus berupa angka tanpa titik atau koma.',
            'tarif_mulai.min' => 'Tarif mulai tidak boleh kurang dari 0.',
            'tarif_mulai.max' => 'Tarif mulai terlalu besar.',
            'keterangan_harga.required' => 'Isi keterangan harga, misalnya "Mulai dari".',
            'gambar.image' => 'Berkas harus berupa gambar.',
            'gambar.mimes' => 'Foto harus berformat JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran foto maksimal 2 MB.',
            'urutan.integer' => 'Urutan harus berupa angka.',
            'bagi_hasil.required' => 'Isi persentase bagi hasil teknisi.',
            'bagi_hasil.integer' => 'Bagi hasil harus berupa angka bulat.',
            'bagi_hasil.between' => 'Bagi hasil harus antara 0 sampai 100 persen.',
        ];
    }

    /**
     * Poin pekerjaan diisi satu per baris di textarea, lalu disimpan sebagai array.
     * Input bisa sudah berupa array saat form dikirim ulang setelah gagal validasi.
     *
     * @return list<string>
     */
    private function daftarFitur(): array
    {
        $isi = $this->input('fitur', '');
        $baris = is_array($isi) ? $isi : (preg_split('/\R/', (string) $isi) ?: []);

        return collect($baris)
            ->map(fn ($poin): string => Str::squish((string) $poin))
            ->filter()
            ->values()
            ->all();
    }
}
