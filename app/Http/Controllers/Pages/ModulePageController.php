<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warga\FilterWargaRequest;
use App\Models\Warga;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ModulePageController extends Controller
{
    public function warga(FilterWargaRequest $request): View|Response
    {
        $query = Warga::query()->orderBy('id_warga');
        $term = $request->validated('q');

        if ($term !== null && $term !== '') {
            $escaped = addcslashes($term, '%_\\');
            $query->where(function ($query) use ($escaped, $term) {
                if (ctype_digit($term)) {
                    $query->where('nik', 'like', "%{$escaped}%");
                } else {
                    $query->where(function ($query) use ($escaped) {
                        $query->where('nama_lengkap', 'like', "%{$escaped}%")
                            ->orWhere('alamat', 'like', "%{$escaped}%");
                    });
                }
            });
        }

        $warga = $query->paginate(10)->withQueryString();
        $data = ['warga' => $warga];

        if ($request->ajax()) {
            return response()->view('pages.warga.partials.results', $data);
        }

        return view('pages.warga.index', $data);
    }

    public function arsip(): View
    {
        return view('pages.arsip.index');
    }

    public function rekap(): View
    {
        return view('pages.rekap.index');
    }

    public function pengaturan(): View
    {
        return view('pages.pengaturan.index');
    }
}
