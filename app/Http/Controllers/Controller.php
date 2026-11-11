<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Controller
{
    /**
     * Bagi data dummy per halaman dengan bentuk yang sama seperti hasil `paginate()` Eloquent,
     * supaya view tidak perlu diubah saat data asli disambungkan.
     *
     * @template TItem
     *
     * @param  Collection<int, TItem>  $data
     * @return LengthAwarePaginator<int, TItem>
     */
    protected function paginasiDummy(Collection $data, Request $request, int $perHalaman = 6): LengthAwarePaginator
    {
        $halaman = max(1, (int) $request->query('page', 1));

        return new LengthAwarePaginator(
            $data->forPage($halaman, $perHalaman)->values(),
            $data->count(),
            $perHalaman,
            $halaman,
            ['path' => $request->url(), 'query' => $request->query()],
        );
    }
}
