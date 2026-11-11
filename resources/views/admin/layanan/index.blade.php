<x-layouts.admin title="Layanan & Tarif">
    {{-- Variabel: $tab, $jumlahLayanan, $layanan, $jenisAc, $semuaJenisAc, $tarif, $filter, $pilihanStatus --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp '.number_format($nilai, 0, ',', '.');

        $tombolUtama = [
            'layanan' => ['route' => 'admin.layanan.create', 'label' => 'Tambah layanan'],
            'tarif' => ['route' => 'admin.layanan.tarif.edit', 'label' => 'Ubah tarif'],
            'jenis_ac' => ['route' => 'admin.layanan.jenis-ac.create', 'label' => 'Tambah jenis AC'],
        ][$tab];

        $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
        $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Layanan & Tarif" deskripsi="Atur daftar layanan, tarif per jenis AC, dan jenis AC.">
            <x-slot:aksi>
                <x-admin.tombol-tautan :route="$tombolUtama['route']" varian="utama" :ikon="$tab === 'tarif' ? 'edit' : 'add_circle'">{{ $tombolUtama['label'] }}</x-admin.tombol-tautan>
            </x-slot:aksi>
        </x-admin.kepala-halaman>

        <x-admin.kartu>
            <x-admin.tab
                route="admin.layanan.index"
                :aktif="$tab"
                :tab="['layanan' => 'Layanan ('.$jumlahLayanan.')', 'tarif' => 'Tarif per jenis AC', 'jenis_ac' => 'Jenis AC']"
            />

            @if ($tab === 'layanan')
                <x-admin.filter
                    class="mt-4"
                    route="admin.layanan.index"
                    placeholder="Cari nama layanan"
                    :nilai="$filter"
                    :pilihan="[['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus]]"
                />

                @if (count($layanan) === 0)
                    <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                        <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">ac_unit</span>
                        <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada layanan yang cocok.</p>
                        <a href="{{ route('admin.layanan.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian dan filter</a>
                    </div>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full min-w-[820px] text-left">
                            <thead class="{{ $kelasKepalaTabel }}">
                                <tr class="bg-surface-container-low">
                                    <th scope="col" class="rounded-l-xl px-4 py-3.5">Layanan</th>
                                    <th scope="col" class="px-4 py-3.5">Tarif mulai</th>
                                    <th scope="col" class="px-4 py-3.5">Bagi hasil teknisi</th>
                                    <th scope="col" class="px-4 py-3.5">Status</th>
                                    <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="{{ $kelasIsiTabel }}">
                                @foreach ($layanan as $item)
                                    <tr>
                                        <td class="max-w-[280px] px-4 py-3.5">
                                            <span class="block text-[16px] font-semibold text-on-surface">{{ $item['nama'] }}</span>
                                            <span class="block text-[14px] leading-5 text-text-secondary">{{ $item['deskripsi'] }}</span>
                                        </td>
                                        <td class="px-4 py-3.5 text-[17px] font-bold whitespace-nowrap text-on-secondary-fixed-variant tabular-nums">{{ $rupiah($item['tarif_mulai']) }}</td>
                                        <td class="px-4 py-3.5 text-[16px] tabular-nums">{{ $item['bagi_hasil'] }}%</td>
                                        <td class="px-4 py-3.5">
                                            @if ($item['aktif'])
                                                <x-admin.lencana warna="hijau">Aktif</x-admin.lencana>
                                            @else
                                                <x-admin.lencana warna="netral">Nonaktif</x-admin.lencana>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex gap-2">
                                                <x-admin.tombol-tautan route="admin.layanan.edit" :parameter="$item['id']" :aria-label="'Ubah layanan '.$item['nama']">Ubah</x-admin.tombol-tautan>
                                                <x-admin.tombol-aksi
                                                    route="admin.layanan.destroy"
                                                    :parameter="$item['id']"
                                                    metode="DELETE"
                                                    varian="tolak"
                                                    :konfirmasi="'Hapus layanan '.$item['nama'].'? Tindakan ini tidak bisa dibatalkan.'"
                                                    :aria-label="'Hapus layanan '.$item['nama']"
                                                >Hapus</x-admin.tombol-aksi>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @elseif ($tab === 'tarif')
                <p class="mt-4 text-[14px] leading-5 text-text-secondary">Tarif dasar per unit untuk setiap jenis AC. Tanda strip berarti layanan tidak tersedia untuk jenis AC itu.</p>

                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="{{ $kelasKepalaTabel }}">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Layanan</th>
                                @foreach ($semuaJenisAc as $jenis)
                                    <th scope="col" @class(['px-4 py-3.5 text-right whitespace-nowrap', 'rounded-r-xl' => $loop->last])>{{ $jenis['nama'] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="{{ $kelasIsiTabel }}">
                            @foreach ($layanan as $item)
                                <tr>
                                    <th scope="row" class="px-4 py-3.5 font-semibold text-on-surface">
                                        {{ $item['nama'] }}
                                        @unless ($item['aktif'])
                                            <span class="ml-1 text-[13px] leading-[18px] font-normal text-text-secondary">(nonaktif)</span>
                                        @endunless
                                    </th>
                                    @foreach ($semuaJenisAc as $jenis)
                                        @php($nilai = $tarif[$item['id']][$jenis['id']] ?? null)
                                        <td class="px-4 py-3.5 text-right whitespace-nowrap tabular-nums">
                                            @if ($nilai)
                                                <span class="font-semibold text-on-secondary-fixed-variant">{{ $rupiah($nilai) }}</span>
                                            @else
                                                <span class="text-text-secondary" aria-label="Tidak tersedia">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <x-admin.filter class="mt-4" route="admin.layanan.index" placeholder="Cari jenis AC" :nilai="$filter" :tetap="['tab' => 'jenis_ac']" />

                @if (count($jenisAc) === 0)
                    <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                        <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada jenis AC yang cocok.</p>
                    </div>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="w-full min-w-[640px] text-left">
                            <thead class="{{ $kelasKepalaTabel }}">
                                <tr class="bg-surface-container-low">
                                    <th scope="col" class="rounded-l-xl px-4 py-3.5">Jenis AC</th>
                                    <th scope="col" class="px-4 py-3.5">Keterangan</th>
                                    <th scope="col" class="px-4 py-3.5">Dipakai di</th>
                                    <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="{{ $kelasIsiTabel }}">
                                @foreach ($jenisAc as $jenis)
                                    <tr>
                                        <td class="px-4 py-3.5 text-[16px] font-semibold text-on-surface">{{ $jenis['nama'] }}</td>
                                        <td class="px-4 py-3.5 text-text-secondary">{{ $jenis['keterangan'] }}</td>
                                        <td class="px-4 py-3.5 whitespace-nowrap">{{ collect($tarif)->filter(fn (array $baris): bool => ! empty($baris[$jenis['id']]))->count() }} layanan</td>
                                        <td class="px-4 py-3.5">
                                            <div class="flex gap-2">
                                                <x-admin.tombol-tautan route="admin.layanan.jenis-ac.edit" :parameter="$jenis['id']" :aria-label="'Ubah jenis AC '.$jenis['nama']">Ubah</x-admin.tombol-tautan>
                                                <x-admin.tombol-aksi
                                                    route="admin.layanan.jenis-ac.destroy"
                                                    :parameter="$jenis['id']"
                                                    metode="DELETE"
                                                    varian="tolak"
                                                    :konfirmasi="'Hapus jenis AC '.$jenis['nama'].'? Tarifnya juga ikut terhapus.'"
                                                    :aria-label="'Hapus jenis AC '.$jenis['nama']"
                                                >Hapus</x-admin.tombol-aksi>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            @endif
        </x-admin.kartu>
    </div>
</x-layouts.admin>
