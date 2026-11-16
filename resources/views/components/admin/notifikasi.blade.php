{{--
    Notifikasi singkat (toast) untuk hasil aksi di panel admin. Dibaca dari flash session:
    - with('status', '...') untuk aksi yang berhasil, hilang sendiri setelah beberapa detik.
    - with('galat', '...') untuk aksi yang gagal, tetap tampil sampai ditutup.
    Pewaktu dihentikan saat kursor di atasnya atau saat fokus di dalamnya (lihat app.js).
--}}
@php
    $berhasil = session('status');
    $gagal = session('galat');
@endphp

@if ($berhasil || $gagal)
    <div class="pointer-events-none fixed inset-x-4 bottom-4 z-[60] flex justify-center sm:inset-x-auto sm:right-6 sm:bottom-6 sm:justify-end">
        <div
            data-notifikasi="{{ $gagal ? 'galat' : 'berhasil' }}"
            role="{{ $gagal ? 'alert' : 'status' }}"
            class="pointer-events-auto flex w-full max-w-[400px] items-start gap-3 rounded-2xl border bg-white p-4 shadow-[0px_16px_40px_-8px_rgba(20,64,127,0.3)] transition duration-200 {{ $gagal ? 'border-status-danger/40' : 'border-status-success/40' }}"
        >
            <span @class([
                'flex size-9 shrink-0 items-center justify-center rounded-full',
                'bg-lencana-merah-latar text-status-danger' => $gagal,
                'bg-lencana-hijau-latar text-lencana-hijau' => ! $gagal,
            ])>
                <span class="material-symbols-outlined text-[22px]" aria-hidden="true">{{ $gagal ? 'error' : 'check_circle' }}</span>
            </span>

            <div class="flex min-w-0 flex-1 flex-col gap-0.5">
                <p class="text-[15px] leading-6 font-bold text-on-surface">{{ $gagal ? 'Gagal' : 'Berhasil' }}</p>
                <p class="text-[14px] leading-5 break-words text-text-secondary">{{ $gagal ?: $berhasil }}</p>
            </div>

            <button
                type="button"
                data-notifikasi-tutup
                aria-label="Tutup notifikasi"
                class="-m-1 flex size-11 shrink-0 items-center justify-center rounded-xl text-text-secondary transition-colors duration-200 hover:bg-surface-container-low hover:text-text-primary focus-visible:outline-2 focus-visible:outline-primary-container"
            >
                <span class="material-symbols-outlined text-[20px]" aria-hidden="true">close</span>
            </button>
        </div>
    </div>
@endif
