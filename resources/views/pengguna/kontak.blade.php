
<x-layouts.pengguna title="Tentang & Kontak">

    <section class="mx-auto w-full max-w-[1100px] px-4 py-10 lg:px-8">

        {{-- Header halaman --}}
        <div class="mb-8">
            <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">
                Tentang & Kontak
            </h1>
            <p class="mt-2 text-body-md text-text-secondary">
                Informasi mengenai usaha dan layanan kami.
            </p>
        </div>

        @if (empty($profil) && empty($kontak))
            <div class="rounded-xl border border-border-subtle bg-surface-container-lowest p-8 text-center">
                <h2 class="text-headline-sm text-text-primary">
                    Informasi belum tersedia
                </h2>
                <p class="mt-2 text-body-md text-text-secondary">
                    Silakan kembali lagi beberapa saat lagi.
                </p>
            </div>
        @endif

        {{-- Tentang Kami --}}
        @if (!empty($profil))
            <div class="mb-8 rounded-xl border border-border-subtle bg-surface-container-lowest p-6 lg:p-8">

                <p class="text-label-lg text-primary">
                    TENTANG KAMI
                </p>

                <h2 class="mt-3 text-headline-md text-text-primary">
                    {{ $profil['nama_pemilik'] }}
                </h2>

                <p class="mt-1 text-body-md text-text-secondary">
                    {{ $profil['jabatan'] }}
                </p>

                <div class="mt-4 border-t border-border-subtle pt-4">
                    <p class="text-body-md text-text-secondary">
                        Melayani sejak
                        <span class="font-medium text-text-primary">
                            {{ $profil['sejak'] }}
                        </span>
                    </p>
                </div>

                @if (!empty($profil['keunggulan']))
                    <div class="mt-5">
                        <p class="text-label-lg text-text-primary">
                            Keunggulan Layanan
                        </p>

                        <ul class="mt-3 flex flex-wrap gap-2">
                            @foreach ($profil['keunggulan'] as $unggul)
                                <li class="rounded-lg bg-surface-container-low px-3 py-2 text-body-sm text-text-primary">
                                    {{ $unggul }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (!empty($profil['kutipan']))
                    <blockquote class="mt-5 border-l-2 border-primary-container pl-4 text-body-md text-text-secondary">
                        "{{ $profil['kutipan'] }}"
                    </blockquote>
                @endif

            </div>
        @endif

        {{-- Informasi Kontak --}}
        @if (!empty($kontak))
            <div>
                <h2 class="mb-4 text-headline-md text-text-primary">
                    Informasi Kontak
                </h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                    {{-- Lokasi --}}
                    <div class="rounded-xl border border-border-subtle bg-surface-container-lowest p-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">
                                location_on
                            </span>
                            <h3 class="text-headline-sm text-text-primary">
                                Lokasi
                            </h3>
                        </div>

                        <p class="mt-4 text-body-md text-text-primary">
                            {{ $kontak['alamat'] }}
                        </p>

                        @if (!empty($kontak['patokan']))
                            <p class="mt-2 text-body-sm text-text-secondary">
                                {{ $kontak['patokan'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Jam Operasional --}}
                    <div class="rounded-xl border border-border-subtle bg-surface-container-lowest p-5">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">
                                schedule
                            </span>
                            <h3 class="text-headline-sm text-text-primary">
                                Jam Operasional
                            </h3>
                        </div>

                        <p class="mt-4 text-body-md text-text-primary">
                            {{ $kontak['jam_operasional'] }}
                        </p>

                        @if (!empty($kontak['catatan_jam']))
                            <p class="mt-2 text-body-sm text-text-secondary">
                                {{ $kontak['catatan_jam'] }}
                            </p>
                        @endif
                    </div>

                    {{-- Telepon / WhatsApp --}}
                    <a href="{{ $kontak['tautan_whatsapp'] }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="rounded-xl border border-border-subtle bg-surface-container-lowest p-5 transition hover:bg-surface-container-low md:col-span-2">

                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-primary">
                                call
                            </span>
                            <h3 class="text-headline-sm text-text-primary">
                                Telepon / WhatsApp
                            </h3>
                        </div>

                        <p class="mt-4 text-body-md font-medium text-primary">
                            {{ $kontak['telepon'] }}
                        </p>

                        @if (!empty($kontak['catatan_telepon']))
                            <p class="mt-2 text-body-sm text-text-secondary">
                                {{ $kontak['catatan_telepon'] }}
                            </p>
                        @endif

                        <p class="mt-3 text-label-lg text-primary">
                            Hubungi melalui WhatsApp →
                        </p>
                    </a>

                </div>
            </div>
        @endif

    </section>

</x-layouts.pengguna>
