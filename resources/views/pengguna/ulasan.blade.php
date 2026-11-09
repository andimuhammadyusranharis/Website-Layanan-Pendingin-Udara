<x-layouts.pengguna title="Tentang & Kontak">
    {{-- Variabel: $profil, $kontak --}}

    {{-- Banner biru --}}
    <div class="bg-primary-container text-on-primary">
        <div class="mx-auto w-full max-w-[1100px] px-4 py-10 lg:px-8">
            <h1 class="text-headline-lg-mobile lg:text-headline-lg">Tentang & Kontak</h1>
            <p class="mt-2 text-body-lg text-on-primary-container">Informasi mengenai usaha dan layanan kami.</p>
        </div>
    </div>

    <section class="mx-auto w-full max-w-[1100px] space-y-6 px-4 py-10 lg:px-8">

        {{-- Tampilan kosong --}}
        @if (empty($profil) && empty($kontak))
            <div class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-8 text-center">
                <h2 class="text-headline-sm text-text-primary">Informasi belum tersedia</h2>
                <p class="mt-2 text-body-md text-text-secondary">Silakan kembali lagi beberapa saat lagi.</p>
            </div>
        @endif

        {{-- Tentang kami --}}
        @if (!empty($profil))
            <div class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-6 shadow-sm lg:p-8">
                <p class="text-label-lg text-primary">TENTANG KAMI</p>
                <h2 class="mt-2 text-headline-md text-text-primary">{{ $profil['nama_pemilik'] }}</h2>
                <p class="text-body-md text-text-secondary">{{ $profil['jabatan'] }} · Melayani sejak {{ $profil['sejak'] }}</p>

                @if (!empty($profil['keunggulan']))
                    <ul class="mt-5 flex flex-wrap gap-2">
                        @foreach ($profil['keunggulan'] as $unggul)
                            <li class="rounded-full bg-surface-tint px-4 py-2 text-label-lg text-primary">{{ $unggul }}</li>
                        @endforeach
                    </ul>
                @endif

                @if (!empty($profil['kutipan']))
                    <blockquote class="mt-5 border-l-4 border-primary-container pl-4 text-body-md text-text-primary">
                        “{{ $profil['kutipan'] }}”
                    </blockquote>
                @endif
            </div>
        @endif

        {{-- Informasi kontak --}}
        @if (!empty($kontak))
            @php
                $info = [
                    ['ikon' => 'location_on', 'judul' => 'Lokasi', 'isi' => $kontak['alamat'], 'catatan' => $kontak['patokan'] ?? null],
                    ['ikon' => 'schedule', 'judul' => 'Jam Operasional', 'isi' => $kontak['jam_operasional'], 'catatan' => $kontak['catatan_jam'] ?? null],
                ];
            @endphp

            <div class="grid gap-4 md:grid-cols-2">
                @foreach ($info as $i)
                    <div class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-surface-tint text-primary">
                                <span class="material-symbols-outlined" aria-hidden="true">{{ $i['ikon'] }}</span>
                            </div>
                            <h3 class="text-headline-sm text-text-primary">{{ $i['judul'] }}</h3>
                        </div>
                        <p class="mt-4 text-body-md text-text-primary">{{ $i['isi'] }}</p>
                        @if (!empty($i['catatan']))
                            <p class="mt-2 text-body-md text-text-secondary">{{ $i['catatan'] }}</p>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Tombol WhatsApp --}}
            <a href="{{ $kontak['tautan_whatsapp'] }}" target="_blank" rel="noopener noreferrer"
               class="flex min-h-11 flex-col gap-3 rounded-2xl bg-primary-container p-6 text-on-primary shadow-sm transition hover:bg-primary sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-headline-sm">Telepon / WhatsApp</h3>
                    <p class="mt-1 text-headline-md">{{ $kontak['telepon'] }}</p>
                    @if (!empty($kontak['catatan_telepon']))
                        <p class="mt-1 text-body-md text-on-primary-container">{{ $kontak['catatan_telepon'] }}</p>
                    @endif
                </div>
                <span class="inline-flex min-h-11 items-center justify-center rounded-full bg-surface-container-lowest px-6 text-label-lg text-primary">
                    Chat WhatsApp →
                </span>
            </a>
        @endif

    </section>
</x-layouts.pengguna>