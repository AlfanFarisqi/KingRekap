<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Tampilkan halaman kelola menu dengan data menu dari database.
     */
    public function index(): View
    {
        $menus = Menu::where('is_aktif', true)->orderBy('id')->get();

        return view('menu', compact('menus'));
    }

    /**
     * Simpan menu baru ke dalam database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:150'],
            'harga' => ['required', 'integer', 'min:0'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $menu = Menu::create([
            'nama_menu' => $validated['nama_menu'],
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'] ?? 'Es Teler',
            'deskripsi' => $validated['deskripsi'] ?? null,
            'is_aktif' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil ditambahkan!',
            'data' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga' => $menu->harga,
                'harga_formatted' => 'Rp '.number_format($menu->harga, 0, ',', '.'),
            ],
        ], 201);
    }

    /**
     * Perbarui data menu yang sudah ada.
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:150'],
            'harga' => ['required', 'integer', 'min:0'],
            'kategori' => ['nullable', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string'],
        ]);

        $menu->update([
            'nama_menu' => $validated['nama_menu'],
            'harga' => $validated['harga'],
            'kategori' => $validated['kategori'] ?? $menu->kategori,
            'deskripsi' => $validated['deskripsi'] ?? $menu->deskripsi,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diperbarui!',
            'data' => [
                'id' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'harga' => $menu->harga,
                'harga_formatted' => 'Rp '.number_format($menu->harga, 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Hapus (nonaktifkan) menu dari daftar.
     */
    public function destroy(Menu $menu): JsonResponse
    {
        // Jika menu sudah memiliki riwayat transaksi, nonaktifkan is_aktif agar data penjualan tetap aman
        if ($menu->penjualan()->exists()) {
            $menu->update(['is_aktif' => false]);
        } else {
            $menu->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus!',
        ]);
    }
}
