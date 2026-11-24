<x-layouts.admin title="Pelanggan">
    {{-- Variabel: $pelanggan (paginator User), $jumlah, $filter --}}
    {{-- Catatan: versi sesuai backend. Tombol Tambah, Ekspor, dan kolom status pesanan pada desain belum dibuat. --}}
    @php
    $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
    $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Daftar Pelanggan" deskripsi="Kelola data pelanggan yang terdaftar ({{ $jumlah }} akun)." />

        <x-admin.kartu>
            <x-admin.filter
                route="admin.pelanggan.index"
                placeholder="Cari nama, email, atau nomor telepon"
                :nilai="$filter"
                :pilihan="[]" />

            @if ($pelanggan->count() === 0)
            <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">group</span>
                <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada pelanggan yang cocok.</p>
                <a href="{{ route('admin.pelanggan.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian</a>
            </div>
            @else
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[720px] text-left">
                    <thead class="{{ $kelasKepalaTabel }}">
                        <tr class="bg-surface-container-low">
                            <th scope="col" class="rounded-l-xl px-4 py-3.5">Pelanggan</th>
                            <th scope="col" class="px-4 py-3.5">Nomor telepon</th>
                            <th scope="col" class="px-4 py-3.5">Alamat</th>
                            <th scope="col" class="px-4 py-3.5">Terdaftar</th>
                            <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="{{ $kelasIsiTabel }}">
                        @foreach ($pelanggan as $item)
                        @php
                        $inisial = Str::of($item->name)->explode(' ')->filter()->take(2)->map(fn ($kata) => Str::upper(Str::substr($kata, 0, 1)))->implode('');
                        @endphp
                        <tr>
                            <td class="px-4 py-3.5">
                                <div class="flex items-center gap-3">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-surface-tint text-[14px] font-bold text-primary-container" aria-hidden="true">{{ $inisial }}</span>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-on-surface">{{ $item->name }}</p>
                                        <p class="truncate text-[14px] leading-5 text-text-secondary">{{ $item->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3.5">{{ $item->no_telepon ?? '-' }}</td>
                            <td class="max-w-[260px] truncate px-4 py-3.5">{{ $item->pelanggan?->alamat ?? '-' }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">{{ $item->created_at?->translatedFormat('d M Y') }}</td>
                            <td class="px-4 py-3.5">
                                <a href="{{ route('admin.pelanggan.show', $item->id) }}" class="inline-flex min-h-11 items-center rounded-xl border border-border-subtle px-4 text-[14px] leading-5 font-semibold text-text-primary hover:bg-surface-container-low">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $pelanggan->links() }}</div>
            @endif
        </x-admin.kartu>
    </div>
</x-layouts.admin>