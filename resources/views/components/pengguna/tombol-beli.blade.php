@props(['barang', 'nama'])

{{-- Selama keranjang belum dibuat, pembelian diarahkan ke WhatsApp. --}}
@if ($barang['stok'] <= 0)
    <button type="button" disabled class="flex min-h-11 w-full cursor-not-allowed items-center justify-center rounded-full bg-surface-container px-4 text-label-lg text-text-secondary">Stok habis</button>
@elseif (Route::has('keranjang.store'))
    <form method="POST" action="{{ route('keranjang.store') }}" class="flex gap-3">
        @csrf
        <input type="hidden" name="produk_id" value="{{ $barang['id'] }}" />
        <label class="sr-only" for="jumlah-{{ $barang['id'] }}">Jumlah {{ $nama }}</label>
        <input
            id="jumlah-{{ $barang['id'] }}"
            type="number"
            name="jumlah"
            value="1"
            min="1"
            max="{{ $barang['stok'] }}"
            class="h-11 w-16 rounded-xl border border-border-subtle bg-surface-container-low text-center text-body-md text-text-primary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
        />
        <button type="submit" class="flex min-h-11 flex-1 items-center justify-center gap-2 rounded-full bg-primary-container px-4 text-label-lg text-white transition-colors duration-200 hover:bg-primary">
            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">add_shopping_cart</span>
            Masukkan keranjang
        </button>
    </form>
@else
    <a
        href="{{ config('usaha.whatsapp.tautan').'?text='.rawurlencode("Halo, saya ingin membeli {$nama}. Apakah stoknya masih ada?") }}"
        target="_blank"
        rel="noopener"
        class="flex min-h-11 w-full items-center justify-center gap-2 rounded-full bg-primary-container px-4 text-label-lg text-white transition-colors duration-200 hover:bg-primary"
    >
        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chat</span>
        Pesan lewat WhatsApp
    </a>
@endif
