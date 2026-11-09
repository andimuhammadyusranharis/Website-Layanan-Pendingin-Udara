@props(['adaNotifikasi' => true])

@php
    $tautanModeTeknisi = Route::has('teknisi.tugas.index') ? route('teknisi.tugas.index') : null;
    $tautanNotifikasi = Route::has('admin.notifikasi.index') ? route('admin.notifikasi.index') : null;
    $tautanAkun = Route::has('admin.pengaturan.index') ? route('admin.pengaturan.index') : null;
@endphp

<header class="sticky top-0 z-40 bg-background/90 backdrop-blur">
    <div class="flex h-[72px] items-center justify-between gap-3 px-4 lg:justify-end lg:px-6">
        <button
            type="button"
            data-buka-tutup="sidebar-admin"
            aria-expanded="false"
            aria-controls="sidebar-admin"
            aria-label="Buka menu admin"
            class="flex size-10 items-center justify-center rounded-full bg-white text-primary shadow-[0px_4px_6px_-2px_rgba(20,64,127,0.06)] lg:hidden"
        >
            <span class="material-symbols-outlined" aria-hidden="true">menu</span>
        </button>

        <div class="flex items-center gap-3">
            <a
                @if ($tautanModeTeknisi) href="{{ $tautanModeTeknisi }}" @else aria-disabled="true" title="Halaman teknisi belum tersedia" @endif
                class="flex min-h-10 items-center gap-2 rounded-full bg-surface-tint px-4 text-[14px] leading-5 font-semibold text-primary-container transition-colors duration-200 hover:bg-primary-fixed"
            >
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">swap_horiz</span>
                <span class="hidden sm:inline">Mode Teknisi</span>
            </a>

            <a
                @if ($tautanNotifikasi) href="{{ $tautanNotifikasi }}" @else aria-disabled="true" @endif
                aria-label="Notifikasi{{ $adaNotifikasi ? ' (ada yang baru)' : '' }}"
                class="relative flex size-10 items-center justify-center rounded-full bg-white text-text-primary shadow-[0px_4px_6px_-2px_rgba(20,64,127,0.06)]"
            >
                <span class="material-symbols-outlined" aria-hidden="true">notifications</span>
                @if ($adaNotifikasi)
                    <span class="absolute top-2 right-2.5 size-2.5 rounded-full bg-status-danger ring-2 ring-white" aria-hidden="true"></span>
                @endif
            </a>

            <a
                @if ($tautanAkun) href="{{ $tautanAkun }}" @else aria-disabled="true" @endif
                aria-label="Akun saya"
                class="flex size-10 items-center justify-center rounded-full bg-primary-container text-white"
            >
                <span class="material-symbols-outlined" aria-hidden="true">person</span>
            </a>
        </div>
    </div>
</header>
