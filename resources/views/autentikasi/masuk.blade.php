<x-layouts.autentikasi title="Masuk">
    <h1 class="text-[24px] leading-8 font-bold tracking-tight text-text-primary sm:text-[26px]">Masuk ke Akun Anda</h1>

    @if (session('status'))
        <p class="mt-4 flex items-start gap-2 rounded-xl bg-lencana-hijau-latar p-3 text-[14px] leading-5 text-lencana-hijau" role="status">
            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">check_circle</span>
            {{ session('status') }}
        </p>
    @endif

    <form method="POST" action="{{ route('login.store') }}" class="mt-5 flex flex-col gap-5" novalidate>
        @csrf

        <x-autentikasi.kolom nama="identitas" label="Nomor WhatsApp atau Email" ikon="contact_phone" placeholder="0812xxxx atau email" autocomplete="username" autofocus wajib :tanda-wajib="false" />

        <x-autentikasi.kolom nama="password" label="Kata Sandi" ikon="lock" tipe="password" placeholder="Kata sandi akun Anda" autocomplete="current-password" wajib>
            <x-slot:labelKanan>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-[15px] leading-6 font-semibold text-primary hover:underline">Lupa kata sandi?</a>
                @else
                    <a href="{{ config('usaha.whatsapp.tautan') }}?text={{ rawurlencode('Halo, saya lupa kata sandi akun saya.') }}" target="_blank" rel="noopener" class="text-[15px] leading-6 font-semibold text-primary hover:underline">Lupa kata sandi?</a>
                @endif
            </x-slot:labelKanan>
        </x-autentikasi.kolom>

        <label class="flex cursor-pointer items-center gap-3 select-none">
            <input type="checkbox" name="ingat" value="1" class="size-5 cursor-pointer rounded accent-primary-container" @checked(old('ingat', true)) />
            <span class="text-[16px] leading-6 text-text-secondary">Ingat saya di perangkat ini</span>
        </label>

        <button type="submit" class="flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-[16px] leading-6 font-bold text-white shadow-md transition-colors duration-200 hover:bg-primary hover:shadow-[0_8px_20px_rgba(30,99,214,0.25)]">
            Masuk
            <span class="material-symbols-outlined text-[22px]" aria-hidden="true">login</span>
        </button>
    </form>

    <x-autentikasi.tombol-google />

    <p class="mt-5 flex flex-wrap items-center justify-center gap-1.5 text-center text-[16px] leading-6 text-text-secondary">
        Belum memiliki akun pelanggan?
        <a href="{{ route('registrasi') }}" class="inline-flex items-center gap-0.5 font-semibold text-primary hover:underline">
            Daftar Akun Baru
            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">arrow_forward</span>
        </a>
    </p>
</x-layouts.autentikasi>
