
<x-layouts.admin title="Kelola Ulasan">
    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">

        <x-admin.kepala-halaman
            judul="Kelola Ulasan"
            deskripsi="Lihat ulasan pelanggan dan atur ulasan yang ditampilkan pada halaman publik."
        />

        <x-admin.kartu>
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <div class="mb-4">
                <h2 class="text-lg font-semibold text-text-primary">
                    Daftar Ulasan Pelanggan
                </h2>
                <p class="mt-1 text-sm text-text-secondary">
                    Total {{ $ulasan->count() }} ulasan tersimpan.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[750px] text-left">
                    <thead>
                        <tr class="border-b border-border-subtle text-[14px] font-semibold text-text-secondary">
                            <th class="px-3 py-3">Pelanggan</th>
                            <th class="px-3 py-3">Rating</th>
                            <th class="px-3 py-3">Komentar</th>
                            <th class="px-3 py-3">Tanggal</th>
                            <th class="px-3 py-3">Status</th>
                            <th class="px-3 py-3">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-border-subtle text-sm text-text-primary">
                        @forelse ($ulasan as $item)
                            <tr>
                                <td class="px-3 py-4 font-medium">
                                    {{ $item->nama_pelanggan }}
                                </td>

                                <td class="px-3 py-4 whitespace-nowrap">
                                    <span class="text-amber-500">
                                        {{ str_repeat('★', $item->rating) }}
                                    </span>
                                    <span class="text-text-secondary">
                                        ({{ $item->rating }}/5)
                                    </span>
                                </td>

                                <td class="max-w-[300px] px-3 py-4">
                                    <p class="line-clamp-3">
                                        {{ $item->komentar }}
                                    </p>
                                </td>

                                <td class="px-3 py-4 whitespace-nowrap">
                                    {{ $item->created_at?->format('d/m/Y') ?? '-' }}
                                </td>

                                <td class="px-3 py-4">
                                    @if ($item->tampil)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700">
                                            Ditampilkan
                                        </span>
                                    @else
                                        <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">
                                            Disembunyikan
                                        </span>
                                    @endif
                                </td>

                                <td class="px-3 py-4">
                                    <form
                                        action="{{ route('admin.ulasan.tampilan', $item->id_ulasan) }}"
                                        method="POST"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        @if ($item->tampil)
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-red-200 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50"
                                            >
                                                Sembunyikan
                                            </button>
                                        @else
                                            <button
                                                type="submit"
                                                class="rounded-lg border border-green-200 px-3 py-2 text-xs font-medium text-green-700 hover:bg-green-50"
                                            >
                                                Tampilkan
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-3 py-10 text-center text-text-secondary">
                                    Belum ada ulasan pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-admin.kartu>
    </div>
</x-layouts.admin>

