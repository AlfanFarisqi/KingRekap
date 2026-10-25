<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan.
     */
    public function index(): View
    {
        return view('laporan');
    }
}
