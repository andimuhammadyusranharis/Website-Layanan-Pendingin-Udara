<x-layouts.admin title="Teknisi">
    {{-- Variabel: $teknisi (paginator Teknisi), $filter, $pilihanStatus --}}
    {{-- Catatan: versi sesuai backend. Kolom spesialis dan tombol Tambah pada desain belum ada datanya. --}}
    @php
    $warnaStatus = [
    \App\Models\Teknisi::STATUS_MENUNGGU => 'netral',
    \App\Models\Teknisi::STATUS_AKTIF => 'hijau',
    \App\Models\Teknisi::STATUS_NONAKTIF => 'merah',
    ];

    $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
    $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    $kelasTombol = 'flex min-h-11 items-center justify-center rounded-xl px-4 text-[14px] leading-5 font-semibold transition-colors duration-200';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Data Teknisi" deskripsi="Kelola akun teknisi dan setujui pendaftaran mitra." />

        <x-admin.kartu>
            <x-admin.filter
                route="admin.teknisi.index"
                placeholder="Cari nama, email, atau nomor telepon"
                :nilai="$filter"
                :pilihan="[['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus]]" />

            @if ($teknisi->count() === 0)
            <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">engineering</span>
                <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada teknisi yang cocok.</p>
                <a href="{{ route('admin.teknisi.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian dan filter</a>
            </div>
            @else
            <div class="mt-4 overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="{{ $kelasKepalaTabel }}">
                        <tr class="bg-surface-container-low">
                            <th scope="col" class="rounded-l-xl px-4 py-3.5">Teknisi</th>
                            <th scope="col" class="px-4 py-3.5">Nomor telepon</th>
                            <th scope="col" class="px-4 py-3.5">Domisili</th>
                            <th scope="col" class="px-4 py-3.5">Jenis</th>
                            <th scope="col" class="px-4 py-3.5">Status</th>
                            <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="{{ $kelasIsiTabel }}">
                        @foreach ($teknisi as $item)
                        <tr>
                            <td class="px-4 py-3.5">
                                <p class="font-semibold text-on-surface">{{ $item->user->name }}</p>
                                <p class="text-[14px] leading-5 text-text-secondary">{{ $item->user->email }}</p>
                            </td>
                            <td class="px-4 py-3.5">{{ $item->user->no_telepon ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ $item->domisili ?? '-' }}</td>
                            <td class="px-4 py-3.5">{{ Str::ucfirst($item->jenis) }}</td>
                            <td class="px-4 py-3.5">
                                <x-admin.lencana :warna="$warnaStatus[$item->status] ?? 'netral'">{{ $pilihanStatus[$item->status] ?? $item->status }}</x-admin.lencana>
                            </td>
                            <td class="px-4 py-3.5">
                                <div class="flex flex-wrap gap-2">
                                    @if ($item->status !== \App\Models\Teknisi::STATUS_AKTIF)
                                    <form
                                        method="POST"
                                        action="{{ route('admin.teknisi.setujui', $item->id_teknisi) }}"
                                        data-konfirmasi="Akun {{ $item->user->name }} akan diaktifkan dan bisa masuk sebagai teknisi."
                                        data-konfirmasi-judul="Setujui teknisi?"
                                        data-konfirmasi-tombol="Ya, setujui">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $kelasTombol }} bg-primary-container text-white hover:bg-primary">
                                            {{ $item->status === \App\Models\Teknisi::STATUS_NONAKTIF ? 'Aktifkan' : 'Setujui' }}
                                        </button>
                                    </form>
                                    @endif

                                    @if ($item->status !== \App\Models\Teknisi::STATUS_NONAKTIF)
                                    <form
                                        method="POST"
                                        action="{{ route('admin.teknisi.tolak', $item->id_teknisi) }}"
                                        data-konfirmasi="Akun {{ $item->user->name }} tidak akan bisa masuk sebagai teknisi."
                                        data-konfirmasi-judul="{{ $item->status === \App\Models\Teknisi::STATUS_MENUNGGU ? 'Tolak teknisi?' : 'Nonaktifkan teknisi?' }}"
                                        data-konfirmasi-tombol="{{ $item->status === \App\Models\Teknisi::STATUS_MENUNGGU ? 'Ya, tolak' : 'Ya, nonaktifkan' }}"
                                        data-konfirmasi-varian="bahaya">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="{{ $kelasTombol }} border border-status-danger text-status-danger hover:bg-lencana-merah-latar">
                                            {{ $item->status === \App\Models\Teknisi::STATUS_MENUNGGU ? 'Tolak' : 'Nonaktifkan' }}
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $teknisi->links() }}</div>
            @endif
        </x-admin.kartu>
    </div>
</x-layouts.admin>