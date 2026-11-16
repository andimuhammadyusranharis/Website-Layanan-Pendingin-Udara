{{--
    Dialog konfirmasi bersama untuk seluruh panel admin. Satu dialog dipakai ulang oleh setiap form
    yang punya atribut data-konfirmasi (lihat app.js). Judul, pesan, label tombol, dan varian diisi
    dari atribut form: data-konfirmasi-judul, data-konfirmasi, data-konfirmasi-tombol, data-konfirmasi-varian.
    Memakai elemen <dialog>, jadi fokus terkunci di dalam dialog, tombol Esc menutup, dan fokus
    kembali ke tombol pemicu saat dialog ditutup.
--}}
<dialog
    data-dialog-konfirmasi
    aria-labelledby="dialog-konfirmasi-judul"
    aria-describedby="dialog-konfirmasi-pesan"
    class="group m-auto w-[calc(100%-2rem)] max-w-[440px] rounded-2xl border border-border-subtle bg-white p-0 text-on-surface shadow-[0px_24px_60px_-12px_rgba(20,64,127,0.35)] backdrop:bg-black/40"
>
    <div class="flex flex-col gap-5 p-5 sm:p-6">
        <div class="flex items-start gap-4">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface-tint text-primary-container group-data-[varian=bahaya]:bg-lencana-merah-latar group-data-[varian=bahaya]:text-status-danger">
                <span data-dialog-ikon class="material-symbols-outlined text-[24px]" aria-hidden="true">help</span>
            </span>
            <div class="flex min-w-0 flex-col gap-1">
                <h2 id="dialog-konfirmasi-judul" data-dialog-judul class="text-[18px] leading-7 font-bold text-on-surface">Konfirmasi tindakan</h2>
                <p id="dialog-konfirmasi-pesan" data-dialog-pesan class="text-[15px] leading-6 break-words text-text-secondary"></p>
            </div>
        </div>

        <div class="flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">
            <button
                type="button"
                data-dialog-batal
                autofocus
                class="flex min-h-11 items-center justify-center rounded-xl border border-border-subtle bg-white px-5 text-[14px] leading-5 font-semibold text-text-primary transition-colors duration-200 hover:bg-surface-container-low focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container"
            >
                Batal
            </button>
            <button
                type="button"
                data-dialog-lanjut
                class="flex min-h-11 items-center justify-center rounded-xl bg-primary-container px-5 text-[14px] leading-5 font-semibold text-white transition-colors duration-200 hover:bg-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container disabled:opacity-60 group-data-[varian=bahaya]:bg-status-danger group-data-[varian=bahaya]:hover:bg-lencana-merah"
            >
                Ya, lanjutkan
            </button>
        </div>
    </div>
</dialog>
