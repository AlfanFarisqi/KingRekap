<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PrediksiController extends Controller
{
    /**
     * Tampilkan halaman prediksi kemasan.
     */
    public function index(): View
    {
        return view('prediksi');
    }
}
