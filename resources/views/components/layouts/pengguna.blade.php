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
        @stack('styles')
    </head>
    <body class="flex min-h-screen flex-col bg-background font-sans text-on-surface antialiased">
        <x-pengguna.header />

        @if (session('status'))
            <div class="border-b border-lencana-hijau/20 bg-lencana-hijau-latar" role="status">
                <p class="mx-auto flex w-full max-w-[1280px] items-start gap-2 px-4 py-3 text-[15px] leading-6 text-lencana-hijau lg:px-8">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">check_circle</span>
                    {{ session('status') }}
                </p>
            </div>
        @endif

        <main {{ $attributes->merge(['class' => 'w-full flex-1']) }}>
            {{ $slot }}
        </main>

        <x-pengguna.footer />

        @stack('scripts')
    </body>
</html>
