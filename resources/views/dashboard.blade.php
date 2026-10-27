@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 sm:space-y-8">
    
    <!-- Top Welcome Banner & Date Badge -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-2 border-b border-gray-200/80">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3b2e] tracking-tight">
                Dashboard
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                Selamat Datang di <span class="font-bold text-[#0c3b2e]">King Rekap</span> — Sistem Pengelolaan Penjualan &amp; Prediksi Kemasan King Es Teler.
            </p>
        </div>
        
        <!-- Right Action -->
        <div class="flex items-center gap-3 shrink-0">
            <a href="{{ route('penjualan.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-[#0e3c2f] hover:bg-[#0a2e22] text-white text-sm font-bold rounded-lg shadow-xs hover:shadow transition-all duration-150 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Catat Penjualan</span>
            </a>
        </div>
    </div>

    <!-- 4 Key Stat Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        
        <!-- Card 1: Total Pendapatan Hari Ini -->
        <div class="bg-white rounded-xl p-5 border border-gray-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pendapatan Hari Ini</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-[#0c3b2e] tracking-tight">
                    Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="inline-flex items-center gap-1 font-bold {{ $isPositive ? 'text-emerald-700 bg-emerald-50 border-emerald-200/60' : 'text-red-600 bg-red-50 border-red-200/60' }} px-2 py-0.5 rounded-md border text-[11px]">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $isPositive ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6' }}"/>
                    </svg>
                    <span>{{ $isPositive ? '+' : '-' }}{{ $percentChange }}%</span>
                </span>
                <span class="text-gray-400 text-[11px] font-medium">dari kemarin</span>
            </div>
        </div>

        <!-- Card 2: Total Porsi Terjual -->
        <div class="bg-white rounded-xl p-5 border border-gray-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Porsi Terjual</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ $porsiTerjual }} Porsi
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between gap-2 text-xs">
                <div class="flex-1 bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $targetPercent }}%"></div>
                </div>
                <span class="text-[11px] font-bold text-gray-700 shrink-0">{{ $targetPercent }}% <span class="text-gray-400 font-normal">Target ({{ $targetPorsi }} Porsi)</span></span>
            </div>
        </div>

        <!-- Card 3: Menu Terfavorit -->
        <div class="bg-white rounded-xl p-5 border border-gray-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Menu Terfavorit</span>
                    <div class="w-9 h-9 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-xl sm:text-[22px] font-extrabold text-gray-900 truncate tracking-tight" title="{{ $topMenuName }}">
                    {{ $topMenuName }}
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-500 font-medium text-[11px]">{{ $topMenuSold }} porsi laku</span>
                <span class="text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md text-[11px] border border-amber-200/60">Top #1</span>
            </div>
        </div>

        <!-- Card 4: Prediksi Kemasan Besok -->
        <div class="bg-white rounded-xl p-5 border border-gray-200/80 shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Prediksi Kemasan</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl font-extrabold text-gray-900 tracking-tight">
                    {{ $prediksiBesokCup }} Cup
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                <span class="text-gray-500 font-medium text-[11px]">Estimasi besok</span>
                <span class="text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-md text-[11px] border border-emerald-200/60">Stok Aman</span>
            </div>
        </div>

    </div>

    <!-- Analytics Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Sales Chart (2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-lg font-bold text-[#0c3b2e]">Tren Penjualan 7 Hari Terakhir</h2>
                    <p class="text-xs text-gray-500">Performa omset harian King Es Teler</p>
                </div>
                <div class="flex items-center gap-1.5 p-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-700">
                    <button type="button" class="px-3 py-1.5 rounded-md bg-white shadow-2xs text-[#0c3b2e]">Harian</button>
                    <button type="button" class="px-3 py-1.5 rounded-md text-gray-500 hover:text-gray-800">Mingguan</button>
                </div>
            </div>

            <!-- SVG Visual Trend Area Chart -->
            <div class="relative w-full overflow-hidden pt-2">
                <div class="h-64 sm:h-72 w-full flex flex-col justify-between">
                    <!-- SVG Chart Graphic -->
                    <svg viewBox="0 0 700 220" class="w-full h-full overflow-visible" preserveAspectRatio="none">
                        <defs>
                            <linearGradient id="kingGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0c3b2e" stop-opacity="0.25"/>
                                <stop offset="100%" stop-color="#0c3b2e" stop-opacity="0.0"/>
                            </linearGradient>
                        </defs>
                        
                        <!-- Grid lines -->
                        <line x1="0" y1="20" x2="700" y2="20" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="0" y1="70" x2="700" y2="70" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="0" y1="120" x2="700" y2="120" stroke="#f1f5f9" stroke-width="1" />
                        <line x1="0" y1="170" x2="700" y2="170" stroke="#f1f5f9" stroke-width="1" />
                        
                        <!-- Gradient Fill Area under curve -->
                        <path d="M 30,150 
                                 C 100,140 130,110 200,105 
                                 C 260,100 300,125 380,85 
                                 C 440,55 520,70 580,45 
                                 C 620,30 650,35 670,25 
                                 L 670,190 L 30,190 Z" 
                              fill="url(#kingGradient)"/>

                        <!-- Primary Green Trend Line -->
                        <path d="M 30,150 
                                 C 100,140 130,110 200,105 
                                 C 260,100 300,125 380,85 
                                 C 440,55 520,70 580,45 
                                 C 620,30 650,35 670,25" 
                              fill="none" 
                              stroke="#0c3b2e" 
                              stroke-width="3.5" 
                              stroke-linecap="round" 
                              stroke-linejoin="round"/>

                        <!-- Data Points -->
                        <circle cx="30" cy="150" r="5" fill="#ffffff" stroke="#0c3b2e" stroke-width="3"/>
                        <circle cx="200" cy="105" r="5" fill="#ffffff" stroke="#0c3b2e" stroke-width="3"/>
                        <circle cx="380" cy="85" r="5" fill="#ffffff" stroke="#0c3b2e" stroke-width="3"/>
                        <circle cx="580" cy="45" r="5" fill="#ffffff" stroke="#0c3b2e" stroke-width="3"/>
                        <circle cx="670" cy="25" r="6" fill="#10b981" stroke="#ffffff" stroke-width="3" class="animate-pulse"/>
                    </svg>
                </div>

                <!-- Days X-Axis labels -->
                <div class="flex justify-between items-center text-xs font-semibold text-gray-500 pt-3 border-t border-gray-100">
                    @foreach($trendDays as $td)
                        <span class="text-center {{ $td['is_today'] ? 'font-bold text-[#0c3b2e]' : '' }}">
                            {{ $td['day_name'] }}<br>
                            <span class="text-[11px] {{ $td['is_today'] ? 'font-bold text-emerald-700' : 'font-normal text-gray-400' }}">{{ $td['revenue_k'] }}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Right: Menu Distribution / Breakdown -->
        <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)] flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-[#0c3b2e]">Porsi Menu Terjual</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Persentase varian King Es Teler yang terjual</p>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200 shrink-0">
                        Total {{ $porsiTerjual }} Porsi
                    </span>
                </div>

                <!-- List with progress bars -->
                <div class="space-y-4 pt-2">
                    @forelse($menuBreakdown as $idx => $mb)
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1.5">
                                <span class="text-gray-800">{{ $mb['nama'] }}</span>
                                <span class="text-[#0c3b2e]">{{ $mb['porsi'] }} Porsi <span class="text-gray-400 font-semibold">({{ $mb['persen'] }}%)</span></span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full {{ $idx % 3 === 0 ? 'bg-[#0c3b2e]' : ($idx % 3 === 1 ? 'bg-blue-600' : 'bg-emerald-600') }}" style="width: {{ $mb['persen'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-500 py-4 text-center">Belum ada varian menu.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Transaksi Penjualan Terkini (Full-Width Card) -->
    <div class="w-full bg-white rounded-2xl p-6 sm:p-7 border border-gray-100 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h2 class="text-lg font-bold text-[#0c3b2e]">Transaksi Penjualan Terkini</h2>
                <p class="text-xs text-gray-500 mt-0.5">Data rekap 5 transaksi pesanan terbaru King Es Teler</p>
            </div>
            <a href="{{ route('penjualan.index') }}" 
               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-bold text-[#0c3b2e] bg-emerald-50 hover:bg-emerald-100 transition-colors self-start sm:self-auto">
                <span>Lihat Semua Penjualan</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto rounded-xl border border-gray-100">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50/80 text-gray-500 text-xs font-bold border-b border-gray-200 uppercase tracking-wider">
                        <th class="py-3.5 px-5 text-center w-16">No</th>
                        <th class="py-3.5 px-6">Menu &amp; Tanggal</th>
                        <th class="py-3.5 px-6 text-center">Jumlah</th>
                        <th class="py-3.5 px-6 text-right">Total</th>
                        <th class="py-3.5 px-5 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @forelse($transaksiTerkini as $index => $trx)
                        <tr class="hover:bg-gray-50/70 transition-colors">
                            <td class="py-3.5 px-5 text-center font-bold text-gray-500">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-6 font-bold text-gray-900">
                                <div>{{ $trx->menu->nama_menu ?? '-' }}</div>
                                <div class="text-[11px] font-normal text-gray-400">{{ \Carbon\Carbon::parse($trx->tanggal)->locale('id')->isoFormat('D MMM Y') }}</div>
                            </td>
                            <td class="py-3.5 px-6 text-center font-semibold text-gray-800">{{ $trx->jumlah }} Porsi</td>
                            <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]">Rp {{ number_format($trx->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-5 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200/60 rounded-full">{{ ucfirst($trx->status ?? 'Sukses') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500 text-xs font-medium">Belum ada riwayat transaksi penjualan tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Quick Navigation Link Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-2">
        
        <a href="{{ route('menu.index') }}" 
           class="p-4 bg-white rounded-xl border border-gray-100 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col items-center text-center group cursor-pointer">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-[#0c3b2e] group-hover:bg-[#0c3b2e] group-hover:text-white transition-colors flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h.01M4 12h.01M4 18h.01M8 6h12M8 12h12M8 18h12"/>
                </svg>
            </div>
            <span class="font-bold text-sm text-gray-800 group-hover:text-[#0c3b2e]">Kelola Menu</span>
            <span class="text-xs text-gray-400 mt-0.5">Daftar &amp; Harga</span>
        </a>

        <a href="{{ route('penjualan.index') }}" 
           class="p-4 bg-white rounded-xl border border-gray-100 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col items-center text-center group cursor-pointer">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-[#0c3b2e] group-hover:bg-[#0c3b2e] group-hover:text-white transition-colors flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="font-bold text-sm text-gray-800 group-hover:text-[#0c3b2e]">Data Penjualan</span>
            <span class="text-xs text-gray-400 mt-0.5">Input &amp; Catatan</span>
        </a>

        <a href="{{ route('laporan.index') }}" 
           class="p-4 bg-white rounded-xl border border-gray-100 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col items-center text-center group cursor-pointer">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-[#0c3b2e] group-hover:bg-[#0c3b2e] group-hover:text-white transition-colors flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <span class="font-bold text-sm text-gray-800 group-hover:text-[#0c3b2e]">Laporan Penjualan</span>
            <span class="text-xs text-gray-400 mt-0.5">Rekap Periode</span>
        </a>

        <a href="{{ route('prediksi.index') }}" 
           class="p-4 bg-white rounded-xl border border-gray-100 shadow-2xs hover:shadow-md hover:border-emerald-300 transition-all flex flex-col items-center text-center group cursor-pointer">
            <div class="w-11 h-11 rounded-xl bg-emerald-50 text-[#0c3b2e] group-hover:bg-[#0c3b2e] group-hover:text-white transition-colors flex items-center justify-center mb-2.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <span class="font-bold text-sm text-gray-800 group-hover:text-[#0c3b2e]">Prediksi Kemasan</span>
            <span class="text-xs text-gray-400 mt-0.5">Estimasi Cup</span>
        </a>

    </div>

</div>
@endsection
