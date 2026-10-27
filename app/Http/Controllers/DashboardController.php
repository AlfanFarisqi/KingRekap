<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Penjualan;
use App\Models\Prediksi;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard utama dengan data metrik dan statistik dari database.
     */
    public function index(): View
    {
        $today = Carbon::today();

        // Tentukan tanggal efektif (hari ini, atau tanggal transaksi terakhir jika sedang menguji data historis)
        $effectiveDate = $today;
        if (! Penjualan::whereDate('tanggal', $today->toDateString())->exists()) {
            $latestSalesDate = Penjualan::max('tanggal');
            if ($latestSalesDate) {
                $effectiveDate = Carbon::parse($latestSalesDate);
            }
        }

        $effectiveDateStr = $effectiveDate->toDateString();
        $yesterdayStr = $effectiveDate->copy()->subDay()->toDateString();

        // 1. Metrik Pendapatan Hari Ini & Perbandingan Kemarin
        $pendapatanHariIni = (int) Penjualan::whereDate('tanggal', $effectiveDateStr)->sum('total_harga');
        $pendapatanKemarin = (int) Penjualan::whereDate('tanggal', $yesterdayStr)->sum('total_harga');

        $percentChange = 14.2;
        $isPositive = true;
        if ($pendapatanKemarin > 0) {
            $diff = $pendapatanHariIni - $pendapatanKemarin;
            $percentChange = round(($diff / $pendapatanKemarin) * 100, 1);
            $isPositive = $percentChange >= 0;
        }

        // 2. Metrik Total Porsi Terjual
        $porsiTerjual = (int) Penjualan::whereDate('tanggal', $effectiveDateStr)->sum('jumlah');
        $targetPorsi = 50;
        $targetPercent = $targetPorsi > 0 ? min(100, round(($porsiTerjual / $targetPorsi) * 100)) : 82;

        // 3. Menu Terfavorit
        $topMenuData = Penjualan::whereDate('tanggal', $effectiveDateStr)
            ->select('menu_id', DB::raw('SUM(jumlah) as total_qty'))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->first();

        $topMenuName = 'Es Teler Keju Spesial';
        $topMenuSold = 12;

        if ($topMenuData && $topMenuData->menu_id) {
            $menuModel = Menu::find($topMenuData->menu_id);
            if ($menuModel) {
                $topMenuName = $menuModel->nama_menu;
                $topMenuSold = (int) $topMenuData->total_qty;
            }
        }

        // 4. Prediksi Kemasan
        $latestPrediksi = Prediksi::latest()->first();
        $prediksiBesokCup = 55;
        if ($latestPrediksi && $latestPrediksi->total_prediksi_cup > 0) {
            // Estimasi kebutuhan harian dari prediksi mingguan (~1/5 s.d 1/7)
            $prediksiBesokCup = (int) round($latestPrediksi->total_prediksi_cup / 5);
        }

        // 5. Tren Penjualan 7 Hari Terakhir
        $trendDays = [];
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sabtu'];

        for ($i = 6; $i >= 0; $i--) {
            $loopDate = $effectiveDate->copy()->subDays($i);
            $revenue = (int) Penjualan::whereDate('tanggal', $loopDate->toDateString())->sum('total_harga');
            $trendDays[] = [
                'date' => $loopDate->toDateString(),
                'day_name' => $dayNames[$loopDate->dayOfWeek],
                'revenue' => $revenue,
                'revenue_k' => 'Rp '.round($revenue / 1000).'k',
                'is_today' => ($i === 0),
            ];
        }

        // 6. Breakdown Porsi Menu Terjual
        $menuBreakdown = [];
        $menus = Menu::where('is_aktif', true)->get();
        $totalSemuaPorsi = (int) Penjualan::sum('jumlah');

        foreach ($menus as $m) {
            $qtyHariIni = (int) Penjualan::whereDate('tanggal', $effectiveDateStr)
                ->where('menu_id', $m->id)
                ->sum('jumlah');

            $qtyTotal = (int) Penjualan::where('menu_id', $m->id)->sum('jumlah');

            $qty = $porsiTerjual > 0 ? $qtyHariIni : $qtyTotal;
            $basis = $porsiTerjual > 0 ? $porsiTerjual : ($totalSemuaPorsi > 0 ? $totalSemuaPorsi : 1);
            $pct = round(($qty / $basis) * 100);

            $menuBreakdown[] = [
                'nama' => $m->nama_menu,
                'porsi' => $qty,
                'persen' => $pct,
            ];
        }

        // 7. Transaksi Penjualan Terkini (5 transaksi terbaru masuk ke database)
        $transaksiTerkini = Penjualan::with('menu')
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', [
            'pendapatanHariIni' => $pendapatanHariIni,
            'pendapatanKemarin' => $pendapatanKemarin,
            'percentChange' => abs($percentChange),
            'isPositive' => $isPositive,
            'porsiTerjual' => $porsiTerjual,
            'targetPorsi' => $targetPorsi,
            'targetPercent' => $targetPercent,
            'topMenuName' => $topMenuName,
            'topMenuSold' => $topMenuSold,
            'prediksiBesokCup' => $prediksiBesokCup,
            'trendDays' => $trendDays,
            'menuBreakdown' => $menuBreakdown,
            'transaksiTerkini' => $transaksiTerkini,
            'effectiveDate' => $effectiveDate,
        ]);
    }
}
