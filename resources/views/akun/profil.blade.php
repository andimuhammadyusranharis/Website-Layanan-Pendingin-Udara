<x-layouts.pengguna title="Profil">
    {{-- Variabel: $user, $pelanggan, $bisaGantiPassword --}}
    {{-- Catatan: tampilan seadanya karena desain halaman ini tidak ditemukan di Figma.
         Gaya mengikuti halaman publik yang sudah ada. Mohon dicek saat review. --}}
    @php
    $kelasKartu = 'flex flex-col gap-4 rounded-2xl border border-border-subtle bg-white p-4 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] sm:p-5';
    $kelasInput = 'h-12 w-full rounded-xl border border-border-subtle bg-surface-container-low px-3 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none';
    $kelasTeksArea = 'w-full rounded-xl border border-border-subtle bg-surface-container-low px-3 py-3 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none';
    $kelasTombol = 'flex min-h-11 items-center justify-center rounded-full bg-primary-container px-6 text-label-lg text-white transition-colors duration-200 hover:bg-primary';
    @endphp

    <section class="mx-auto flex w-full max-w-[720px] flex-col gap-6 px-4 pt-8 pb-12 lg:px-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Profil saya</h1>
            <p class="text-body-md text-text-secondary">Kelola data diri dan kata sandi akun Anda.</p>
        </div>

        {{-- Data diri --}}
        <form method="POST" action="{{ route('akun.profil.update') }}" class="{{ $kelasKartu }}">
            @csrf
            @method('PUT')

            <h2 class="text-headline-sm font-bold text-on-surface">Data diri</h2>

            <div class="flex flex-col gap-1">
                <span class="text-label-lg text-text-secondary">Email</span>
                <p class="text-body-md text-text-primary">{{ $user->email }}</p>
            </div>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Nama</span>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $kelasInput }}" />
                @error('name')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Nomor telepon</span>
                <input type="tel" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required placeholder="081234567890" class="{{ $kelasInput }}" />
                @error('no_telepon')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Alamat</span>
                <textarea name="alamat" rows="3" class="{{ $kelasTeksArea }}">{{ old('alamat', $pelanggan?->alamat) }}</textarea>
                @error('alamat')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Patokan</span>
                <input type="text" name="patokan" value="{{ old('patokan', $pelanggan?->patokan) }}" placeholder="Contoh: depan minimarket" class="{{ $kelasInput }}" />
                @error('patokan')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <div>
                <button type="submit" class="{{ $kelasTombol }}">Simpan</button>
            </div>
        </form>

        {{-- Ganti kata sandi (akun Google tidak punya kata sandi, jadi tidak ditampilkan) --}}
        @if ($bisaGantiPassword)
        <form method="POST" action="{{ route('akun.profil.password') }}" class="{{ $kelasKartu }}">
            @csrf
            @method('PUT')

            <h2 class="text-headline-sm font-bold text-on-surface">Ganti kata sandi</h2>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Kata sandi lama</span>
                <input type="password" name="current_password" required autocomplete="current-password" class="{{ $kelasInput }}" />
                @error('current_password')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Kata sandi baru</span>
                <input type="password" name="password" required autocomplete="new-password" class="{{ $kelasInput }}" />
                @error('password')
                <span class="text-label-sm text-error">{{ $message }}</span>
                @enderror
            </label>

            <label class="flex flex-col gap-1">
                <span class="text-label-lg text-text-primary">Konfirmasi kata sandi baru</span>
                <input type="password" name="password_confirmation" required autocomplete="new-password" class="{{ $kelasInput }}" />
            </label>

            <div>
                <button type="submit" class="{{ $kelasTombol }}">Ganti kata sandi</button>
            </div>
        </form>
        @endif
    </section>
</x-layouts.pengguna>