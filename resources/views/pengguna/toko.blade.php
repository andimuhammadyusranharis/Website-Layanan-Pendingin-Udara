<!-- <x-layouts.pengguna title="Toko"> -->
    
<div class="min-h-screen bg-[#F5F7FA] text-[#1F2937] pb-16">
    
    {{-- HERO BANNER / HEADER TOKO --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <div class="bg-gradient-to-r from-[#E8F1FD] to-[#f7f9fc] rounded-3xl p-6 sm:p-10 border border-[#E3E8EF] shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="max-w-2xl">
                <h1 class="text-3xl sm:text-4xl font-extrabold text-[#14407F] tracking-tight mb-3">
                    Suku Cadang dan Unit AC
                </h1>
                <p class="text-base sm:text-lg text-[#5B6675] leading-relaxed mb-6">
                    Menyediakan spare part original bergaransi dan unit AC baru siap pasang langsung oleh teknisi berpengalaman Pak Abdul Haris di seluruh wilayah Kabupaten Pangkajene & Kepulauan.
                </p>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full text-sm font-semibold text-[#1F2937] border border-[#E3E8EF] shadow-xs">
                        <svg class="w-5 h-5 text-[#1E9E6A]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        100% Produk Original Bersegel
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full text-sm font-semibold text-[#1F2937] border border-[#E3E8EF] shadow-xs">
                        <svg class="w-5 h-5 text-[#1E63D6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Garansi Tukar Baru 7 Hari
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full text-sm font-semibold text-[#1F2937] border border-[#E3E8EF] shadow-xs">
                        <svg class="w-5 h-5 text-[#20B7C9]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Bisa Sekaligus Jasa Pasang
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- SEARCH & FILTER BAR --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-[#E3E8EF] shadow-sm flex flex-col gap-4">
            
            {{-- Search & Sort Row --}}
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="relative w-full md:w-2/3">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-[#737785]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </span>
                    <input type="text" id="input-pencarian" placeholder="Cari suku cadang, tipe freon, kapasitas PK, atau merek unit AC..." 
                           class="w-full pl-12 pr-4 h-[50px] bg-[#F5F7FA] border border-[#E3E8EF] rounded-xl text-base text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1E63D6] focus:bg-white transition">
                </div>
                <div class="flex items-center justify-between w-full md:w-auto gap-3">
                    <span class="text-sm font-semibold text-[#5B6675] whitespace-nowrap">Urutkan:</span>
                    <select id="select-urutkan" class="h-[50px] px-4 bg-[#F5F7FA] border border-[#E3E8EF] rounded-xl text-base font-medium text-[#1F2937] focus:outline-none focus:ring-2 focus:ring-[#1E63D6]">
                        <option value="rekomendasi">Terpopuler & Rekomendasi</option>
                        <option value="termurah">Harga Termurah</option>
                        <option value="termahal">Harga Tertinggi</option>
                    </select>
                </div>
            </div>

            {{-- Category Filter Pills (Dinamis dari $kategoriProduk) --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <button type="button" class="px-5 py-2.5 rounded-full bg-[#1E63D6] text-white font-semibold text-sm whitespace-nowrap shadow-xs">Semua Produk</button>
                @foreach($kategoriProduk as $kat)
                    <button type="button" class="px-5 py-2.5 rounded-full bg-[#F5F7FA] hover:bg-[#E8F1FD] text-[#5B6675] hover:text-[#1E63D6] font-semibold text-sm whitespace-nowrap border border-[#E3E8EF] transition">
                        {{ $kat['nama'] }}
                    </button>
                @endforeach
            </div>

            {{-- Brand Filter Pills (Dinamis dari $merekPopuler) --}}
            <div class="flex items-center gap-3 pt-2 border-t border-[#E3E8EF]">
                <span class="text-xs font-bold text-[#737785] uppercase tracking-wider whitespace-nowrap">Brand Populer:</span>
                <div class="flex items-center gap-2 overflow-x-auto">
                    @foreach($merekPopuler as $brand)
                        <span class="px-3 py-1 bg-[#F5F7FA] border border-[#E3E8EF] rounded-lg text-xs font-semibold text-[#5B6675] hover:border-[#1E63D6] cursor-pointer transition">{{ $brand }}</span>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- SECTION: KOLEKSI UNIT AC --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-[#14407F]">Koleksi Unit AC</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($unitAc as $ac)
            <div class="bg-white rounded-2xl border border-[#E3E8EF] p-4 flex flex-col justify-between shadow-xs hover:shadow-md transition card-produk" data-nama="{{ strtolower($ac['nama'] . ' ' . $ac['merek'] . ' ' . $ac['model']) }}" data-harga="{{ $ac['harga'] }}">
                <div>
                    <div class="relative rounded-xl overflow-hidden bg-[#F5F7FA] h-48 mb-4 flex items-center justify-center">
                        <img src="{{ asset($ac['gambar']) }}" alt="{{ $ac['nama'] }}" class="object-cover h-full w-full" onerror="this.src='https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=600&q=80'">
                        
                        {{-- Badge Stok Sesuai Aturan --}}
                        @if($ac['stok'] == 0)
                            <span class="absolute top-3 left-3 bg-[#D64545] text-white text-xs font-semibold px-2.5 py-1 rounded-md">Stok Habis</span>
                        @elseif($ac['stok'] <= 3)
                            <span class="absolute top-3 left-3 bg-[#F2A93B] text-white text-xs font-semibold px-2.5 py-1 rounded-md">Sisa {{ $ac['stok'] }}</span>
                        @else
                            <span class="absolute top-3 left-3 bg-[#0F1F44] text-white text-xs font-semibold px-2.5 py-1 rounded-md">Ready Stock ({{ $ac['stok'] }})</span>
                        @endif
                    </div>
                    <div class="text-xs font-semibold text-[#1E63D6] uppercase mb-1">{{ strtoupper($ac['merek']) }} • {{ $ac['model'] }}</div>
                    <h3 class="text-lg font-bold text-[#1F2937] mb-2">{{ $ac['nama'] }}</h3>
                    <div class="space-y-1 mb-4">
                        @foreach($ac['spesifikasi'] as $spec)
                            <p class="text-xs bg-[#F5F7FA] text-[#5B6675] px-2 py-1 rounded inline-block font-medium">{{ $spec }}</p>
                        @endforeach
                        <p class="text-xs text-[#1E9E6A] font-semibold flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $ac['garansi'] }}
                        </p>
                    </div>
                </div>
                <div>
                    <div class="text-xs text-[#5B6675] mb-1">Harga Unit Resmi:</div>
                    <div class="text-xl font-extrabold text-[#1E63D6] mb-4">Rp{{ number_format($ac['harga'], 0, ',', '.') }}</div>
                    <div class="flex items-center gap-2">
                        @if($ac['stok'] == 0)
                            <button type="button" disabled class="flex-1 bg-[#E3E8EF] text-[#737785] font-semibold py-2.5 px-4 rounded-full text-center text-sm cursor-not-allowed">
                                Stok Habis
                            </button>
                        @else
                            <button type="button" class="flex-1 bg-[#1E63D6] hover:bg-[#14407F] text-white font-semibold py-2.5 px-4 rounded-full text-center text-sm transition shadow-xs flex items-center justify-center gap-2">
                                Beli Unit Sekarang
                            </button>
                            <button type="button" class="p-2.5 bg-[#E8F1FD] hover:bg-[#d9e2ff] text-[#1E63D6] rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- SECTION: SUKU CADANG & KOMPONEN AC --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-[#14407F]">Suku Cadang & Komponen AC</h2>
        </div>

        <div id="container-suku-cadang" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($produk as $p)
            <div class="bg-white rounded-2xl border border-[#E3E8EF] p-5 flex flex-col justify-between shadow-xs hover:shadow-md transition card-produk" data-nama="{{ strtolower($p['nama'] . ' ' . $p['kategori'] . ' ' . $p['deskripsi']) }}" data-harga="{{ $p['harga'] }}">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        {{-- Badge Stok Sesuai Aturan --}}
                        @if($p['stok'] == 0)
                            <span class="bg-[#FEE2E2] text-[#B91C1C] text-xs font-bold px-2.5 py-1 rounded-full">Stok Habis</span>
                        @elseif($p['stok'] <= 3)
                            <span class="bg-[#FEF3C7] text-[#B45309] text-xs font-bold px-2.5 py-1 rounded-full">Sisa {{ $p['stok'] }}</span>
                        @else
                            <span class="bg-[#DCFCE7] text-[#15803D] text-xs font-bold px-2.5 py-1 rounded-full">Ready Stock ({{ $p['stok'] }})</span>
                        @endif
                        <span class="text-xs font-semibold text-[#5B6675] uppercase">{{ $p['kategori'] }}</span>
                    </div>
                    
                    {{-- Gambar Produk Suku Cadang --}}
                    <div class="relative rounded-xl overflow-hidden bg-[#F5F7FA] h-40 mb-4 flex items-center justify-center">
                        <img src="{{ asset($p['gambar']) }}" alt="{{ $p['nama'] }}" class="object-cover h-full w-full" onerror="this.src='https://images.unsplash.com/photo-1581092160607-ee22621dd758?auto=format&fit=crop&w=600&q=80'">
                    </div>

                    <h3 class="text-lg font-bold text-[#1F2937] mb-2">{{ $p['nama'] }}</h3>
                    <p class="text-sm text-[#5B6675] mb-4 leading-relaxed">{{ $p['deskripsi'] }}</p>
                </div>
                <div>
                    <div class="flex items-baseline gap-1 mb-4">
                        <span class="text-xl font-extrabold text-[#1E63D6]">Rp{{ number_format($p['harga'], 0, ',', '.') }}</span>
                        @if(!empty($p['satuan']))
                            <span class="text-xs text-[#5B6675] font-medium">/ {{ $p['satuan'] }}</span>
                        @endif
                    </div>
                    
                    {{-- Quantity Selector & Buttons --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center border border-[#E3E8EF] rounded-xl overflow-hidden bg-[#F5F7FA]">
                            <button type="button" class="px-3 py-2 text-[#5B6675] hover:bg-[#E8F1FD] font-bold transition btn-kurang" data-max="{{ $p['stok'] }}">-</button>
                            <span class="px-3 text-sm font-semibold text-[#1F2937] nilai-qty">1</span>
                            <button type="button" class="px-3 py-2 text-[#5B6675] hover:bg-[#E8F1FD] font-bold transition btn-tambah" data-max="{{ $p['stok'] }}">+</button>
                        </div>
                        
                        @if($p['stok'] == 0)
                            <button type="button" disabled class="flex-1 bg-[#E3E8EF] text-[#737785] font-semibold py-2.5 px-3 rounded-xl text-xs sm:text-sm text-center cursor-not-allowed">
                                Habis
                            </button>
                        @else
                            <button type="button" class="flex-1 bg-[#E8F1FD] hover:bg-[#d9e2ff] text-[#1E63D6] font-semibold py-2.5 px-3 rounded-xl text-xs sm:text-sm text-center transition flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                Keranjang
                            </button>
                            <button type="button" class="bg-[#1E63D6] hover:bg-[#14407F] text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm text-center transition">
                                Beli
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pesan Kosong Jika Tidak Ada Produk Ditemukan (Pencarian) --}}
        <div id="pesan-kosong" class="hidden text-center py-16 bg-white rounded-2xl border border-[#E3E8EF] mt-6">
            <svg class="w-16 h-16 mx-auto text-[#737785] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <h3 class="text-lg font-bold text-[#1F2937]">Produk tidak ditemukan</h3>
            <p class="text-sm text-[#5B6675] mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
        </div>
    </section>

    {{-- SECTION: 3 CARA MUDAH MENERIMA PESANAN --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="bg-white rounded-3xl border border-[#E3E8EF] p-8 sm:p-12 shadow-sm">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-bold uppercase tracking-wider text-[#1E63D6] bg-[#E8F1FD] px-3 py-1 rounded-full">PENGANTARAN FLEKSIBEL & TANPA KHAWATIR</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-[#14407F] mt-3">3 Cara Mudah Menerima Pesanan Anda di Pangkep</h2>
                <p class="text-sm sm:text-base text-[#5B6675] mt-2">Kami mengerti kenyamanan warga Pangkep. Pilih cara penerimaan yang paling cocok dengan waktu dan kebutuhan Anda.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-[#F5F7FA] rounded-2xl p-6 border border-[#E3E8EF] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#E8F1FD] text-[#1E63D6] flex items-center justify-center font-bold text-lg mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-[#1E9E6A] uppercase">Gratis Ongkir</span>
                        <h3 class="text-lg font-bold text-[#1F2937] mt-1 mb-2">1. Ambil di Workshop</h3>
                        <p class="text-sm text-[#5B6675] leading-relaxed">Kunjungi workshop kami di Jl. Poros Makassar - Pangkep Km. 48. Anda bisa mengecek langsung kondisi fisik unit/spare part bersama Pak Abdul Haris.</p>
                    </div>
                    <p class="text-xs font-semibold text-[#14407F] mt-6 pt-4 border-t border-[#E3E8EF]">Buka Setiap Hari 08.00 - 17.30</p>
                </div>

                <div class="bg-[#F5F7FA] rounded-2xl p-6 border border-[#E3E8EF] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#E8F1FD] text-[#1E63D6] flex items-center justify-center font-bold text-lg mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                        </div>
                        <span class="text-xs font-bold text-[#1E63D6] uppercase">Cepat Sampai Hari Ini</span>
                        <h3 class="text-lg font-bold text-[#1F2937] mt-1 mb-2">2. Diantar Driver</h3>
                        <p class="text-sm text-[#5B6675] leading-relaxed">Driver terpercaya CV. Tiga Putra mengantarkan langsung ke rumah atau kantor Anda. Ongkir transparan hanya Rp10.000 untuk 3 km pertama.</p>
                    </div>
                    <p class="text-xs font-semibold text-[#14407F] mt-6 pt-4 border-t border-[#E3E8EF]">Bisa Bayar di Tempat (COD)</p>
                </div>

                <div class="bg-[#F5F7FA] rounded-2xl p-6 border border-[#E3E8EF] flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-[#E8F1FD] text-[#1E63D6] flex items-center justify-center font-bold text-lg mb-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <span class="text-xs font-bold text-[#20B7C9] uppercase">Paling Praktis</span>
                        <h3 class="text-lg font-bold text-[#1F2937] mt-1 mb-2">3. Dibawa & Dipasang Teknisi</h3>
                        <p class="text-sm text-[#5B6675] leading-relaxed">Jika Anda memesan jadwal servis atau paket pasang, part & unit baru akan langsung dibawa oleh teknisi ke lokasi tanpa biaya ongkir terpisah.</p>
                    </div>
                    <p class="text-xs font-semibold text-[#14407F] mt-6 pt-4 border-t border-[#E3E8EF]">Sekaligus Pengecekan Sistem</p>
                </div>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputPencarian = document.getElementById('input-pencarian');
    const selectUrutkan = document.getElementById('select-urutkan');
    const cards = document.querySelectorAll('.card-produk');
    const pesanKosong = document.getElementById('pesan-kosong');

    // 1. Logika Filter Pencarian
    if (inputPencarian) {
        inputPencarian.addEventListener('input', function (e) {
            const keyword = e.target.value.toLowerCase().trim();
            let adaYangTampil = false;

            cards.forEach(card => {
                const namaData = card.getAttribute('data-nama') || '';
                if (namaData.includes(keyword)) {
                    card.style.display = 'flex';
                    adaYangTampil = true;
                } else {
                    card.style.display = 'none';
                }
            });

            if (pesanKosong) {
                if (!adaYangTampil) {
                    pesanKosong.classList.remove('hidden');
                } else {
                    pesanKosong.classList.add('hidden');
                }
            }
        });
    }

    // 2. Logika Pengurutan Harga
    if (selectUrutkan) {
        selectUrutkan.addEventListener('change', function (e) {
            const val = e.target.value;
            
            // Urutkan Unit AC
            const gridUnitAc = document.querySelector('.grid-cols-1.sm\\:grid-cols-2.lg\\:grid-cols-4');
            if (gridUnitAc) {
                urutkanGrid(gridUnitAc, val);
            }

            // Urutkan Suku Cadang
            const gridSukuCadang = document.getElementById('container-suku-cadang');
            if (gridSukuCadang) {
                urutkanGrid(gridSukuCadang, val);
            }
        });
    }

    function jenisUrut(container, mode) {
        const itemCards = Array.from(container.querySelectorAll('.card-produk'));
        itemCards.sort((a, b) => {
            const hargaA = parseInt(a.getAttribute('data-harga')) || 0;
            const hargaB = parseInt(b.getAttribute('data-harga')) || 0;

            if (mode === 'termurah') {
                return hargaA - hargaB;
            } else if (mode === 'termahal') {
                return hargaB - hargaA;
            }
            return 0; // Default / rekomendasi
        });

        itemCards.forEach(card => container.appendChild(card));
    }

    function urutkanGrid(container, mode) {
        if (mode === 'rekomendasi') return; // biarkan urutan awal
        jenisUrut(container, mode);
    }

    // 3. Logika Tombol Kuantitas (- dan +)
    document.querySelectorAll('.btn-kurang').forEach(btn => {
        btn.addEventListener('click', function () {
            const parent = this.closest('.flex');
            const spanQty = parent.querySelector('.nilai-qty');
            let val = parseInt(spanQty.textContent) || 1;
            if (val > 1) {
                val--;
                spanQty.textContent = val;
            }
        });
    });

    document.querySelectorAll('.btn-tambah').forEach(btn => {
        btn.addEventListener('click', function () {
            const parent = this.closest('.flex');
            const spanQty = parent.querySelector('.nilai-qty');
            const maxStok = parseInt(this.getAttribute('data-max')) || 99;
            let val = parseInt(spanQty.textContent) || 1;
            if (val < maxStok) {
                val++;
                spanQty.textContent = val;
            }
        });
    });
});
</script>
@endpush
<!-- </x-layouts.pengguna> -->
