<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class MenuController extends Controller
{
    /**
     * Tampilkan halaman kelola menu.
     */
    public function index(): View
    {
        return view('menu');
    }
}
