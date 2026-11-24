<x-layouts.admin title="Ubah tarif">
    {{-- Variabel: $layanan (Collection Layanan), $jenisAc (Collection JenisAc), $tarif (matriks) --}}
    @php
        $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
        $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Ubah tarif" deskripsi="Tarif dasar per unit untuk setiap jenis AC. Sel yang dikosongkan berarti layanan tidak tersedia untuk jenis AC itu." />

        <x-admin.kartu>
            <form action="{{ route('admin.layanan.tarif.update') }}" method="POST" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="{{ $kelasKepalaTabel }}">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Layanan</th>
                                @foreach ($jenisAc as $jenis)
                                    <th scope="col" @class(['px-4 py-3.5 text-right whitespace-nowrap', 'rounded-r-xl' => $loop->last])>{{ $jenis->nama }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="{{ $kelasIsiTabel }}">
                            @forelse ($layanan as $item)
                                <tr>
                                    <th scope="row" class="px-4 py-3.5 font-semibold text-on-surface">
                                        {{ $item->nama }}
                                        @unless ($item->aktif)
                                            <span class="ml-1 text-[13px] leading-[18px] font-normal text-text-secondary">(nonaktif)</span>
                                        @endunless
                                    </th>
                                    @foreach ($jenisAc as $jenis)
                                        @php($nilai = $tarif[$item->id_layanan][$jenis->id_jenis_ac] ?? null)
                                        <td class="px-4 py-3.5 text-right">
                                            <input
                                                type="number"
                                                name="tarif[{{ $item->id_layanan }}][{{ $jenis->id_jenis_ac }}]"
                                                value="{{ $nilai }}"
                                                min="0"
                                                max="100000000"
                                                inputmode="numeric"
                                                placeholder="Tidak tersedia"
                                                aria-label="Tarif {{ $item->nama }} untuk {{ $jenis->nama }}"
                                                class="h-11 w-[150px] rounded-xl border border-transparent bg-surface-container-low px-4 text-[16px] leading-6 text-right tabular-nums text-text-primary transition-colors duration-200 focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
                                            />
                                        </td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(2, $jenisAc->count() + 1) }}" class="px-4 py-6 text-center text-text-secondary">
                                        Belum ada layanan. Tambahkan layanan terlebih dahulu.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($errors->has('tarif'))
                    <p class="flex items-start gap-1.5 text-[14px] leading-5 text-status-danger">
                        <span class="material-symbols-outlined text-[18px]" aria-hidden="true">error</span>
                        {{ $errors->first('tarif') }}
                    </p>
                @endif

                <div class="flex flex-wrap gap-3">
                    <button
                        type="submit"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-primary-container px-5 text-[14px] leading-5 font-semibold whitespace-nowrap text-white transition-colors duration-200 hover:bg-primary hover:shadow-[0px_8px_20px_rgba(30,99,214,0.25)]"
                    >
                        Simpan tarif
                    </button>
                    <x-admin.tombol-tautan route="admin.layanan.index" :parameter="['tab' => 'tarif']">Batal</x-admin.tombol-tautan>
                </div>
            </form>
        </x-admin.kartu>
    </div>
</x-layouts.admin>
