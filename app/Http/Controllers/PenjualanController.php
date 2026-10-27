<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Tampilkan halaman data penjualan dengan form dan riwayat transaksi hari ini.
     */
    public function index(): View
    {
        $menus = Menu::where('is_aktif', true)->orderBy('id')->get();

        $todayStr = Carbon::today()->toDateString();

        // Cari transaksi hari ini, atau gunakan tanggal transaksi terakhir jika ada data historis
        $queryDate = $todayStr;
        if (! Penjualan::whereDate('tanggal', $todayStr)->exists()) {
            $latestDate = Penjualan::max('tanggal');
            if ($latestDate) {
                $queryDate = Carbon::parse($latestDate)->toDateString();
            }
        }

        $penjualanHariIni = Penjualan::with('menu')
            ->whereDate('tanggal', $queryDate)
            ->latest('id')
            ->get();

        $totalPorsi = $penjualanHariIni->sum('jumlah');
        $totalPendapatan = $penjualanHariIni->sum('total_harga');

        return view('penjualan', compact('menus', 'penjualanHariIni', 'totalPorsi', 'totalPendapatan', 'queryDate'));
    }

    /**
     * Simpan transaksi penjualan baru ke dalam database.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal' => ['nullable', 'string'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $jumlah = (int) $validated['jumlah'];
        $hargaSatuan = (int) $menu->harga;
        $totalHarga = $hargaSatuan * $jumlah;

        // Parse tanggal transaksi (format Y-m-d atau d/m/Y)
        $tanggal = Carbon::today();
        if (! empty($validated['tanggal'])) {
            try {
                if (str_contains($validated['tanggal'], '/')) {
                    $tanggal = Carbon::createFromFormat('d/m/Y', $validated['tanggal']);
                } else {
                    $tanggal = Carbon::parse($validated['tanggal']);
                }
            } catch (\Exception $e) {
                $tanggal = Carbon::today();
            }
        }

        // Generate nomor transaksi unik TRX-YYYYMMDD-XXXX
        $dateCode = $tanggal->format('Ymd');
        $todayCount = Penjualan::whereDate('tanggal', $tanggal->toDateString())->count() + 1;
        $nomorTransaksi = 'TRX-' . $dateCode . '-' . str_pad((string) $todayCount, 4, '0', STR_PAD_LEFT);

        $penjualan = Penjualan::create([
            'nomor_transaksi' => $nomorTransaksi,
            'tanggal' => $tanggal->toDateString(),
            'menu_id' => $menu->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'status' => 'sukses',
            'user_id' => Auth::id() ?? 1,
        ]);

        $penjualan->load('menu');

        return response()->json([
            'success' => true,
            'message' => 'Transaksi ' . $jumlah . ' Porsi ' . $menu->nama_menu . ' (Rp ' . number_format($totalHarga, 0, ',', '.') . ') berhasil dicatat!',
            'data' => [
                'id' => $penjualan->id,
                'nomor_transaksi' => $penjualan->nomor_transaksi,
                'tanggal_formatted' => $penjualan->tanggal->locale('id')->isoFormat('D MMM Y'),
                'nama_menu' => $menu->nama_menu,
                'jumlah' => $penjualan->jumlah,
                'total_harga' => $penjualan->total_harga,
                'total_harga_formatted' => 'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
                'status' => $penjualan->status,
            ],
        ], 201);
    }

    /**
     * Perbarui data transaksi penjualan.
     */
    public function update(Request $request, Penjualan $penjualan): JsonResponse
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'exists:menus,id'],
            'jumlah'  => ['required', 'integer', 'min:1'],
            'tanggal' => ['nullable', 'string'],
        ]);

        $menu = Menu::findOrFail($validated['menu_id']);
        $jumlah = (int) $validated['jumlah'];
        $hargaSatuan = (int) $menu->harga;
        $totalHarga = $hargaSatuan * $jumlah;

        $tanggal = $penjualan->tanggal ?? Carbon::today();
        if (! empty($validated['tanggal'])) {
            try {
                $tanggal = str_contains($validated['tanggal'], '/')
                    ? Carbon::createFromFormat('d/m/Y', $validated['tanggal'])
                    : Carbon::parse($validated['tanggal']);
            } catch (\Exception $e) {
                $tanggal = Carbon::today();
            }
        }

        $penjualan->update([
            'menu_id'      => $menu->id,
            'jumlah'       => $jumlah,
            'harga_satuan' => $hargaSatuan,
            'total_harga'  => $totalHarga,
            'tanggal'      => $tanggal->toDateString(),
        ]);

        $penjualan->load('menu');

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil diperbarui!',
            'data' => [
                'id'                   => $penjualan->id,
                'tanggal_formatted'    => $penjualan->tanggal->locale('id')->isoFormat('D MMM Y'),
                'nama_menu'            => $menu->nama_menu,
                'jumlah'               => $penjualan->jumlah,
                'total_harga'          => $penjualan->total_harga,
                'total_harga_formatted' => 'Rp ' . number_format($penjualan->total_harga, 0, ',', '.'),
            ],
        ]);
    }

    /**
     * Hapus transaksi penjualan.
     */
    public function destroy(Penjualan $penjualan): JsonResponse
    {
        $penjualan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dihapus!',
        ]);
    }
}
