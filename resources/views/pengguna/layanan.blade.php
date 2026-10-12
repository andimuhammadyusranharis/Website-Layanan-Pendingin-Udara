
<x-layouts.pengguna title="Layanan">
    {{-- HERO DAN FILTER --}}
    <section class="relative overflow-hidden bg-surface-container-low">
        <div class="mx-auto w-full max-w-[1280px] px-4 py-12 lg:px-8 lg:py-16">
            <div class="max-w-2xl">
                <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">
                    Daftar Layanan
                    <span class="text-primary">CV. Tiga Putra Teknik</span>
                </h1>

                <p class="mt-3 text-body-md text-text-secondary">
                    Solusi pendingin ruangan higienis, terstandar, dan bergaransi
                    14–30 hari untuk rumah tinggal, perkantoran, instansi, hingga
                    area komersial di Kabupaten Pangkajene dan Kepulauan.
                </p>
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <span class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-on-surface">
                    ✓ Garansi Resmi 14–30 Hari
                </span>
                <span class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-on-surface">
                    ↔ Bebas Biaya Transport Lokal
                </span>
                <span class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-on-surface">
                    ✓ SOP Terpal Anti-Ceceran
                </span>
            </div>

            <div class="mt-8 rounded-2xl bg-white p-4 shadow-sm">
                <label for="cari-layanan" class="sr-only">Cari layanan AC</label>
                <input
                    id="cari-layanan"
                    type="search"
                    placeholder="Cari jenis servis AC..."
                    class="w-full rounded-xl border-0 bg-surface-container-low px-4 py-3 text-body-md text-on-surface placeholder:text-text-secondary focus:outline-2 focus:outline-primary"
                >

                <div class="mt-3 flex flex-wrap gap-2">
                    <button
                        type="button"
                        data-kategori="semua"
                        aria-pressed="true"
                        class="filter-layanan min-h-11 rounded-full bg-primary px-4 py-2 text-sm font-semibold text-white"
                    >
                        Semua Layanan ({{ count($layanan) }})
                    </button>

                    @foreach ($kategoriLayanan as $kategori)
                        <button
                            type="button"
                            data-kategori="{{ $kategori['slug'] }}"
                            aria-pressed="false"
                            class="filter-layanan min-h-11 rounded-full bg-surface-container-low px-4 py-2 text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high"
                        >
                            {{ $kategori['nama'] }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- DAFTAR KARTU LAYANAN --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 py-10 lg:px-8 lg:py-12">
        <div class="mb-6">
            <h2 class="text-headline-md text-on-surface">Paket Pengerjaan</h2>
            <p class="mt-1 text-sm text-text-secondary">
                Pilih paket sesuai kebutuhan unit Anda.
            </p>
        </div>

        <div id="daftar-layanan" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($layanan as $item)
                <article
                    data-kategori="{{ $item['kategori'] }}"
                    data-cari="{{ strtolower($item['nama'].' '.$item['jenis_ac'].' '.$item['deskripsi'].' '.implode(' ', $item['fitur'])) }}"
                    class="kartu-layanan flex min-w-0 flex-col overflow-hidden rounded-2xl border border-border-subtle bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg"
                >
                    <div class="relative h-48 overflow-hidden bg-surface-container-high">
                        <img
                            src="{{ asset($item['gambar']) }}"
                            alt="{{ $item['nama'] }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >

                        <div class="absolute inset-0 hidden items-center justify-center bg-gradient-to-br from-blue-100 to-slate-200" aria-hidden="true">
                            <span class="text-sm font-semibold text-primary">Foto layanan AC</span>
                        </div>

                        <span class="absolute left-3 top-3 rounded-full bg-primary px-3 py-1 text-xs font-semibold text-white">
                            {{ $item['label'] }}
                        </span>
                        <span class="absolute bottom-3 right-3 rounded-lg bg-white px-2 py-1 text-xs font-semibold text-on-surface">
                            ◷ {{ $item['durasi'] }}
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col p-4">
                        <div class="flex flex-wrap items-start justify-between gap-2">
                            <span class="text-xs font-bold uppercase tracking-wide text-tertiary">
                                {{ $item['jenis_ac'] }}
                            </span>
                            <span class="rounded-full bg-emerald-50 px-2 py-1 text-xs font-semibold text-status-success">
                                ✓ {{ $item['garansi'] }}
                            </span>
                        </div>

                        <h3 class="mt-2 text-lg font-bold leading-snug text-on-surface">
                            {{ $item['nama'] }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-text-secondary">
                            {{ $item['deskripsi'] }}
                        </p>

                        <ul class="mt-4 space-y-2">
                            @foreach ($item['fitur'] as $fitur)
                                <li class="flex items-start gap-2 text-sm text-on-surface-variant">
                                    <span class="font-bold text-status-success">✓</span>
                                    <span>{{ $fitur }}</span>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-auto pt-5">
                            <div class="border-t border-border-subtle pt-4">
                                <p class="text-xs text-text-secondary">
                                    {{ $item['keterangan_harga'] }}
                                </p>

                                <div class="mt-1 flex flex-wrap items-baseline gap-1">
                                    @if ($item['perlu_survei'])
                                        <span class="text-2xl font-bold text-primary">Gratis</span>
                                    @elseif ($item['harga_mulai'] !== null)
                                        <span class="text-2xl font-bold text-primary">
                                            Rp{{ number_format($item['harga_mulai'], 0, ',', '.') }}
                                        </span>
                                        @if ($item['satuan'])
                                            <span class="text-xs text-text-secondary">/ {{ $item['satuan'] }}</span>
                                        @endif
                                    @endif
                                </div>

                                <p class="mt-1 text-xs text-text-secondary">
                                    {{ $item['catatan_harga'] }}
                                </p>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-2">
                                <button
                                    type="button"
                                    class="min-h-11 rounded-full bg-surface-container-low px-3 py-2 text-sm font-semibold text-primary hover:bg-surface-container-high"
                                >
                                    Detail SOP
                                </button>
                                <button
                                    type="button"
                                    class="min-h-11 rounded-full bg-primary px-3 py-2 text-sm font-semibold text-white hover:bg-primary-container"
                                >
                                    {{ $item['perlu_survei'] ? 'Ajukan Survei' : 'Pesan Servis' }}
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div id="layanan-kosong" class="hidden rounded-2xl border border-border-subtle bg-white px-6 py-12 text-center">
            <p class="font-semibold text-on-surface">Layanan tidak ditemukan</p>
            <p class="mt-2 text-sm text-text-secondary">Coba kata kunci atau kategori lain.</p>
        </div>
    </section>

    {{-- BANNER ESTIMASI --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-12 lg:px-8 lg:pb-16">
        <div class="rounded-3xl bg-gradient-to-br from-primary via-primary-container to-[#345c9d] p-6 text-white shadow-lg sm:p-8 lg:p-10">
            <div class="grid items-center gap-8 lg:grid-cols-[1.3fr_0.9fr]">
                <div>
                    <span class="inline-flex rounded-full bg-white/15 px-3 py-1.5 text-xs font-semibold">
                        FITUR KALKULATOR TRANSPARAN
                    </span>
                    <h2 class="mt-5 text-2xl font-bold leading-tight sm:text-3xl">
                        Punya Banyak Unit AC di Rumah atau Kantor?
                    </h2>
                    <p class="mt-3 text-sm leading-relaxed text-white/85 sm:text-base">
                        Hitung estimasi biaya cuci AC, isi freon, atau sparepart
                        dengan lebih mudah.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a
                            href="{{ url('/kalkulator') }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-full bg-white px-5 py-3 text-sm font-bold text-primary hover:bg-blue-50"
                        >
                            Buka Kalkulator Estimasi Biaya
                        </a>
                        <a
                            href="https://wa.me/6281234567890"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex min-h-11 items-center justify-center rounded-full bg-white/15 px-5 py-3 text-sm font-semibold text-white hover:bg-white/25"
                        >
                            Konsultasi WhatsApp
                        </a>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/20 bg-white/10 p-5 backdrop-blur-sm sm:p-6">
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-white/20 pb-4">
                        <h3 class="font-semibold">{{ $simulasiEstimasi['judul'] }}</h3>
                        <span class="rounded-full bg-emerald-400/20 px-3 py-1 text-xs font-bold text-emerald-100">
                            Simulasi
                        </span>
                    </div>

                    <div class="space-y-3 py-4">
                        @foreach ($simulasiEstimasi['rincian'] as $rincian)
                            <div class="flex items-start justify-between gap-4 text-sm">
                                <span class="text-white/80">{{ $rincian['nama'] }}</span>
                                <span class="shrink-0 font-semibold">
                                    @if ($rincian['biaya'] == 0)
                                        Rp0 (Gratis)
                                    @else
                                        Rp{{ number_format($rincian['biaya'], 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex items-center justify-between gap-4 border-t border-white/20 pt-4">
                        <span class="font-semibold">Total Bayar di Tempat:</span>
                        <span class="text-xl font-bold text-cyan-200 sm:text-2xl">
                            Rp{{ number_format($simulasiEstimasi['total'], 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- INTERAKSI PENCARIAN DAN FILTER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputCari = document.getElementById('cari-layanan');
            const tombolFilter = document.querySelectorAll('.filter-layanan');
            const kartuLayanan = document.querySelectorAll('.kartu-layanan');
            const pesanKosong = document.getElementById('layanan-kosong');

            if (!inputCari || !pesanKosong) return;

            let kategoriAktif = 'semua';

            function terapkanFilter() {
                const kataKunci = inputCari.value.trim().toLocaleLowerCase('id');
                let jumlahTampil = 0;

                kartuLayanan.forEach(function (kartu) {
                    const cocokKategori =
                        kategoriAktif === 'semua' ||
                        kartu.dataset.kategori === kategoriAktif;

                    const cocokPencarian =
                        (kartu.dataset.cari || '').includes(kataKunci);

                    const tampil = cocokKategori && cocokPencarian;
                    kartu.classList.toggle('hidden', !tampil);

                    if (tampil) jumlahTampil++;
                });

                pesanKosong.classList.toggle('hidden', jumlahTampil > 0);
            }

            tombolFilter.forEach(function (tombol) {
                tombol.addEventListener('click', function () {
                    kategoriAktif = tombol.dataset.kategori;

                    tombolFilter.forEach(function (item) {
                        const aktif = item === tombol;
                        item.setAttribute('aria-pressed', String(aktif));
                        item.classList.toggle('bg-primary', aktif);
                        item.classList.toggle('text-white', aktif);
                        item.classList.toggle('bg-surface-container-low', !aktif);
                        item.classList.toggle('text-on-surface-variant', !aktif);
                    });

                    terapkanFilter();
                });
            });

            inputCari.addEventListener('input', terapkanFilter);
        });
    </script>
</x-layouts.pengguna>
