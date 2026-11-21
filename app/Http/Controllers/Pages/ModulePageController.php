<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Support\Dummy\WargaDummy;
use Illuminate\View\View;

class ModulePageController extends Controller
{
    public function warga(): View
    {
        return view('pages.warga.index', ['warga' => WargaDummy::all()]);
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
