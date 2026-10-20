<x-layouts.pengguna title="Tentang & Kontak">
    {{-- Variabel: $profil, $kontak --}}

    @php
        $fotoAda = fn ($path) => filled($path) && file_exists(public_path($path));
    @endphp

    <section class="mx-auto w-full max-w-[1280px] px-4 py-space-xl lg:px-8">
        <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Tentang & Kontak</h1>
        <p class="mt-space-xs text-body-md text-text-secondary">
            Kenali kami dan hubungi kapan saja untuk konsultasi keluhan AC Anda.
        </p>

        @if (empty($profil) && empty($kontak))
            {{-- Tampilan kosong --}}
            <div class="mt-space-xl rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-xl text-center shadow-sm">
                <p class="text-headline-sm text-text-primary">Informasi belum tersedia</p>
                <p class="mt-space-xs text-body-md text-text-secondary">Silakan kembali lagi beberapa saat lagi.</p>
            </div>
        @endif

        {{-- Profil usaha --}}
        @if (!empty($profil))
            <div class="mt-space-xl grid items-center gap-space-lg lg:grid-cols-2 lg:gap-space-xl">
                <div class="overflow-hidden rounded-2xl border border-border-subtle bg-surface-container shadow-sm">
                    @if ($fotoAda($profil['foto'] ?? null))
                        <img src="{{ asset($profil['foto']) }}"
                             alt="Foto {{ $profil['nama_pemilik'] ?? 'pemilik usaha' }}"
                             class="aspect-[4/3] w-full object-cover" loading="lazy" />
                    @else
                        <div class="flex aspect-[4/3] w-full flex-col items-center justify-center gap-space-xs text-text-secondary">
                            <span class="material-symbols-outlined text-[48px]" aria-hidden="true">person</span>
                            <span class="text-body-md">Foto belum tersedia</span>
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-label-lg text-primary">Tentang Kami</p>
                    <h2 class="mt-space-xs text-headline-md text-text-primary">{{ $profil['nama_pemilik'] }}</h2>
                    <p class="text-body-lg text-text-secondary">{{ $profil['jabatan'] }}</p>
                    <p class="mt-space-xs text-body-md text-text-secondary">Melayani sejak {{ $profil['sejak'] }}</p>

                    @if (!empty($profil['keunggulan']))
                        <ul class="mt-space-lg flex flex-wrap gap-space-sm">
                            @foreach ($profil['keunggulan'] as $unggul)
                                <li class="inline-flex min-h-11 items-center gap-space-xs rounded-full bg-surface-tint px-space-md text-label-lg text-primary">
                                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">verified</span>
                                    {{ $unggul }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    @if (!empty($profil['kutipan']))
                        <blockquote class="mt-space-lg border-l-4 border-primary-container pl-space-md text-body-lg text-text-primary">
                            “{{ $profil['kutipan'] }}”
                        </blockquote>
                    @endif
                </div>
            </div>
        @endif

        {{-- Kontak dan lokasi --}}
        @if (!empty($kontak))
            <div class="mt-space-xl grid gap-space-lg lg:grid-cols-2 lg:gap-space-xl">
                <div class="overflow-hidden rounded-2xl border border-border-subtle bg-surface-container shadow-sm">
                    @if ($fotoAda($kontak['foto_toko'] ?? null))
                        <img src="{{ asset($kontak['foto_toko']) }}" alt="Foto toko CV. Tiga Putra Teknik"
                             class="aspect-[4/3] h-full w-full object-cover" loading="lazy" />
                    @else
                        <div class="flex aspect-[4/3] w-full flex-col items-center justify-center gap-space-xs text-text-secondary">
                            <span class="material-symbols-outlined text-[48px]" aria-hidden="true">storefront</span>
                            <span class="text-body-md">Foto toko belum tersedia</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-space-md">
                    {{-- Lokasi --}}
                    <div class="flex gap-space-md rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-lg shadow-sm">
                        <span class="material-symbols-outlined shrink-0 text-primary-container" aria-hidden="true">location_on</span>
                        <div>
                            <h3 class="text-headline-sm text-text-primary">Lokasi</h3>
                            <p class="mt-space-xs text-body-md text-text-primary">{{ $kontak['alamat'] }}</p>
                            @if (!empty($kontak['patokan']))
                                <p class="mt-space-xs text-body-md text-text-secondary">{{ $kontak['patokan'] }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Jam operasional --}}
                    <div class="flex gap-space-md rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-lg shadow-sm">
                        <span class="material-symbols-outlined shrink-0 text-primary-container" aria-hidden="true">schedule</span>
                        <div>
                            <h3 class="text-headline-sm text-text-primary">Jam Operasional</h3>
                            <p class="mt-space-xs text-body-md text-text-primary">{{ $kontak['jam_operasional'] }}</p>
                            @if (!empty($kontak['catatan_jam']))
                                <p class="mt-space-xs text-body-md text-text-secondary">{{ $kontak['catatan_jam'] }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Telepon / WhatsApp --}}
                    <a href="{{ $kontak['tautan_whatsapp'] }}" target="_blank" rel="noopener"
                       class="flex min-h-11 gap-space-md rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-lg shadow-sm transition hover:border-primary-container hover:bg-surface-tint focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container">
                        <span class="material-symbols-outlined shrink-0 text-primary-container" aria-hidden="true">call</span>
                        <div>
                            <h3 class="text-headline-sm text-text-primary">Telp / WhatsApp</h3>
                            <p class="mt-space-xs text-body-semibold text-primary">{{ $kontak['telepon'] }}</p>
                            @if (!empty($kontak['catatan_telepon']))
                                <p class="mt-space-xs text-body-md text-text-secondary">{{ $kontak['catatan_telepon'] }}</p>
                            @endif
                            <p class="mt-space-xs text-label-lg text-primary">Chat lewat WhatsApp →</p>
                        </div>
                    </a>
                </div>
            </div>
        @endif
    </section>
</x-layouts.pengguna>