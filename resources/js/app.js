import './bootstrap';

// Tombol dengan data-buka-tutup="id-elemen" menampilkan atau menyembunyikan elemen itu.
document.querySelectorAll('[data-buka-tutup]').forEach((tombol) => {
    const target = document.getElementById(tombol.dataset.bukaTutup);

    tombol.addEventListener('click', () => {
        const tersembunyi = target.classList.toggle('hidden');

        tombol.setAttribute('aria-expanded', String(!tersembunyi));
    });
});
