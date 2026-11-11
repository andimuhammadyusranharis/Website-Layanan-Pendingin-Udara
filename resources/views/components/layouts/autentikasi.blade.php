@props(['title' => null])

<!doctype html>
<html lang="id">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ $title ? $title.' - '.config('usaha.nama') : config('usaha.nama') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex min-h-screen flex-col bg-background font-sans text-text-primary antialiased">
        <header class="bg-surface/90 shadow-[0_1px_8px_rgba(20,64,127,0.05)] backdrop-blur-xl">
            <div class="mx-auto flex h-20 w-full max-w-[1280px] items-center justify-between gap-4 px-4 lg:px-8">
                <a href="{{ route('beranda') }}" class="flex min-h-11 flex-col justify-center">
                    <span class="text-[16px] leading-5 font-bold tracking-tight text-primary">{{ config('usaha.nama') }}</span>
                    <span class="text-[13px] leading-[18px] text-text-secondary">Layanan AC Terpercaya Pangkep</span>
                </a>
                <a
                    href="{{ config('usaha.whatsapp.tautan') }}"
                    target="_blank"
                    rel="noopener"
                    class="flex min-h-11 items-center gap-2 rounded-full bg-surface-container-low px-4 text-[14px] leading-5 text-text-secondary transition-colors duration-200 hover:bg-surface-container"
                >
                    <span class="material-symbols-outlined text-[22px] text-status-success" aria-hidden="true">headset_mic</span>
                    <span class="hidden sm:inline">Butuh bantuan?</span>
                    <strong class="font-bold text-text-primary">{{ config('usaha.whatsapp.nomor') }}</strong>
                </a>
            </div>
        </header>

        <main class="flex w-full flex-1 items-start justify-center px-4 py-6 sm:py-8">
            <div class="w-full max-w-[504px] rounded-3xl bg-white p-5 shadow-[0_15px_35px_-5px_rgba(20,64,127,0.08),0_4px_10px_-2px_rgba(20,64,127,0.03)] sm:p-7">
                {{ $slot }}
            </div>
        </main>

        <footer class="bg-surface-container-low py-6 shadow-[0_-1px_6px_rgba(20,64,127,0.03)]">
            <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-3 px-4 text-[14px] leading-5 sm:flex-row sm:items-center sm:justify-between lg:px-8">
                <p class="text-text-secondary">&copy; {{ now()->year }} {{ config('usaha.nama') }} Pangkep. Seluruh hak cipta dilindungi.</p>
                <nav aria-label="Tautan bantuan" class="flex flex-wrap items-center gap-x-6 gap-y-2 font-semibold text-text-primary">
                    <a href="{{ route('kontak.index') }}" class="hover:text-primary">Kebijakan Privasi</a>
                    <span class="size-1 rounded-full bg-outline-variant" aria-hidden="true"></span>
                    <a href="{{ route('kontak.index') }}" class="hover:text-primary">Syarat &amp; Ketentuan</a>
                    <span class="size-1 rounded-full bg-outline-variant" aria-hidden="true"></span>
                    <a href="{{ config('usaha.whatsapp.tautan') }}" target="_blank" rel="noopener" class="hover:text-primary">Bantuan Teknis</a>
                </nav>
            </div>
        </footer>
    </body>
</html>
