<x-layouts.admin title="Detail Pelanggan">
    {{-- Variabel: $user (User dengan relasi pelanggan) --}}
    @php
    $baris = [
    'Nama' => $user->name,
    'Email' => $user->email,
    'Nomor telepon' => $user->no_telepon ?? '-',
    'Alamat' => $user->pelanggan?->alamat ?? '-',
    'Patokan' => $user->pelanggan?->patokan ?? '-',
    'Terdaftar' => $user->created_at?->translatedFormat('d F Y'),
    ];

    $nomorWa = $user->no_telepon ? preg_replace('/^0/', '62', $user->no_telepon) : null;
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="{{ $user->name }}" deskripsi="Detail data pelanggan." />

        <x-admin.kartu>
            <dl class="grid grid-cols-1 gap-x-8 gap-y-5 md:grid-cols-2">
                @foreach ($baris as $label => $nilai)
                <div class="flex flex-col gap-1">
                    <dt class="text-[14px] leading-5 font-semibold text-text-secondary">{{ $label }}</dt>
                    <dd class="text-[15px] leading-6 break-words text-text-primary">{{ $nilai }}</dd>
                </div>
                @endforeach
            </dl>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('admin.pelanggan.index') }}" class="flex min-h-11 items-center rounded-xl border border-border-subtle bg-white px-5 text-[14px] leading-5 font-semibold text-text-primary hover:bg-surface-container-low">Kembali ke daftar</a>
                @if ($nomorWa)
                <a href="https://wa.me/{{ $nomorWa }}" target="_blank" rel="noopener" class="flex min-h-11 items-center gap-2 rounded-xl bg-primary-container px-5 text-[14px] leading-5 font-semibold text-white hover:bg-primary">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chat</span>
                    Hubungi lewat WhatsApp
                </a>
                @endif
            </div>
        </x-admin.kartu>
    </div>
</x-layouts.admin>