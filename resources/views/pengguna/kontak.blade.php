<x-layouts.pengguna title="Tentang & Kontak">
    {{-- Variabel: $profil, $kontak --}}
    @php
        $ikonKeunggulan = ['build', 'map'];

        $tautanPeta = 'https://www.google.com/maps/search/?api=1&query='.rawurlencode(config('usaha.nama').', '.$kontak['alamat']);

        $nomorTelepon = preg_replace('/\D/', '', $kontak['telepon']);
    @endphp

    <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-6 lg:px-8">
        <div class="flex max-w-[760px] flex-col gap-3">
            <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Tentang kami dan kontak</h1>
            <p class="text-body-md text-text-secondary lg:text-body-lg">
                {{ config('usaha.nama') }} melayani servis dan pemasangan AC untuk rumah, ruko, dan instansi di Kabupaten Pangkajene dan Kepulauan sejak {{ $profil['sejak'] }}.
            </p>
        </div>
    </section>

    <div class="mx-auto grid w-full max-w-[1280px] grid-cols-1 gap-5 px-4 pb-10 lg:grid-cols-12 lg:gap-6 lg:px-8 lg:pb-12">
        {{-- Kontak dan lokasi --}}
        <section class="flex flex-col gap-6 lg:order-2 lg:col-span-7" aria-labelledby="judul-kontak">
            <div class="overflow-hidden rounded-3xl border border-border-subtle bg-white">
                <div class="aspect-[21/9] bg-surface-container-low">
                    <x-pengguna.foto :src="$kontak['foto_toko']" alt="Toko dan workshop {{ config('usaha.nama') }}" ikon="storefront" />
                </div>

                <div class="flex flex-col gap-5 p-5 sm:p-6">
                    <h2 id="judul-kontak" class="text-headline-md text-on-surface">Toko dan workshop</h2>

                    <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="flex gap-3 sm:col-span-2">
                            <span class="material-symbols-outlined mt-0.5 text-[22px] text-primary-container" aria-hidden="true">location_on</span>
                            <div class="flex flex-col gap-1">
                                <dt class="text-label-md text-text-secondary">Alamat</dt>
                                <dd class="text-body-semibold text-text-primary">{{ $kontak['alamat'] }}</dd>
                                @if ($kontak['patokan'])
                                    <dd class="text-body-md text-text-secondary">{{ $kontak['patokan'] }}</dd>
                                @endif
                                <dd class="pt-1">
                                    <a href="{{ $tautanPeta }}" target="_blank" rel="noopener" class="inline-flex min-h-11 items-center gap-1.5 text-label-lg text-primary underline-offset-4 hover:underline">
                                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">directions</span>
                                        Buka petunjuk arah di Google Maps
                                    </a>
                                </dd>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="material-symbols-outlined mt-0.5 text-[22px] text-primary-container" aria-hidden="true">schedule</span>
                            <div class="flex flex-col gap-1">
                                <dt class="text-label-md text-text-secondary">Jam buka</dt>
                                <dd class="text-body-semibold text-text-primary">{{ $kontak['jam_operasional'] }}</dd>
                                @if ($kontak['catatan_jam'])
                                    <dd class="text-body-md text-text-secondary">{{ $kontak['catatan_jam'] }}</dd>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <span class="material-symbols-outlined mt-0.5 text-[22px] text-primary-container" aria-hidden="true">call</span>
                            <div class="flex flex-col gap-1">
                                <dt class="text-label-md text-text-secondary">Telepon dan WhatsApp</dt>
                                <dd class="text-body-semibold text-text-primary tabular-nums">{{ $kontak['telepon'] }}</dd>
                                <dd class="text-body-md text-text-secondary">{{ $kontak['catatan_telepon'] }}</dd>
                            </div>
                        </div>
                    </dl>

                    <div class="flex flex-col gap-3 border-t border-border-subtle pt-5 sm:flex-row">
                        <a
                            href="{{ $kontak['tautan_whatsapp'] }}"
                            target="_blank"
                            rel="noopener"
                            class="flex min-h-12 flex-1 items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-label-lg text-white transition-colors duration-200 hover:bg-primary"
                        >
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chat</span>
                            Chat WhatsApp
                        </a>
                        <a
                            href="tel:{{ $nomorTelepon }}"
                            class="flex min-h-12 flex-1 items-center justify-center gap-2 rounded-full border border-border-subtle px-6 text-label-lg text-primary transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint"
                        >
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">call</span>
                            Telepon
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Profil pemilik --}}
        <section class="flex flex-col gap-6 lg:order-1 lg:col-span-5" aria-labelledby="judul-profil">
            <div class="flex flex-col gap-5 rounded-3xl border border-border-subtle bg-white p-5 sm:p-6">
                <h2 id="judul-profil" class="text-headline-md text-on-surface">Profil</h2>

                <div class="aspect-[16/10] overflow-hidden rounded-2xl bg-surface-container-low">
                    <x-pengguna.foto :src="$profil['foto']" :alt="$profil['nama_pemilik'].' di workshop'" ikon="engineering" />
                </div>

                <div class="flex flex-col gap-1">
                    <p class="text-headline-sm font-bold text-text-primary">{{ $profil['nama_pemilik'] }}</p>
                    <p class="text-body-md text-text-secondary">{{ $profil['jabatan'] }}, sejak {{ $profil['sejak'] }}</p>
                </div>

                <ul class="flex flex-col gap-2">
                    @foreach ($profil['keunggulan'] as $keunggulan)
                        <li class="flex items-center gap-3 text-body-semibold text-text-primary">
                            <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-surface-tint text-primary-container">
                                <span class="material-symbols-outlined text-[22px]" aria-hidden="true">{{ $ikonKeunggulan[$loop->index % count($ikonKeunggulan)] }}</span>
                            </span>
                            {{ $keunggulan }}
                        </li>
                    @endforeach
                </ul>

                @if ($profil['kutipan'])
                    <figure class="border-l-4 border-primary-container pl-5">
                        <blockquote class="text-body-md text-text-primary">{{ $profil['kutipan'] }}</blockquote>
                        <figcaption class="pt-3 text-label-lg text-primary">{{ $profil['nama_pemilik'] }}</figcaption>
                    </figure>
                @endif
            </div>
        </section>
    </div>
</x-layouts.pengguna>
