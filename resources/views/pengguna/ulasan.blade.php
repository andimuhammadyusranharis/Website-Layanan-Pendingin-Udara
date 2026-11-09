<x-layouts.pengguna title="Ulasan">
    {{-- Variabel: $ringkasan, $kategoriUlasan, $ulasan --}}

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