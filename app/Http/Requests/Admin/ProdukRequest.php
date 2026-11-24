<?php

namespace App\Http\Requests\Admin;

use App\Enums\KategoriProduk;
use App\Models\Produk;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProdukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Merek, model, dan spesifikasi hanya berlaku untuk unit AC. Untuk kategori lain ketiganya
     * dikosongkan, supaya data lama tidak tertinggal saat admin mengganti kategori.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => Str::squish((string) $this->input('nama')),
            'merek' => $this->untukUnitAc() ? $this->input('merek') : null,
            'model' => $this->untukUnitAc() ? $this->input('model') : null,
            'spesifikasi' => $this->untukUnitAc() ? $this->daftarSpesifikasi() : null,
            'hapus_gambar' => $this->boolean('hapus_gambar'),
            'aktif' => $this->boolean('aktif'),
        ]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var Produk|null $produk */
        $produk = $this->route('produk');

        return [
            'nama' => ['required', 'string', 'max:150', Rule::unique(Produk::class, 'nama')->ignore($produk?->getKey(), 'id_produk')],
            'kategori' => ['required', Rule::enum(KategoriProduk::class)],
            'deskripsi' => ['required', 'string', 'max:3000'],
            'merek' => [Rule::requiredIf($this->untukUnitAc()), 'nullable', 'string', 'max:50'],
            'model' => ['nullable', 'string', 'max:50'],
            'spesifikasi' => ['nullable', 'array', 'max:6'],
            'spesifikasi.*' => ['string', 'max:100'],
            'harga' => ['required', 'integer', 'min:1', 'max:100000000'],
            'stok' => ['required', 'integer', 'min:0', 'max:100000'],
            'satuan' => ['nullable', 'string', 'max:30'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_gambar' => ['boolean'],
            'aktif' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Isi nama produk.',
            'nama.max' => 'Nama produk maksimal 150 huruf.',
            'nama.unique' => 'Nama produk ini sudah ada.',
            'kategori.required' => 'Pilih kategori produk.',
            'kategori.enum' => 'Kategori yang dipilih tidak dikenal.',
            'deskripsi.required' => 'Isi deskripsi produk.',
            'deskripsi.max' => 'Deskripsi maksimal 3000 huruf.',
            'merek.required' => 'Isi merek untuk unit AC.',
            'merek.max' => 'Merek maksimal 50 huruf.',
            'model.max' => 'Model maksimal 50 huruf.',
            'spesifikasi.max' => 'Spesifikasi maksimal 6 baris.',
            'spesifikasi.*.max' => 'Tiap baris spesifikasi maksimal 100 huruf.',
            'harga.required' => 'Isi harga produk.',
            'harga.integer' => 'Harga harus berupa angka tanpa titik atau koma.',
            'harga.min' => 'Harga minimal Rp1.',
            'harga.max' => 'Harga terlalu besar.',
            'stok.required' => 'Isi jumlah stok.',
            'stok.integer' => 'Stok harus berupa angka bulat.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'stok.max' => 'Stok terlalu besar.',
            'satuan.max' => 'Satuan maksimal 30 huruf.',
            'gambar.image' => 'Berkas harus berupa gambar.',
            'gambar.mimes' => 'Foto harus berformat JPG, PNG, atau WebP.',
            'gambar.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }

    private function untukUnitAc(): bool
    {
        return $this->input('kategori') === KategoriProduk::UnitAc->value;
    }

    /**
     * Spesifikasi diisi satu per baris di textarea, lalu disimpan sebagai array.
     * Input bisa sudah berupa array saat form dikirim ulang setelah gagal validasi.
     *
     * @return list<string>
     */
    private function daftarSpesifikasi(): array
    {
        $isi = $this->input('spesifikasi', '');
        $baris = is_array($isi) ? $isi : (preg_split('/\R/', (string) $isi) ?: []);

        return collect($baris)
            ->map(fn ($poin): string => Str::squish((string) $poin))
            ->filter()
            ->values()
            ->all();
    }
}
