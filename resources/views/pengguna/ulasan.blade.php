<x-layouts.pengguna title="Ulasan">
    {{-- Variabel: $ringkasan, $kategoriUlasan, $ulasan --}}

    @php
        $inisial = fn ($nama) => collect(preg_split('/\s+/', trim($nama)))
            ->take(2)
            ->map(fn ($kata) => mb_strtoupper(mb_substr($kata, 0, 1)))
            ->implode('');
        $tanggalIndo = fn ($tgl) => \Carbon\Carbon::parse($tgl)->locale('id')->translatedFormat('j F Y');
        $chip = 'group min-h-11 rounded-full border border-border-subtle bg-surface-container-lowest px-4 text-label-lg text-on-surface aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-on-primary';
        $pilihan = 'min-h-11 rounded-xl border border-outline-variant bg-surface-container-lowest px-3 text-body-md text-text-primary';
    @endphp

    {{-- Banner biru --}}
    <div class="bg-primary-container text-on-primary">
        <div class="mx-auto flex w-full max-w-[1100px] flex-col gap-4 px-4 py-10 sm:flex-row sm:items-end sm:justify-between lg:px-8">
            <div>
                <h1 class="text-headline-lg-mobile lg:text-headline-lg">Ulasan Pelanggan</h1>
                <p class="mt-2 text-body-lg text-on-primary-container">Cerita nyata dari pelanggan kami di Pangkep.</p>
            </div>
            <a href="{{ route('login') }}"
               class="inline-flex min-h-11 items-center justify-center rounded-full bg-surface-container-lowest px-6 text-label-lg text-primary">
                Tulis Ulasan Saya
            </a>
        </div>
    </div>

    <section class="mx-auto w-full max-w-[1100px] space-y-6 px-4 py-10 lg:px-8">

        @if (empty($ulasan))
            {{-- Tampilan kosong --}}
            <div class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-8 text-center">
                <h2 class="text-headline-sm text-text-primary">Belum ada ulasan</h2>
                <p class="mt-2 text-body-md text-text-secondary">Jadilah yang pertama menulis ulasan.</p>
            </div>
        @else
            {{-- Ringkasan rating --}}
            <div class="grid gap-6 rounded-2xl border border-border-subtle bg-surface-container-lowest p-6 shadow-sm md:grid-cols-2">
                <div>
                    <p class="text-display-hero-mobile text-text-primary">{{ number_format($ringkasan['rata_rata'], 1, ',', '.') }}</p>
                    <p class="text-headline-sm text-status-warning" aria-label="Rata-rata {{ $ringkasan['rata_rata'] }} dari 5">
                        {{ str_repeat('★', round($ringkasan['rata_rata'])) }}{{ str_repeat('☆', 5 - round($ringkasan['rata_rata'])) }}
                    </p>
                    <p class="mt-1 text-body-md text-text-secondary">dari {{ $ringkasan['jumlah'] }} ulasan</p>
                    <p class="mt-3 inline-block rounded-full bg-surface-tint px-4 py-2 text-label-lg text-primary">
                        {{ $ringkasan['persen_puas'] }}% pelanggan puas
                    </p>
                </div>

                <ul class="space-y-2">
                    @foreach ($ringkasan['sebaran'] as $bintang => $jumlah)
                        <li class="flex items-center gap-3 text-body-md text-text-primary">
                            <span class="w-16 shrink-0">{{ $bintang }} bintang</span>
                            <span class="h-3 flex-1 overflow-hidden rounded-full bg-surface-container">
                                <span class="block h-full rounded-full bg-primary-container"
                                      style="width: {{ $ringkasan['jumlah'] > 0 ? ($jumlah / $ringkasan['jumlah']) * 100 : 0 }}%"></span>
                            </span>
                            <span class="w-10 text-right text-text-secondary">{{ $jumlah }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Penilaian per aspek --}}
            <div class="grid gap-4 sm:grid-cols-3">
                @foreach ($ringkasan['aspek'] as $aspek)
                    <div class="rounded-2xl border border-border-subtle bg-surface-container-lowest p-5 text-center">
                        <p class="text-headline-md text-primary">{{ number_format($aspek['nilai'], 1, ',', '.') }}</p>
                        <p class="text-body-md text-text-secondary">{{ $aspek['nama'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Filter --}}
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2" role="group" aria-label="Filter kategori">
                    <button type="button" data-chip="semua" aria-pressed="true" class="{{ $chip }}">Semua</button>
                    @foreach ($kategoriUlasan as $kategori)
                        <button type="button" data-chip="{{ $kategori['slug'] }}" aria-pressed="false" class="{{ $chip }}">
                            {{ $kategori['nama'] }} ({{ $kategori['jumlah'] }})
                        </button>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-3 text-body-md text-text-primary">
                    <label>Bintang
                        <select id="filter-bintang" class="{{ $pilihan }}">
                            <option value="semua">Semua</option>
                            @for ($b = 5; $b >= 1; $b--)
                                <option value="{{ $b }}">{{ $b }} bintang</option>
                            @endfor
                        </select>
                    </label>
                    <label>Urutan
                        <select id="urutan" class="{{ $pilihan }}">
                            <option value="terbaru">Terbaru</option>
                            <option value="terlama">Terlama</option>
                        </select>
                    </label>
                    <span id="info-hasil" class="text-text-secondary" aria-live="polite"></span>
                </div>
            </div>

            {{-- Daftar ulasan --}}
            <div id="daftar-ulasan" class="grid gap-4 md:grid-cols-2">
                @foreach ($ulasan as $u)
                    <article data-ulasan data-kategori="{{ $u['kategori'] }}" data-rating="{{ $u['rating'] }}" data-tanggal="{{ $u['tanggal'] }}"
                             class="flex flex-col rounded-2xl border border-border-subtle bg-surface-container-lowest p-6 shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-surface-tint text-label-lg text-primary" aria-hidden="true">
                                {{ $inisial($u['nama']) }}
                            </div>
                            <div>
                                <p class="text-body-semibold text-text-primary">{{ $u['nama'] }}</p>
                                <p class="text-body-md text-text-secondary">{{ $u['lokasi'] }}</p>
                            </div>
                        </div>

                        <p class="mt-4 text-body-lg text-status-warning" aria-label="Rating {{ $u['rating'] }} dari 5">
                            {{ str_repeat('★', $u['rating']) }}{{ str_repeat('☆', 5 - $u['rating']) }}
                            <span class="text-body-md text-text-secondary">{{ $u['rating'] }} dari 5</span>
                        </p>
                        <p class="mt-2 text-body-semibold text-primary">{{ $u['layanan'] }}</p>
                        <p class="mt-1 flex-1 text-body-md text-text-primary">{{ $u['komentar'] }}</p>

                        <div class="mt-4 flex flex-wrap justify-between gap-2 border-t border-border-subtle pt-4 text-body-md text-text-secondary">
                            <span>Teknisi: {{ $u['teknisi'] ?? 'Pembelian Toko' }}</span>
                            <time datetime="{{ $u['tanggal'] }}">{{ $tanggalIndo($u['tanggal']) }}</time>
                        </div>
                    </article>
                @endforeach
            </div>

            <div id="hasil-kosong" class="hidden rounded-2xl border border-border-subtle bg-surface-container-lowest p-8 text-center">
                <h2 class="text-headline-sm text-text-primary">Tidak ada ulasan yang cocok</h2>
                <p class="mt-2 text-body-md text-text-secondary">Coba ganti kategori atau pilihan bintang.</p>
            </div>
        @endif

    </section>

    @push('scripts')
        <script>
            (function () {
                const daftar = document.getElementById('daftar-ulasan');
                if (!daftar) return;

                const kartu = Array.from(daftar.children);
                const chips = document.querySelectorAll('[data-chip]');
                const bintang = document.getElementById('filter-bintang');
                const urutan = document.getElementById('urutan');
                let kategori = 'semua';

                function terapkan() {
                    const cocok = kartu.filter(function (k) {
                        return (kategori === 'semua' || k.dataset.kategori === kategori)
                            && (bintang.value === 'semua' || k.dataset.rating === bintang.value);
                    });

                    cocok.sort(function (a, b) {
                        const hasil = a.dataset.tanggal.localeCompare(b.dataset.tanggal);
                        return urutan.value === 'terlama' ? hasil : -hasil;
                    });

                    kartu.forEach(function (k) { k.classList.add('hidden'); });
                    cocok.forEach(function (k) {
                        k.classList.remove('hidden');
                        daftar.appendChild(k);
                    });

                    document.getElementById('hasil-kosong').classList.toggle('hidden', cocok.length > 0);
                    document.getElementById('info-hasil').textContent = cocok.length + ' dari ' + kartu.length + ' ulasan';
                }

                chips.forEach(function (chip) {
                    chip.addEventListener('click', function () {
                        kategori = chip.dataset.chip;
                        chips.forEach(function (c) { c.setAttribute('aria-pressed', c === chip); });
                        terapkan();
                    });
                });

                bintang.addEventListener('change', terapkan);
                urutan.addEventListener('change', terapkan);
                terapkan();
            })();
        </script>
    @endpush
</x-layouts.pengguna>