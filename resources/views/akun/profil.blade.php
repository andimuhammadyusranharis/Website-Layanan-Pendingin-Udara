<x-layouts.pengguna title="Profil">
    {{-- Variabel: $user, $pelanggan, $bisaGantiPassword --}}
    {{-- Catatan: dibuat dari gambar JPG desain Figma (file aslinya tidak ditemukan). Mohon dicek saat review. --}}
    @php
    $inisial = Str::of($user->name)->explode(' ')->filter()->take(2)->map(fn ($kata) => Str::upper(Str::substr($kata, 0, 1)))->implode('');

    $kelasKartu = 'flex flex-col gap-5 rounded-2xl border border-border-subtle bg-white p-5 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] sm:p-6';
    $kelasInput = 'h-[50px] w-full rounded-xl border border-border-subtle bg-white px-3 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none';
    $kelasTeksArea = 'w-full rounded-xl border border-border-subtle bg-white px-3 py-3 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none';
    $kelasTombol = 'flex min-h-12 items-center justify-center rounded-full bg-primary-container px-7 text-label-lg text-white transition-colors duration-200 hover:bg-secondary';
    $kelasMenu = 'flex min-h-11 items-center gap-3 rounded-xl px-3 text-body-semibold';
    @endphp

    <div class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-12 lg:px-8">
        <nav aria-label="Jejak halaman" class="flex items-center gap-2 text-label-md text-text-secondary">
            <a href="{{ route('beranda') }}" class="text-primary hover:underline">Beranda</a>
            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">chevron_right</span>
            <span>Akun</span>
            <span class="material-symbols-outlined text-[16px]" aria-hidden="true">chevron_right</span>
            <span class="text-on-surface">Profil Saya</span>
        </nav>

        <div class="mt-5 grid grid-cols-1 gap-6 lg:grid-cols-12">
            {{-- Menu akun --}}
            <aside class="lg:col-span-3">
                <div class="flex flex-col gap-2 rounded-2xl border border-border-subtle bg-white p-4">
                    <div class="flex items-center gap-3 pb-3">
                        <span class="flex size-12 shrink-0 items-center justify-center rounded-full bg-primary-container text-label-lg text-white" aria-hidden="true">{{ $inisial }}</span>
                        <div class="min-w-0">
                            <p class="truncate text-body-semibold text-on-surface">{{ $user->name }}</p>
                            <p class="text-label-sm text-text-secondary">Pelanggan sejak {{ $user->created_at?->format('Y') }}</p>
                        </div>
                    </div>

                    <a href="{{ route('akun.profil') }}" aria-current="page" class="{{ $kelasMenu }} bg-surface-tint text-primary">
                        <span class="material-symbols-outlined" aria-hidden="true">person</span>
                        Profil Saya
                    </a>

                    @if (Route::has('pesanan.index'))
                    <a href="{{ route('pesanan.index') }}" class="{{ $kelasMenu }} text-text-primary hover:bg-surface-tint">
                        <span class="material-symbols-outlined" aria-hidden="true">description</span>
                        Pesanan Saya
                    </a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="{{ $kelasMenu }} w-full text-error hover:bg-error-container/40">
                            <span class="material-symbols-outlined" aria-hidden="true">logout</span>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Isi --}}
            <div class="flex flex-col gap-6 lg:col-span-9">
                <h1 class="text-headline-lg-mobile text-secondary lg:text-headline-lg">Profil Saya</h1>

                {{-- Data diri --}}
                <form method="POST" action="{{ route('akun.profil.update') }}" class="{{ $kelasKartu }}">
                    @csrf
                    @method('PUT')

                    <h2 class="text-headline-md text-secondary">Data diri &amp; alamat</h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-label-md text-text-primary">Nama lengkap</span>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $kelasInput }}" />
                            @error('name')
                            <span class="text-label-sm text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-1.5">
                            <span class="text-label-md text-text-primary">Nomor WhatsApp</span>
                            <input type="tel" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" required placeholder="081234567890" class="{{ $kelasInput }}" />
                            @error('no_telepon')
                            <span class="text-label-sm text-error">{{ $message }}</span>
                            @else
                            <span class="text-label-sm text-text-secondary">Dipakai untuk login dan notifikasi pesanan</span>
                            @enderror
                        </label>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-label-md text-text-primary">Email</span>
                        <p class="text-body-md text-text-secondary">{{ $user->email }}</p>
                    </div>

                    <label class="flex flex-col gap-1.5">
                        <span class="text-label-md text-text-primary">Alamat utama</span>
                        <textarea name="alamat" rows="4" placeholder="Contoh: Jl. Sultan Hasanuddin No. 12, Pangkajene" class="{{ $kelasTeksArea }}">{{ old('alamat', $pelanggan?->alamat) }}</textarea>
                        @error('alamat')
                        <span class="text-label-sm text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="flex flex-col gap-1.5">
                        <span class="text-label-md text-text-primary">Patokan</span>
                        <input type="text" name="patokan" value="{{ old('patokan', $pelanggan?->patokan) }}" placeholder="Contoh: depan masjid raya" class="{{ $kelasInput }}" />
                        @error('patokan')
                        <span class="text-label-sm text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <div class="flex justify-end">
                        <button type="submit" class="{{ $kelasTombol }}">Simpan Perubahan</button>
                    </div>
                </form>

                {{-- Ganti kata sandi (akun Google tidak punya kata sandi) --}}
                @if ($bisaGantiPassword)
                <form method="POST" action="{{ route('akun.profil.password') }}" class="{{ $kelasKartu }}">
                    @csrf
                    @method('PUT')

                    <h2 class="text-headline-md text-secondary">Ganti kata sandi</h2>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <label class="flex flex-col gap-1.5">
                            <span class="text-label-md text-text-primary">Kata sandi lama</span>
                            <input type="password" name="current_password" required autocomplete="current-password" class="{{ $kelasInput }}" />
                            @error('current_password')
                            <span class="text-label-sm text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-1.5">
                            <span class="text-label-md text-text-primary">Kata sandi baru</span>
                            <input type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" class="{{ $kelasInput }}" />
                            @error('password')
                            <span class="text-label-sm text-error">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-1.5 md:col-start-2">
                            <span class="text-label-md text-text-primary">Konfirmasi kata sandi baru</span>
                            <input type="password" name="password_confirmation" required autocomplete="new-password" class="{{ $kelasInput }}" />
                        </label>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="{{ $kelasTombol }}">Ganti kata sandi</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
</x-layouts.pengguna>