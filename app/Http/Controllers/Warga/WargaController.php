<?php

namespace App\Http\Controllers\Warga;

use App\Http\Controllers\Controller;
use App\Http\Requests\Warga\StoreWargaRequest;
use App\Models\Desa;
use App\Models\Warga;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WargaController extends Controller
{
    public function create(): View
    {
        return view('warga.create');
    }

    public function store(StoreWargaRequest $request): RedirectResponse
    {
        $desa = Desa::query()->orderBy('id_desa')->firstOrFail();

        Warga::create([
            ...$request->validated(),
            'desa_id' => $desa->getKey(),
            'create_by' => $request->user()->getAuthIdentifier(),
        ]);

        return to_route('warga.index')->with('success', 'Data warga berhasil disimpan.');
    }
}
