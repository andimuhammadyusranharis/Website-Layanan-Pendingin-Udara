@props(['jumlahKeranjang' => count(session('keranjang', []))])

@php
    $menu = [
        ['label' => 'Beranda', 'route' => 'beranda', 'aktif' => 'beranda'],
        ['label' => 'Layanan', 'route' => 'layanan.index', 'aktif' => 'layanan.*'],
        ['label' => 'Toko', 'route' => 'toko.index', 'aktif' => 'toko.*'],
        ['label' => 'Ulasan', 'route' => 'ulasan.index', 'aktif' => 'ulasan.*'],
        ['label' => 'Tentang & Kontak', 'route' => 'kontak.index', 'aktif' => 'kontak.*'],
    ];

    $menuAkun = collect([
        ['label' => 'Pesanan saya', 'route' => 'akun.pesanan.index'],
        ['label' => 'Profil', 'route' => 'akun.profil'],
    ])->filter(fn (array $item): bool => Route::has($item['route']));

    $tautanKeranjang = Route::has('keranjang.index') ? route('keranjang.index') : route('toko.index');
@endphp

<header {{ $attributes->merge(['class' => 'sticky top-0 z-50 bg-white shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)]']) }}>
    <div class="mx-auto flex h-20 w-full max-w-[1280px] items-center justify-between gap-4 px-4 lg:px-8">
        <a href="{{ route('beranda') }}" class="flex shrink-0 items-center gap-3">
            <img src="{{ asset('images/komponen-logo.png') }}" alt="" width="40" height="40" class="size-10" />
            <span class="flex flex-col">
                <span class="text-[18px] leading-[22.5px] font-bold tracking-[-0.45px] whitespace-nowrap text-primary">{{ config('usaha.nama') }}</span>
                <span class="text-[11px] leading-[11px] font-semibold whitespace-nowrap text-text-secondary">{{ config('usaha.slogan') }}</span>
            </span>
        </a>

        <nav aria-label="Navigasi utama" class="hidden items-center gap-1 rounded-full border border-border-subtle bg-surface-container-low/70 px-[11px] py-[7px] lg:flex">
            @foreach ($menu as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @if (request()->routeIs($item['aktif'])) aria-current="page" @endif
                    @class([
                        'rounded-full px-4 py-2 text-[14px] leading-5 font-bold whitespace-nowrap transition-colors duration-200',
                        'bg-primary-container text-white drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)]' => request()->routeIs($item['aktif']),
                        'text-on-surface-variant hover:bg-surface-tint hover:text-primary' => ! request()->routeIs($item['aktif']),
                    ])
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="flex shrink-0 items-center gap-3">
            <a
                href="{{ config('usaha.whatsapp.tautan') }}"
                target="_blank"
                rel="noopener"
                aria-label="WhatsApp resmi {{ config('usaha.nama') }}"
                class="hidden items-center gap-1.5 rounded-full border border-whatsapp/40 bg-whatsapp/20 px-[13px] py-[9px] text-[12px] leading-4 font-semibold text-text-primary lg:flex"
            >
                <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" />
                {{ config('usaha.whatsapp.nomor') }}
            </a>

            <a
                href="{{ $tautanKeranjang }}"
                aria-label="Keranjang belanja ({{ $jumlahKeranjang }} barang)"
                class="relative flex size-11 items-center justify-center rounded-full bg-surface-container-low"
            >
                <img src="{{ asset('images/komponen-ikon-keranjang.svg') }}" alt="" />
                @if ($jumlahKeranjang > 0)
                    <span class="absolute -top-1 -right-1 flex size-5 items-center justify-center rounded-full bg-status-danger text-[11px] leading-[11px] font-bold text-white ring-2 ring-white">{{ $jumlahKeranjang }}</span>
                @endif
            </a>

            @auth
                <div class="relative hidden lg:block">
                    <button
                        type="button"
                        data-buka-tutup="menu-akun"
                        aria-expanded="false"
                        aria-controls="menu-akun"
                        class="flex items-center gap-2.5 border-l border-border-subtle py-1 pl-[13px] text-left"
                    >
                        <span class="flex size-10 items-center justify-center rounded-full bg-surface-tint text-[14px] font-bold text-primary ring-2 ring-primary/20">{{ Str::of(auth()->user()->name)->substr(0, 1)->upper() }}</span>
                        <span class="flex flex-col">
                            <span class="text-[13px] leading-[16.25px] font-bold whitespace-nowrap text-on-surface">{{ auth()->user()->name }}</span>
                            <span class="text-[11px] leading-[13.75px] whitespace-nowrap text-text-secondary">{{ Str::ucfirst(auth()->user()->role ?? 'pelanggan') }}</span>
                        </span>
                        <img src="{{ asset('images/komponen-ikon-panah-bawah.svg') }}" alt="" />
                    </button>

                    <div id="menu-akun" class="absolute right-0 mt-2 hidden w-56 rounded-2xl border border-border-subtle bg-white p-2 shadow-[0px_20px_45px_-10px_rgba(15,31,68,0.12)]">
                        @foreach ($menuAkun as $item)
                            <a href="{{ route($item['route']) }}" class="flex min-h-11 items-center rounded-xl px-3 text-body-md text-text-primary hover:bg-surface-tint">{{ $item['label'] }}</a>
                        @endforeach
                        @if (Route::has('logout'))
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex min-h-11 w-full items-center rounded-xl px-3 text-body-md text-status-danger hover:bg-surface-tint">Keluar</button>
                            </form>
                        @endif
                    </div>
                </div>
            @else
                <div class="hidden items-center gap-2 border-l border-border-subtle pl-[13px] lg:flex">
                    <a href="{{ route('login') }}" class="flex min-h-11 items-center rounded-full border border-border-subtle bg-white px-5 text-[14px] font-bold text-primary transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint">Masuk</a>
                    <a href="{{ route('registrasi') }}" class="flex min-h-11 items-center rounded-full bg-primary-container px-5 text-[14px] font-bold text-white transition-colors duration-200 hover:bg-primary">Daftar</a>
                </div>
            @endauth

            <button
                type="button"
                data-buka-tutup="menu-ponsel"
                aria-expanded="false"
                aria-controls="menu-ponsel"
                aria-label="Buka menu"
                class="flex size-11 items-center justify-center rounded-full bg-surface-container-low text-primary lg:hidden"
            >
                <span class="material-symbols-outlined" aria-hidden="true">menu</span>
            </button>
        </div>
    </div>

    <div id="menu-ponsel" class="hidden border-t border-border-subtle bg-white px-4 py-4 lg:hidden">
        <nav aria-label="Navigasi utama ponsel" class="flex flex-col gap-1">
            @foreach ($menu as $item)
                <a
                    href="{{ route($item['route']) }}"
                    @if (request()->routeIs($item['aktif'])) aria-current="page" @endif
                    @class([
                        'flex min-h-11 items-center rounded-xl px-4 text-label-lg',
                        'bg-primary-container text-white' => request()->routeIs($item['aktif']),
                        'text-on-surface-variant' => ! request()->routeIs($item['aktif']),
                    ])
                >{{ $item['label'] }}</a>
            @endforeach
        </nav>

        <div class="mt-4 flex flex-col gap-2 border-t border-border-subtle pt-4">
            <a
                href="{{ config('usaha.whatsapp.tautan') }}"
                target="_blank"
                rel="noopener"
                class="flex min-h-11 items-center justify-center gap-2 rounded-full border border-whatsapp/40 bg-whatsapp/20 px-4 text-label-lg text-text-primary"
            >
                <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" />
                WhatsApp {{ config('usaha.whatsapp.nomor') }}
            </a>

            @auth
                <p class="px-4 pt-2 text-body-md text-text-secondary">Masuk sebagai <span class="font-bold text-on-surface">{{ auth()->user()->name }}</span></p>
                @foreach ($menuAkun as $item)
                    <a href="{{ route($item['route']) }}" class="flex min-h-11 items-center rounded-xl px-4 text-label-lg text-on-surface-variant">{{ $item['label'] }}</a>
                @endforeach
                @if (Route::has('logout'))
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex min-h-11 w-full items-center rounded-xl px-4 text-label-lg text-status-danger">Keluar</button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="flex min-h-11 items-center justify-center rounded-full border border-border-subtle bg-white px-5 text-label-lg text-primary">Masuk</a>
                <a href="{{ route('registrasi') }}" class="flex min-h-11 items-center justify-center rounded-full bg-primary-container px-5 text-label-lg text-white">Daftar</a>
            @endauth
        </div>
    </div>
</header>
