<x-layouts.admin title="Kelola Ulasan">
    {{-- Variabel: $jumlahUlasan, $ulasan (Collection Ulasan), $filter, $pilihanStatus --}}
    @php
        $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
        $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Kelola Ulasan" deskripsi="Atur ulasan pelanggan yang tampil di halaman Ulasan." />

        <x-admin.kartu>
            <h2 class="text-[18px] leading-7 font-bold text-on-surface">Ulasan pelanggan ({{ $jumlahUlasan }})</h2>

            <x-admin.filter
                class="mt-4"
                route="admin.ulasan.index"
                placeholder="Cari nama pelanggan"
                :nilai="$filter"
                :pilihan="[['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus]]"
            />

            @if (count($ulasan) === 0)
                <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                    <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">reviews</span>
                    <p class="text-[15px] leading-6 font-semibold text-on-surface">
                        {{ $jumlahUlasan === 0 ? 'Belum ada ulasan pelanggan.' : 'Tidak ada ulasan yang cocok.' }}
                    </p>
                    @if ($jumlahUlasan > 0)
                        <a href="{{ route('admin.ulasan.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian dan filter</a>
                    @endif
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="{{ $kelasKepalaTabel }}">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Pelanggan</th>
                                <th scope="col" class="px-4 py-3.5">Rating</th>
                                <th scope="col" class="px-4 py-3.5">Komentar</th>
                                <th scope="col" class="px-4 py-3.5">Tanggal</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="{{ $kelasIsiTabel }}">
                            @foreach ($ulasan as $item)
                                <tr>
                                    <td class="px-4 py-3.5 text-[16px] font-semibold text-on-surface">{{ $item->nama_pelanggan }}</td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <span class="text-status-warning" aria-hidden="true">{{ str_repeat('★', $item->rating) }}</span>
                                        <span class="text-text-secondary">{{ $item->rating }} dari 5</span>
                                    </td>
                                    <td class="max-w-[360px] px-4 py-3.5">
                                        <p class="line-clamp-3">{{ $item->komentar }}</p>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap text-text-secondary">{{ $item->created_at?->format('d/m/Y') ?? '-' }}</td>
                                    <td class="px-4 py-3.5">
                                        @if ($item->tampil)
                                            <x-admin.lencana warna="hijau">Ditampilkan</x-admin.lencana>
                                        @else
                                            <x-admin.lencana warna="netral">Disembunyikan</x-admin.lencana>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <form method="POST" action="{{ route('admin.ulasan.tampilan', $item) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="tampil" value="{{ $item->tampil ? 0 : 1 }}" />
                                            <button
                                                type="submit"
                                                aria-label="{{ ($item->tampil ? 'Sembunyikan' : 'Tampilkan').' ulasan dari '.$item->nama_pelanggan }}"
                                                @class([
                                                    'flex min-h-10 items-center justify-center rounded-xl px-4 text-[14px] leading-5 font-semibold whitespace-nowrap transition-colors duration-200',
                                                    'border border-status-danger/70 bg-white text-status-danger hover:bg-lencana-merah-latar' => $item->tampil,
                                                    'border border-border-subtle bg-white text-on-secondary-fixed-variant hover:border-primary-container hover:bg-surface-tint' => ! $item->tampil,
                                                ])
                                            >
                                                {{ $item->tampil ? 'Sembunyikan' : 'Tampilkan' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.kartu>
    </div>
</x-layouts.admin>
