<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_dashboard_page_can_be_rendered(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('King Rekap');
        $response->assertSee('Rp 1.348.000');
    }

    public function test_laporan_penjualan_page_can_be_rendered(): void
    {
        $response = $this->get(route('laporan.index'));

        $response->assertStatus(200);
        $response->assertSee('Laporan Penjualan');
        $response->assertSee('Filter Periode');
        $response->assertSee('Total Pendapatan (Periode Dipilih)');
        $response->assertSee('Es Teler Keju Spesial');
    }

    public function test_kelola_menu_page_can_be_rendered(): void
    {
        $response = $this->get(route('menu.index'));

        $response->assertStatus(200);
        $response->assertSee('Kelola Menu');
    }

    public function test_data_penjualan_page_can_be_rendered(): void
    {
        $response = $this->get(route('penjualan.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Penjualan');
    }

    public function test_prediksi_kemasan_page_can_be_rendered(): void
    {
        $response = $this->get(route('prediksi.index'));

        $response->assertStatus(200);
        $response->assertSee('Prediksi Kebutuhan Kemasan');
        $response->assertSee('Input Prediksi');
        $response->assertSee('Hasil Analisa');
        $response->assertSee('Tren Penggunaan Kemasan (Cup)');
        $response->assertSee('280 cup');
    }
}
