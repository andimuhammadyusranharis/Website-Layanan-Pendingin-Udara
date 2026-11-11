<x-layouts.autentikasi title="Daftar">
    <h1 class="text-[24px] leading-8 font-bold tracking-tight text-text-primary sm:text-[26px]">Buat Akun</h1>

    <form method="POST" action="{{ route('registrasi.store') }}" class="mt-5 flex flex-col gap-5" novalidate>
        @csrf

        <x-autentikasi.kolom nama="name" label="Nama Lengkap" ikon="badge" placeholder="Contoh: Raihan Nur Faiz" autocomplete="name" autofocus wajib />

        <x-autentikasi.kolom nama="email" label="Alamat Email" ikon="mail" tipe="email" placeholder="nama@email.com" autocomplete="email" wajib />

        <x-autentikasi.kolom
            nama="password"
            label="Kata Sandi"
            ikon="lock"
            tipe="password"
            placeholder="Minimal 8 karakter"
            autocomplete="new-password"
            data-kekuatan-sandi="kekuatan-sandi"
            wajib
        >
            <div class="flex flex-col gap-1.5" id="kekuatan-sandi" data-label-awal="Masukkan minimal 8 karakter">
                <div class="grid h-1.5 grid-cols-3 gap-1.5 overflow-hidden rounded-full" aria-hidden="true">
                    <span data-batang class="rounded-full bg-surface-container-high transition-colors duration-200"></span>
                    <span data-batang class="rounded-full bg-surface-container-high transition-colors duration-200"></span>
                    <span data-batang class="rounded-full bg-surface-container-high transition-colors duration-200"></span>
                </div>
                @unless ($errors->has('password'))
                    <p data-label class="text-[14px] leading-5 text-text-secondary" aria-live="polite">Masukkan minimal 8 karakter</p>
                @endunless
            </div>
        </x-autentikasi.kolom>

        <x-autentikasi.kolom
            nama="password_confirmation"
            label="Konfirmasi Kata Sandi"
            ikon="lock_reset"
            tipe="password"
            placeholder="Ulangi kata sandi"
            autocomplete="new-password"
            bantuan="Harus sama dengan kata sandi di atas"
            wajib
        />

        <div class="flex flex-col gap-2">
            <label class="flex cursor-pointer items-start gap-3">
                <input
                    type="checkbox"
                    name="setuju"
                    value="1"
                    class="mt-0.5 size-5 shrink-0 cursor-pointer rounded accent-primary-container"
                    @checked(old('setuju'))
                    @error('setuju') aria-invalid="true" aria-describedby="setuju-galat" @enderror
                    required
                />
                <span class="text-[15px] leading-6 text-text-secondary">
                    Saya menyetujui
                    <a href="{{ route('kontak.index') }}" class="font-semibold text-primary hover:underline">Syarat &amp; Ketentuan</a>
                    serta
                    <a href="{{ route('kontak.index') }}" class="font-semibold text-primary hover:underline">Kebijakan Privasi</a>
                    servis AC {{ config('usaha.nama') }} Pangkep.
                </span>
            </label>
            @error('setuju')
                <p id="setuju-galat" class="flex items-start gap-1.5 text-[14px] leading-5 text-status-danger">
                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="flex min-h-12 w-full items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-[16px] leading-6 font-bold text-white shadow-md transition-colors duration-200 hover:bg-primary hover:shadow-[0_8px_20px_rgba(30,99,214,0.25)]">
            <span class="material-symbols-outlined text-[22px]" aria-hidden="true">check_circle</span>
            Daftar
        </button>
    </form>

    <x-autentikasi.tombol-google label="Daftar dengan Google" />

    <p class="mt-5 border-t border-border-subtle pt-5 text-center text-[16px] leading-6 text-text-secondary">
        Sudah memiliki akun?
        <a href="{{ route('login') }}" class="font-semibold text-primary hover:underline">Masuk di sini</a>
    </p>
</x-layouts.autentikasi>
