@php
    $menu = [
        ['label' => 'Dashboard', 'ikon' => 'space_dashboard', 'route' => 'admin.dashboard', 'aktif' => 'admin.dashboard'],
        ['label' => 'Pesanan', 'ikon' => 'receipt_long', 'route' => 'admin.pesanan.index', 'aktif' => 'admin.pesanan.*'],
        ['label' => 'Jadwal', 'ikon' => 'calendar_month', 'route' => 'admin.jadwal.index', 'aktif' => 'admin.jadwal.*'],
        ['label' => 'Pembayaran', 'ikon' => 'payments', 'route' => 'admin.pembayaran.index', 'aktif' => 'admin.pembayaran.*'],
        ['label' => 'Layanan & Tarif', 'ikon' => 'ac_unit', 'route' => 'admin.layanan.index', 'aktif' => 'admin.layanan.*'],
        ['label' => 'Produk & Stok', 'ikon' => 'inventory_2', 'route' => 'admin.produk.index', 'aktif' => 'admin.produk.*'],
        ['label' => 'Pelanggan', 'ikon' => 'group', 'route' => 'admin.pelanggan.index', 'aktif' => 'admin.pelanggan.*'],
        ['label' => 'Teknisi', 'ikon' => 'engineering', 'route' => 'admin.teknisi.index', 'aktif' => 'admin.teknisi.*'],
        ['label' => 'Laporan', 'ikon' => 'monitoring', 'route' => 'admin.laporan.index', 'aktif' => 'admin.laporan.*'],
        ['label' => 'Konten Beranda', 'ikon' => 'web', 'route' => 'admin.beranda.index', 'aktif' => 'admin.beranda.*'],
        ['label' => 'Pengaturan', 'ikon' => 'settings', 'route' => 'admin.pengaturan.index', 'aktif' => 'admin.pengaturan.*'],
    ];

    // Nama dummy dipakai sampai login admin tersedia.
    $namaAdmin = auth()->user()?->name ?? 'Pak Abdul Haris';
@endphp

<aside
    id="sidebar-admin"
    aria-label="Menu admin"
    class="fixed inset-y-0 left-0 z-50 hidden w-64 border-r border-border-subtle bg-white shadow-[0px_20px_45px_-10px_rgba(15,31,68,0.12)] lg:sticky lg:top-0 lg:block lg:h-screen lg:shrink-0 lg:shadow-none"
>
    <div class="flex h-full flex-col px-5 py-5">
        <div class="flex items-start justify-between gap-3 border-b border-border-subtle pb-4">
            <a href="{{ route('admin.dashboard') }}" class="flex min-h-10 flex-col justify-center">
                <span class="text-[18px] leading-[22.5px] font-bold tracking-[-0.45px] text-primary">{{ config('usaha.nama') }}</span>
                <span class="text-[12px] leading-4 font-semibold text-text-secondary">Panel admin</span>
            </a>
            <button
                type="button"
                data-buka-tutup="sidebar-admin"
                aria-expanded="true"
                aria-controls="sidebar-admin"
                aria-label="Tutup menu"
                class="flex size-10 items-center justify-center rounded-full bg-surface-container-low text-primary lg:hidden"
            >
                <span class="material-symbols-outlined" aria-hidden="true">close</span>
            </button>
        </div>

        <nav aria-label="Navigasi admin" class="mt-3 flex flex-1 flex-col gap-0.5">
            @foreach ($menu as $item)
                @php
                    $aktif = request()->routeIs($item['aktif']);
                    $tersedia = Route::has($item['route']);
                @endphp
                <a
                    @if ($tersedia) href="{{ route($item['route']) }}" @else aria-disabled="true" title="Halaman belum tersedia" @endif
                    @if ($aktif) aria-current="page" @endif
                    @class([
                        'flex min-h-9 items-center gap-3 rounded-xl px-3.5 text-[14px] leading-5 font-semibold transition-colors duration-200',
                        'bg-primary-container text-white' => $aktif,
                        'text-text-primary hover:bg-surface-tint hover:text-primary' => ! $aktif && $tersedia,
                        'cursor-not-allowed text-text-primary' => ! $tersedia,
                    ])
                >
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">{{ $item['ikon'] }}</span>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-3 flex items-center gap-3 rounded-2xl bg-surface-container-low p-3">
            <span class="relative flex size-9 shrink-0 items-center justify-center rounded-full bg-primary-container text-white">
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">person</span>
                <span class="absolute -right-0.5 -bottom-0.5 size-3 rounded-full bg-status-success ring-2 ring-white" aria-hidden="true"></span>
            </span>
            <span class="flex min-w-0 flex-col">
                <span class="truncate text-[14px] leading-5 font-semibold text-on-surface">{{ $namaAdmin }}</span>
                <span class="text-[12px] leading-4 font-semibold text-text-secondary">Admin & Pemilik</span>
            </span>
        </div>
    </div>
</aside>
