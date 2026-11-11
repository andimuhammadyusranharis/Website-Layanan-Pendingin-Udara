<x-layouts.pengguna title="Ulasan">
    {{-- Variabel: $ringkasan, $kategoriUlasan, $ulasan --}}
<<<<<<< HEAD

    @php
        $inisial = fn ($nama) => collect(preg_split('/\s+/', trim($nama)))
            ->filter()
            ->take(2)
            ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
            ->implode('');
        $tanggalIndo = fn ($tgl) => \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j F Y');
        $angka = fn ($n) => number_format($n, 1, ',', '.');
    @endphp

    <section class="mx-auto w-full max-w-[1280px] px-4 py-space-xl lg:px-8">
        {{-- Judul halaman --}}
        <div class="flex flex-col gap-space-md sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Ulasan Pelanggan</h1>
                <p class="mt-space-xs text-body-md text-text-secondary">
                    Cerita nyata dari pelanggan CV. Tiga Putra Teknik di Pangkep.
                </p>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex min-h-11 w-full items-center justify-center rounded-full bg-primary-container px-space-lg text-label-lg text-on-primary transition hover:bg-primary sm:w-auto">
                Tulis Ulasan Saya
            </a>
        </div>

        @if (empty($ulasan))
            {{-- Tampilan kosong --}}
            <div class="mt-space-xl rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-xl text-center shadow-sm">
                <p class="text-headline-sm text-text-primary">Belum ada ulasan</p>
                <p class="mt-space-xs text-body-md text-text-secondary">
                    Jadilah yang pertama menulis ulasan setelah pekerjaan selesai.
                </p>
            </div>
        @else
            <div class="mt-space-xl grid gap-space-lg lg:grid-cols-[360px_1fr] lg:items-start">

                {{-- Kolom kiri: ringkasan rating --}}
                <aside class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-lg shadow-sm lg:sticky lg:top-24">
                    <div class="flex items-center gap-space-md">
                        <p class="text-display-hero-mobile text-text-primary">{{ $angka($ringkasan['rata_rata']) }}</p>
                        <div>
                            <div class="flex text-status-warning" role="img"
                                 aria-label="Rata-rata rating {{ $angka($ringkasan['rata_rata']) }} dari 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="h-6 w-6 {{ $i <= round($ringkasan['rata_rata']) ? '' : 'text-outline-variant' }}"
                                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path d="M10 1.5l2.6 5.3 5.9.9-4.25 4.15 1 5.85L10 14.9l-5.25 2.8 1-5.85L1.5 7.7l5.9-.9L10 1.5z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-body-md text-text-secondary">
                                dari {{ number_format($ringkasan['jumlah'], 0, ',', '.') }} ulasan
                            </p>
                        </div>
                    </div>

                    <p class="mt-space-md rounded-xl bg-surface-tint px-space-md py-space-sm text-body-semibold text-primary">
                        {{ $ringkasan['persen_puas'] }}% pelanggan puas
                    </p>

                    {{-- Sebaran bintang --}}
                    <ul class="mt-space-lg space-y-space-sm">
                        @for ($bintang = 5; $bintang >= 1; $bintang--)
                            @php
                                $jumlahBintang = $ringkasan['sebaran'][$bintang] ?? 0;
                                $persen = $ringkasan['jumlah'] > 0 ? ($jumlahBintang / $ringkasan['jumlah']) * 100 : 0;
                            @endphp
                            <li class="flex items-center gap-space-sm text-body-md text-text-primary">
                                <span class="w-14 shrink-0">{{ $bintang }} bintang</span>
                                <span class="h-3 flex-1 overflow-hidden rounded-full bg-surface-container">
                                    <span class="block h-full rounded-full bg-primary-container"
                                          style="width: {{ $persen }}%"></span>
                                </span>
                                <span class="w-10 shrink-0 text-right text-text-secondary">{{ $jumlahBintang }}</span>
                            </li>
                        @endfor
                    </ul>

                    {{-- Penilaian per aspek --}}
                    <div class="mt-space-lg border-t border-border-subtle pt-space-lg">
                        <h2 class="text-headline-sm text-text-primary">Penilaian per aspek</h2>
                        <ul class="mt-space-md space-y-space-md">
                            @foreach ($ringkasan['aspek'] as $aspek)
                                <li>
                                    <div class="flex items-center justify-between text-body-md text-text-primary">
                                        <span>{{ $aspek['nama'] }}</span>
                                        <span class="font-semibold">{{ $angka($aspek['nilai']) }} / 5</span>
                                    </div>
                                    <div class="mt-1 h-2 overflow-hidden rounded-full bg-surface-container">
                                        <div class="h-full rounded-full bg-tertiary-container"
                                             style="width: {{ ($aspek['nilai'] / 5) * 100 }}%"></div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>

                {{-- Kolom kanan: filter dan daftar ulasan --}}
                <div>
                    {{-- Chip kategori --}}
                    <div class="flex flex-wrap gap-space-xs" role="group" aria-label="Filter kategori">
                        <button type="button" data-chip="semua" aria-pressed="true"
                                class="group min-h-11 rounded-full border border-border-subtle bg-surface-container-lowest px-space-md text-label-lg text-on-surface transition aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-on-primary">
                            <span class="mr-1 hidden group-aria-pressed:inline" aria-hidden="true">✓</span>Semua ({{ $ringkasan['jumlah'] }})
                        </button>
                        @foreach ($kategoriUlasan as $kategori)
                            <button type="button" data-chip="{{ $kategori['slug'] }}" aria-pressed="false"
                                    class="group min-h-11 rounded-full border border-border-subtle bg-surface-container-lowest px-space-md text-label-lg text-on-surface transition aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-on-primary">
                                <span class="mr-1 hidden group-aria-pressed:inline" aria-hidden="true">✓</span>{{ $kategori['nama'] }} ({{ $kategori['jumlah'] }})
                            </button>
                        @endforeach
                    </div>

                    {{-- Filter bintang dan urutan --}}
                    <div class="mt-space-md flex flex-col gap-space-sm sm:flex-row sm:items-center sm:justify-between">
                        <p id="info-hasil" class="text-body-md text-text-secondary" aria-live="polite"></p>
                        <div class="flex flex-col gap-space-sm sm:flex-row">
                            <label class="flex items-center gap-space-xs text-body-md text-text-primary">
                                <span>Bintang</span>
                                <select id="filter-bintang"
                                        class="min-h-11 flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-space-sm text-body-md text-text-primary">
                                    <option value="semua">Semua</option>
                                    @for ($b = 5; $b >= 1; $b--)
                                        <option value="{{ $b }}">{{ $b }} bintang</option>
                                    @endfor
                                </select>
                            </label>
                            <label class="flex items-center gap-space-xs text-body-md text-text-primary">
                                <span>Urutan</span>
                                <select id="urutan"
                                        class="min-h-11 flex-1 rounded-xl border border-outline-variant bg-surface-container-lowest px-space-sm text-body-md text-text-primary">
                                    <option value="terbaru">Terbaru</option>
                                    <option value="terlama">Terlama</option>
                                </select>
                            </label>
                        </div>
                    </div>

                    {{-- Daftar ulasan --}}
                    <div id="daftar-ulasan" class="mt-space-lg grid gap-space-md md:grid-cols-2">
                        @foreach ($ulasan as $u)
                            <article data-ulasan
                                     data-kategori="{{ $u['kategori'] }}"
                                     data-rating="{{ $u['rating'] }}"
                                     data-tanggal="{{ $u['tanggal'] }}"
                                     class="flex flex-col rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-lg shadow-sm">
                                <div class="flex items-center gap-space-md">
                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary-fixed text-label-lg text-on-primary-fixed-variant"
                                         aria-hidden="true">
                                        {{ $inisial($u['nama']) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-body-semibold text-text-primary">{{ $u['nama'] }}</p>
                                        <p class="text-body-md text-text-secondary">{{ $u['lokasi'] }}</p>
                                    </div>
                                </div>

                                <div class="mt-space-md flex items-center gap-space-xs">
                                    <div class="flex text-status-warning" role="img"
                                         aria-label="Rating {{ $u['rating'] }} dari 5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <svg class="h-5 w-5 {{ $i <= $u['rating'] ? '' : 'text-outline-variant' }}"
                                                 viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M10 1.5l2.6 5.3 5.9.9-4.25 4.15 1 5.85L10 14.9l-5.25 2.8 1-5.85L1.5 7.7l5.9-.9L10 1.5z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-body-md text-text-secondary">{{ $u['rating'] }} dari 5</span>
                                </div>

                                <p class="mt-space-sm text-body-semibold text-primary">{{ $u['layanan'] }}</p>
                                <p class="mt-space-xs flex-1 text-body-md text-text-primary">{{ $u['komentar'] }}</p>

                                <div class="mt-space-md flex flex-wrap items-center justify-between gap-x-space-md gap-y-1 border-t border-border-subtle pt-space-md text-body-md text-text-secondary">
                                    <p>Teknisi: <span class="font-semibold text-text-primary">{{ $u['teknisi'] ?? 'Pembelian Toko' }}</span></p>
                                    <time datetime="{{ $u['tanggal'] }}">{{ $tanggalIndo($u['tanggal']) }}</time>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Pesan bila hasil filter kosong --}}
                    <div id="hasil-kosong"
                         class="mt-space-lg hidden rounded-2xl border border-border-subtle bg-surface-container-lowest p-space-xl text-center shadow-sm">
                        <p class="text-headline-sm text-text-primary">Tidak ada ulasan yang cocok</p>
                        <p class="mt-space-xs text-body-md text-text-secondary">
                            Coba ganti kategori atau pilihan bintang.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </section>

    @push('scripts')
        <script>
            (function () {
                const daftar = document.getElementById('daftar-ulasan');
                if (!daftar) return;

                const kartu = Array.from(daftar.querySelectorAll('[data-ulasan]'));
                const chips = Array.from(document.querySelectorAll('[data-chip]'));
                const filterBintang = document.getElementById('filter-bintang');
                const urutan = document.getElementById('urutan');
                const kosong = document.getElementById('hasil-kosong');
                const info = document.getElementById('info-hasil');
                let kategoriAktif = 'semua';

                function terapkan() {
                    const bintang = filterBintang.value;
                    const terlama = urutan.value === 'terlama';

                    const cocok = kartu.filter(function (k) {
                        const cocokKategori = kategoriAktif === 'semua' || k.dataset.kategori === kategoriAktif;
                        const cocokBintang = bintang === 'semua' || k.dataset.rating === bintang;
                        return cocokKategori && cocokBintang;
                    });

                    cocok.sort(function (a, b) {
                        return terlama
                            ? a.dataset.tanggal.localeCompare(b.dataset.tanggal)
                            : b.dataset.tanggal.localeCompare(a.dataset.tanggal);
                    });

                    kartu.forEach(function (k) { k.classList.add('hidden'); });
                    cocok.forEach(function (k) {
                        k.classList.remove('hidden');
                        daftar.appendChild(k);
                    });

                    kosong.classList.toggle('hidden', cocok.length > 0);
                    info.textContent = 'Menampilkan ' + cocok.length + ' dari ' + kartu.length + ' ulasan';
                }

                chips.forEach(function (chip) {
                    chip.addEventListener('click', function () {
                        kategoriAktif = chip.dataset.chip;
                        chips.forEach(function (c) {
                            c.setAttribute('aria-pressed', c === chip ? 'true' : 'false');
                        });
                        terapkan();
                    });
                });

                filterBintang.addEventListener('change', terapkan);
                urutan.addEventListener('change', terapkan);
                terapkan();
            })();
        </script>
    @endpush
</x-layouts.pengguna>
=======
    @php
        $warnaInisial = [
            'bg-primary-fixed text-primary',
            'bg-secondary-fixed text-secondary',
            'bg-tertiary-fixed text-tertiary',
        ];

        $inisial = fn (string $nama): string => Str::of($nama)
            ->replaceMatches('/^(Ibu|Bu|Bapak|Pak|Hj\.|H\.|Drs\.|Dr\.)\s+/i', '')
            ->explode(' ')
            ->take(2)
            ->map(fn (string $kata): string => Str::upper(Str::substr($kata, 0, 1)))
            ->implode('');

        $nilaiTertinggi = max(1, max($ringkasan['sebaran']));

        $tautanTulisUlasan = Route::has('akun.pesanan.index') ? route('akun.pesanan.index') : route('login');

        $kelasPilihan = 'flex min-h-11 items-center gap-1 rounded-full border border-border-subtle bg-white px-4 text-label-md lg:min-h-10 lg:px-3.5 text-on-surface-variant transition-colors duration-200 hover:border-primary-container hover:text-primary aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container';
    @endphp

    <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-6 lg:px-8">
        <div class="flex max-w-[720px] flex-col gap-3">
            <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Ulasan pelanggan</h1>
            <p class="text-body-md text-text-secondary lg:text-body-lg">Ditulis oleh pelanggan setelah pekerjaan selesai dan dikonfirmasi.</p>
        </div>
    </section>

    @if ($ringkasan['jumlah'] > 0)
        {{-- Ringkasan rating --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pb-6 lg:px-8" aria-labelledby="judul-ringkasan">
            <h2 id="judul-ringkasan" class="sr-only">Ringkasan rating</h2>
            <div class="grid grid-cols-1 gap-6 rounded-3xl border border-border-subtle bg-white p-5 sm:p-6 lg:grid-cols-12 lg:gap-8">
                <div class="flex flex-col items-start gap-2 lg:col-span-3">
                    <p class="flex items-baseline gap-1">
                        <span class="text-[52px] leading-[52px] font-extrabold tracking-[-1.5px] text-secondary tabular-nums">{{ number_format($ringkasan['rata_rata'], 1, ',', '.') }}</span>
                        <span class="text-headline-sm text-text-secondary">/ 5</span>
                    </p>
                    <x-pengguna.bintang :nilai="$ringkasan['rata_rata']" ukuran="text-[20px]" />
                    <p class="text-body-md text-text-secondary">Dari {{ $ringkasan['jumlah'] }} ulasan pelanggan</p>
                    <p class="flex items-center gap-1.5 text-body-semibold text-status-success">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">sentiment_satisfied</span>
                        {{ $ringkasan['persen_puas'] }}% pelanggan puas
                    </p>
                </div>

                <dl class="flex flex-col justify-center gap-1.5 lg:col-span-5">
                    @foreach ($ringkasan['sebaran'] as $bintang => $jumlah)
                        <div class="grid grid-cols-[56px_1fr_40px] items-center gap-3">
                            <dt class="flex items-center gap-1 text-label-lg text-text-primary">
                                {{ $bintang }}
                                <span class="material-symbols-outlined text-[18px] text-status-warning" style="font-variation-settings: 'FILL' 1" aria-hidden="true">star</span>
                                <span class="sr-only">bintang</span>
                            </dt>
                            <dd class="h-2.5 overflow-hidden rounded-full bg-surface-container" aria-hidden="true">
                                <span class="block h-full rounded-full bg-primary-container" style="width: {{ round($jumlah / $nilaiTertinggi * 100) }}%"></span>
                            </dd>
                            <dd class="text-right text-body-md text-text-secondary tabular-nums">{{ $jumlah }}</dd>
                        </div>
                    @endforeach
                </dl>

                <div class="flex flex-col justify-center gap-3 lg:col-span-4">
                    <a href="{{ $tautanTulisUlasan }}" class="flex min-h-11 items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-label-lg text-white transition-colors duration-200 hover:bg-primary">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">rate_review</span>
                        Beri ulasan dari pesanan saya
                    </a>
                    <p class="text-label-sm text-text-secondary">Ulasan bisa ditulis setelah Anda mengonfirmasi pekerjaan selesai.</p>
                </div>
            </div>
        </section>

        {{-- Daftar ulasan --}}
        <section data-saring class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-labelledby="judul-daftar-ulasan">
            <h2 id="judul-daftar-ulasan" class="sr-only">Daftar ulasan</h2>

            <div class="mb-4 flex flex-col gap-3 rounded-2xl border border-border-subtle bg-white p-4 sm:p-5">
                <div class="flex flex-wrap gap-2" role="group" aria-label="Saring menurut layanan">
                    <button type="button" data-saring-pilihan="kategori" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua ({{ $ringkasan['jumlah'] }})</button>
                    @foreach ($kategoriUlasan as $kategori)
                        <button type="button" data-saring-pilihan="kategori" data-nilai="{{ $kategori['slug'] }}" aria-pressed="false" class="{{ $kelasPilihan }}">{{ $kategori['nama'] }} ({{ $kategori['jumlah'] }})</button>
                    @endforeach
                </div>

                <div class="flex flex-col gap-3 border-t border-border-subtle pt-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Saring menurut bintang">
                        <span class="text-body-md text-text-secondary">Bintang:</span>
                        <button type="button" data-saring-pilihan="rating" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua</button>
                        @foreach ([5, 4, 3] as $bintang)
                            <button type="button" data-saring-pilihan="rating" data-nilai="{{ $bintang }}" aria-pressed="false" class="{{ $kelasPilihan }}">
                                {{ $bintang }}
                                <span class="material-symbols-outlined text-[16px]" style="font-variation-settings: 'FILL' 1" aria-hidden="true">star</span>
                                <span class="sr-only">bintang</span>
                            </button>
                        @endforeach
                    </div>

                    <label class="flex items-center gap-3">
                        <span class="text-body-md text-text-secondary">Urutkan</span>
                        <select data-saring-urut class="h-11 rounded-xl border border-border-subtle bg-surface-container-low px-3 text-body-md text-text-primary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none">
                            <option value="">Terbaru</option>
                            <option value="rating:desc">Bintang tertinggi</option>
                            <option value="rating:asc">Bintang terendah</option>
                        </select>
                    </label>
                </div>
            </div>

            <p data-saring-status data-satuan="ulasan" role="status" class="sr-only"></p>

            <div data-saring-daftar class="grid grid-cols-1 items-start gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-5">
                @foreach ($ulasan as $item)
                    <figure
                        data-saring-item
                        data-kategori="{{ $item['kategori'] }}"
                        data-rating="{{ $item['rating'] }}"
                        class="flex flex-col gap-3 rounded-2xl border border-border-subtle bg-white p-5"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <span @class(['flex size-10 shrink-0 items-center justify-center rounded-full text-label-lg', $warnaInisial[$loop->index % count($warnaInisial)]]) aria-hidden="true">{{ $inisial($item['nama']) }}</span>
                                <figcaption class="flex flex-col">
                                    <span class="text-body-semibold text-text-primary">{{ $item['nama'] }}</span>
                                    <span class="text-label-sm text-text-secondary">{{ $item['lokasi'] }}</span>
                                </figcaption>
                            </div>
                            <x-pengguna.bintang :nilai="$item['rating']" class="shrink-0" />
                        </div>

                        <p class="self-start rounded-lg bg-surface-tint px-2.5 py-1 text-label-sm text-primary">{{ $item['layanan'] }}</p>

                        <blockquote class="text-body-md text-text-primary">{{ $item['komentar'] }}</blockquote>

                        <p class="flex flex-wrap justify-between gap-x-4 gap-y-1 border-t border-border-subtle pt-3 text-label-sm text-text-secondary">
                            <span>{{ $item['teknisi'] ? 'Dikerjakan '.$item['teknisi'] : 'Pembelian di toko' }}</span>
                            <time datetime="{{ $item['tanggal'] }}">{{ Carbon\Carbon::parse($item['tanggal'])->locale('id')->translatedFormat('j F Y') }}</time>
                        </p>
                    </figure>
                @endforeach
            </div>

            <div data-saring-kosong hidden class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-white px-6 py-12 text-center">
                <span class="material-symbols-outlined text-[40px] text-text-secondary" aria-hidden="true">reviews</span>
                <p class="text-body-semibold text-text-primary">Belum ada ulasan untuk pilihan ini.</p>
                <button type="button" data-saring-reset class="flex min-h-11 items-center rounded-full border border-border-subtle px-5 text-label-lg text-primary hover:bg-surface-tint">Tampilkan semua ulasan</button>
            </div>
        </section>
    @else
        <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12">
            <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-white px-6 py-12 text-center">
                <span class="material-symbols-outlined text-[40px] text-text-secondary" aria-hidden="true">reviews</span>
                <p class="text-body-semibold text-text-primary">Belum ada ulasan.</p>
                <p class="max-w-[480px] text-body-md text-text-secondary">Ulasan pertama akan tampil di sini setelah pelanggan mengonfirmasi pekerjaan selesai.</p>
                <a href="{{ route('layanan.index') }}" class="flex min-h-11 items-center rounded-full bg-primary-container px-5 text-label-lg text-white hover:bg-primary">Lihat layanan</a>
            </div>
        </section>
    @endif
</x-layouts.pengguna>
>>>>>>> origin/main
