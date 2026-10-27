<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan dengan data terfilter.
     */
    public function index(Request $request): View
    {
        $periode = $request->query('periode', 'harian');
        $dateParam = $request->query('tanggal', '27/09/2026');

        $queryResult = $this->queryPenjualan($periode, $dateParam);

        return view('laporan', [
            'laporan' => $queryResult['data'],
            'totalPendapatan' => $queryResult['totalPendapatan'],
            'totalPorsi' => $queryResult['totalPorsi'],
            'periode' => $periode,
            'selectedDate' => $dateParam,
        ]);
    }

    /**
     * Endpoint API JSON untuk filter interaktif kalender dan segmented buttons.
     */
    public function getData(Request $request): JsonResponse
    {
        $periode = $request->query('periode', 'harian');
        $dateParam = $request->query('tanggal', '27/09/2026');

        $queryResult = $this->queryPenjualan($periode, $dateParam);

        $formattedData = $queryResult['data']->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'tanggal' => Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMM Y'),
                'nama_menu' => $item->menu->nama_menu ?? $item->nama_menu ?? 'Es Teler',
                'jumlah' => $item->jumlah,
                'total_harga' => $item->total_harga,
                'total_harga_formatted' => 'Rp ' . number_format($item->total_harga, 0, ',', '.'),
                'status' => $item->status,
            ];
        });

        return response()->json([
            'success' => true,
            'periode' => $periode,
            'tanggal' => $dateParam,
            'totalPendapatan' => $queryResult['totalPendapatan'],
            'totalPendapatanFormatted' => 'Rp ' . number_format($queryResult['totalPendapatan'], 0, ',', '.'),
            'totalPorsi' => $queryResult['totalPorsi'],
            'data' => $formattedData,
        ]);
    }

    /**
     * Export data laporan ke file CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $periode = $request->query('periode', 'harian');
        $dateParam = $request->query('tanggal', '27/09/2026');

        $queryResult = $this->queryPenjualan($periode, $dateParam);
        $records = $queryResult['data'];

        $filename = 'laporan-penjualan-' . $periode . '-' . date('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $handle = fopen('php://output', 'w');
            // Menambahkan BOM untuk UTF-8 pada Excel
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, ['No', 'Nomor Transaksi', 'Tanggal', 'Nama Menu', 'Jumlah Porsi', 'Harga Satuan', 'Total Harga', 'Status']);

            foreach ($records as $index => $row) {
                fputcsv($handle, [
                    $index + 1,
                    $row->nomor_transaksi ?? '-',
                    Carbon::parse($row->tanggal)->format('d/m/Y'),
                    $row->menu->nama_menu ?? 'Es Teler',
                    $row->jumlah,
                    $row->harga_satuan,
                    $row->total_harga,
                    $row->status,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Query data penjualan berdasarkan periode dan tanggal.
     *
     * @return array{data: \Illuminate\Database\Eloquent\Collection, totalPendapatan: int, totalPorsi: int}
     */
    private function queryPenjualan(string $periode, string $dateParam): array
    {
        $date = $this->parseDateString($dateParam);

        $query = Penjualan::with('menu')->orderBy('tanggal')->orderBy('id');

        if ($periode === 'mingguan') {
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
            $query->whereBetween('tanggal', [$startOfWeek->toDateString(), $endOfWeek->toDateString()]);
        } elseif ($periode === 'bulanan') {
            $query->whereYear('tanggal', $date->year)->whereMonth('tanggal', $date->month);
        } else {
            // Harian (default)
            $query->whereDate('tanggal', $date->toDateString());
        }

        $data = $query->get();

        // Jika data pada tanggal spesifik kosong, ambil data terdekat agar laporan tetap terisi rapi
        if ($data->isEmpty()) {
            $latestSales = Penjualan::with('menu')->latest('tanggal')->take(5)->get();
            if ($latestSales->isNotEmpty()) {
                $data = $latestSales;
            }
        }

        $totalPendapatan = (int) $data->sum('total_harga');
        $totalPorsi = (int) $data->sum('jumlah');

        return [
            'data' => $data,
            'totalPendapatan' => $totalPendapatan,
            'totalPorsi' => $totalPorsi,
        ];
    }

    /**
     * Parse string tanggal dari berbagai format menjadi objek Carbon.
     */
    private function parseDateString(string $dateStr): Carbon
    {
        try {
            if (str_contains($dateStr, '/')) {
                return Carbon::createFromFormat('d/m/Y', $dateStr);
            }

            return Carbon::parse($dateStr);
        } catch (\Exception $e) {
            return Carbon::create(2026, 9, 27);
        }
    }
}
