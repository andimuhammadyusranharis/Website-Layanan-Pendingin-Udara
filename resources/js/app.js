import './bootstrap';

// Tombol dengan data-buka-tutup="id-elemen" menampilkan atau menyembunyikan elemen itu.
document.querySelectorAll('[data-buka-tutup]').forEach((tombol) => {
    const target = document.getElementById(tombol.dataset.bukaTutup);

    tombol.addEventListener('click', () => {
        const tersembunyi = target.classList.toggle('hidden');

        tombol.setAttribute('aria-expanded', String(!tersembunyi));
    });
});

/*
 * Penyaring daftar di sisi klien. Struktur yang dikenali di dalam [data-saring]:
 * - [data-saring-cari]: input teks, dicocokkan dengan data-teks pada item.
 * - [data-saring-pilihan="kunci"][data-nilai]: tombol pilihan, dicocokkan dengan data-{kunci} pada item. Nilai kosong berarti semua.
 * - [data-saring-urut]: select dengan nilai "atribut:asc" atau "atribut:desc". Nilai kosong mengembalikan urutan awal.
 * - [data-saring-daftar] berisi [data-saring-item], [data-saring-kosong], dan [data-saring-status][data-satuan].
 */
document.querySelectorAll('[data-saring]').forEach((akar) => {
    const cari = akar.querySelector('[data-saring-cari]');
    const urut = akar.querySelector('[data-saring-urut]');
    const pilihan = [...akar.querySelectorAll('[data-saring-pilihan]')];
    const daftar = [...akar.querySelectorAll('[data-saring-daftar]')];
    const kosong = akar.querySelector('[data-saring-kosong]');
    const status = akar.querySelector('[data-saring-status]');
    const terpilih = {};

    daftar.forEach((wadah) => {
        [...wadah.querySelectorAll('[data-saring-item]')].forEach((item, indeks) => {
            item.dataset.urutanAwal = indeks;
        });
    });

    const terapkan = () => {
        const kataKunci = (cari?.value ?? '').trim().toLowerCase();
        let jumlahTampil = 0;

        daftar.forEach((wadah) => {
            const item = [...wadah.querySelectorAll('[data-saring-item]')];
            let tampilDiWadah = 0;

            item.forEach((elemen) => {
                const cocokTeks = kataKunci === '' || (elemen.dataset.teks ?? '').includes(kataKunci);
                const cocokPilihan = Object.entries(terpilih).every(
                    ([kunci, nilai]) => nilai === '' || (elemen.dataset[kunci] ?? '').split(' ').includes(nilai),
                );
                const tampil = cocokTeks && cocokPilihan;

                elemen.hidden = !tampil;
                tampilDiWadah += tampil ? 1 : 0;
            });

            const [atribut, arah] = (urut?.value || 'urutanAwal:asc').split(':');
            item.sort((a, b) => {
                const selisih = Number(a.dataset[atribut] ?? 0) - Number(b.dataset[atribut] ?? 0);

                return arah === 'desc' ? -selisih : selisih;
            }).forEach((elemen) => elemen.parentElement.appendChild(elemen));

            wadah.closest('[data-saring-bagian]')?.toggleAttribute('hidden', tampilDiWadah === 0);
            jumlahTampil += tampilDiWadah;
        });

        if (kosong) {
            kosong.hidden = jumlahTampil > 0;
        }

        if (status) {
            status.textContent = `${jumlahTampil} ${status.dataset.satuan} ditampilkan`;
        }
    };

    pilihan.forEach((tombol) => {
        const kunci = tombol.dataset.saringPilihan;
        terpilih[kunci] ??= '';

        tombol.addEventListener('click', () => {
            terpilih[kunci] = tombol.dataset.nilai;
            pilihan
                .filter((lain) => lain.dataset.saringPilihan === kunci)
                .forEach((lain) => lain.setAttribute('aria-pressed', String(lain === tombol)));
            terapkan();
        });
    });

    cari?.addEventListener('input', terapkan);
    urut?.addEventListener('change', terapkan);
    akar.querySelector('[data-saring-reset]')?.addEventListener('click', () => {
        if (cari) {
            cari.value = '';
        }
        if (urut) {
            urut.value = '';
        }
        pilihan.forEach((tombol) => {
            const semua = tombol.dataset.nilai === '';
            tombol.setAttribute('aria-pressed', String(semua));
            if (semua) {
                terpilih[tombol.dataset.saringPilihan] = '';
            }
        });
        terapkan();
        cari?.focus();
    });
});

// Dropdown penyaring di halaman admin langsung mengirim form-nya saat pilihan diubah.
document.querySelectorAll('select[data-kirim-otomatis]').forEach((pilihan) => {
    pilihan.addEventListener('change', () => pilihan.form?.requestSubmit());
});

// Form dengan data-konfirmasi meminta persetujuan dulu sebelum dikirim, untuk aksi yang tidak bisa dibatalkan.
document.querySelectorAll('form[data-konfirmasi]').forEach((form) => {
    form.addEventListener('submit', (event) => {
        if (!window.confirm(form.dataset.konfirmasi)) {
            event.preventDefault();
        }
    });
});
