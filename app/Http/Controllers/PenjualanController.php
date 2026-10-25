<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PenjualanController extends Controller
{
    /**
     * Tampilkan halaman data penjualan.
     */
    public function index(): View
    {
        return view('penjualan');
    }
}
