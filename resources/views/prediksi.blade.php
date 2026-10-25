@extends('layouts.admin')

@section('title', 'Prediksi Kebutuhan Kemasan')

@section('content')
<div class="space-y-6 max-w-5xl">
    
    <!-- Title Page & Subtitle -->
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3b2e] tracking-tight">
            Prediksi Kebutuhan Kemasan
        </h1>
        <p class="text-xs sm:text-sm text-gray-600 mt-1">
            Gunakan data historis penjualan untuk memprediksi kebutuhan kemasan anda
        </p>
    </div>

    <!-- 1. Card Input Prediksi -->
    <div class="bg-[#dbe2ea] rounded-2xl p-5 sm:p-7 shadow-xs border border-gray-300/80">
        <h2 class="text-lg sm:text-xl font-extrabold text-[#0c3b2e] mb-3">
            Input Prediksi
        </h2>

        <div class="space-y-2">
            <label for="rentangData" class="block text-sm sm:text-base font-semibold text-gray-800">
                Pilih Rentang Data Historis
            </label>

            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4">
                <!-- Dropdown Select -->
                <div class="relative w-full sm:w-96">
                    <select id="rentangData" 
                            onchange="updatePredictionChart()"
                            class="w-full appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2.5 sm:py-3 pr-10 text-sm sm:text-base font-medium text-gray-900 shadow-2xs focus:outline-none focus:ring-2 focus:ring-[#0c3b2e] cursor-pointer">
                        <option value="4">4 Minggu Terakhir (1 Sep - 30 Sep 2026)</option>
                        <option value="3">3 Minggu Terakhir (8 Sep - 30 Sep 2026)</option>
                        <option value="2">2 Minggu Terakhir (15 Sep - 30 Sep 2026)</option>
                        <option value="1">1 Minggu Terakhir (23 Sep - 30 Sep 2026)</option>
                    </select>
                    <!-- Custom Arrow Icon -->
                    <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </div>
                </div>

                <!-- Tombol Hitung Prediksi Stok Kemasan -->
                <button type="button" 
                        id="btnHitung"
                        onclick="calculatePrediction()"
                        class="px-5 sm:px-6 py-2.5 sm:py-3 bg-[#0e3c2f] hover:bg-[#0a2e22] active:scale-98 text-white font-bold text-sm sm:text-base rounded-lg shadow-2xs transition-all duration-150 cursor-pointer flex items-center justify-center gap-2 shrink-0">
                    <span id="btnHitungText">Hitung Prediksi Stok Kemasan</span>
                    <svg id="btnSpinner" class="hidden animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 2. Card Hasil Analisa -->
    <div class="bg-[#dbe2ea] rounded-2xl p-5 sm:p-7 shadow-xs border border-gray-300/80 space-y-4">
        
        <div>
            <h2 class="text-lg sm:text-xl font-extrabold text-[#0c3b2e]">
                Hasil Analisa
            </h2>
            <h3 class="text-sm sm:text-base font-bold text-gray-800 mt-0.5">
                Tren Penggunaan Kemasan (Cup)
            </h3>
        </div>

        <!-- White Chart Box Container -->
        <div class="bg-white rounded-xl p-4 sm:p-6 shadow-2xs border border-gray-200/90">
            
            <!-- Legend (Top Right) -->
            <div class="flex items-center justify-end gap-5 text-xs text-gray-600 mb-3 font-medium">
                <div class="flex items-center gap-1.5">
                    <span class="w-3.5 h-3.5 rounded-xs inline-block shadow-2xs" style="background-color: #0c3b2e;"></span>
                    <span class="font-bold text-gray-800">Data Historis</span>
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="w-3.5 h-3.5 rounded-xs inline-block shadow-2xs" style="background-color: #0284c7;"></span>
                    <span class="font-bold text-gray-800">Prediksi</span>
                </div>
            </div>

            <!-- Responsive SVG Line Chart -->
            <div class="relative w-full overflow-x-auto">
                <div class="min-w-[500px]">
                    <svg viewBox="0 0 700 240" class="w-full h-56 sm:h-64 overflow-visible">
                        <defs>
                            <!-- Gradient for Data Historis (Theme Green) -->
                            <linearGradient id="historisGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0c3b2e" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#0c3b2e" stop-opacity="0.05"/>
                            </linearGradient>

                            <!-- Gradient for Prediksi (Theme Blue) -->
                            <linearGradient id="prediksiGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="#0284c7" stop-opacity="0.35"/>
                                <stop offset="100%" stop-color="#0284c7" stop-opacity="0.05"/>
                            </linearGradient>
                        </defs>

                        <!-- Horizontal Grid Lines and Y-Axis Labels -->
                        <!-- 400 -->
                        <text x="35" y="28" text-anchor="end" fill="#9ca3af" font-size="11" font-weight="500">400</text>
                        <line x1="45" y1="25" x2="680" y2="25" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 300 -->
                        <text x="35" y="78" text-anchor="end" fill="#9ca3af" font-size="11" font-weight="500">300</text>
                        <line x1="45" y1="75" x2="680" y2="75" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 200 -->
                        <text x="35" y="128" text-anchor="end" fill="#9ca3af" font-size="11" font-weight="500">200</text>
                        <line x1="45" y1="125" x2="680" y2="125" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 100 -->
                        <text x="35" y="178" text-anchor="end" fill="#9ca3af" font-size="11" font-weight="500">100</text>
                        <line x1="45" y1="175" x2="680" y2="175" stroke="#f1f5f9" stroke-width="1.2" />

                        <!-- 0 Baseline -->
                        <text x="35" y="218" text-anchor="end" fill="#9ca3af" font-size="11" font-weight="500">0</text>
                        <line x1="45" y1="215" x2="680" y2="215" stroke="#e2e8f0" stroke-width="1.5" />

                        <!-- X-Axis Labels (Minggu 4 to 10) -->
                        <text x="68" y="232" text-anchor="middle" fill="#64748b" font-size="11" font-weight="600">Minggu 4</text>
                        <text x="170" y="232" text-anchor="middle" fill="#64748b" font-size="11" font-weight="600">Minggu 5</text>
                        <text x="272" y="232" text-anchor="middle" fill="#64748b" font-size="11" font-weight="600">Minggu 6</text>
                        <text x="374" y="232" text-anchor="middle" fill="#64748b" font-size="11" font-weight="600">Minggu 7</text>
                        <text x="476" y="232" text-anchor="middle" fill="#0284c7" font-size="11" font-weight="700">Minggu 8</text>
                        <text x="578" y="232" text-anchor="middle" fill="#0284c7" font-size="11" font-weight="700">Minggu 9</text>
                        <text x="670" y="232" text-anchor="middle" fill="#0284c7" font-size="11" font-weight="700">Minggu 10</text>

                        <!-- Shaded Area Fills -->
                        <polygon id="historisArea"
                                 points="68,155 170,125 272,145 374,100 374,215 68,215" 
                                 fill="url(#historisGradient)" />

                        <polygon id="prediksiArea"
                                 points="374,100 476,75 578,55 670,35 670,215 374,215" 
                                 fill="url(#prediksiGradient)" />

                        <!-- Solid Line: Data Historis (Weeks 4 to 7) -->
                        <polyline id="historisLine"
                                  fill="none" 
                                  stroke="#0c3b2e" 
                                  stroke-width="3" 
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                  points="68,155 170,125 272,145 374,100" />

                        <!-- Value Badges / Labels on Data Historis -->
                        <text x="68" y="145" text-anchor="middle" fill="#0c3b2e" font-size="10" font-weight="700">140</text>
                        <text x="170" y="115" text-anchor="middle" fill="#0c3b2e" font-size="10" font-weight="700">190</text>
                        <text x="272" y="135" text-anchor="middle" fill="#0c3b2e" font-size="10" font-weight="700">160</text>
                        <text x="374" y="90" text-anchor="middle" fill="#0c3b2e" font-size="10" font-weight="700">240</text>

                        <!-- Dots on Data Historis -->
                        <circle cx="68" cy="155" r="4.5" fill="#0c3b2e" stroke="#ffffff" stroke-width="2" />
                        <circle cx="170" cy="125" r="4.5" fill="#0c3b2e" stroke="#ffffff" stroke-width="2" />
                        <circle cx="272" cy="145" r="4.5" fill="#0c3b2e" stroke="#ffffff" stroke-width="2" />
                        <circle cx="374" cy="100" r="4.5" fill="#0c3b2e" stroke="#ffffff" stroke-width="2" />

                        <!-- Dashed Line: Prediksi (Weeks 7 to 10) in Royal/King Blue -->
                        <polyline id="prediksiLine"
                                  fill="none" 
                                  stroke="#0284c7" 
                                  stroke-width="3" 
                                  stroke-dasharray="6,4"
                                  stroke-linecap="round" 
                                  stroke-linejoin="round"
                                  points="374,100 476,75 578,55 670,35" />

                        <!-- Value Badges / Labels on Prediksi -->
                        <text id="lblPred1" x="476" y="65" text-anchor="middle" fill="#0284c7" font-size="10" font-weight="700">280</text>
                        <text id="lblPred2" x="578" y="45" text-anchor="middle" fill="#0284c7" font-size="10" font-weight="700">310</text>
                        <text id="lblPred3" x="670" y="25" text-anchor="middle" fill="#0284c7" font-size="10" font-weight="700">350</text>

                        <!-- Dots on Prediksi in King Blue -->
                        <circle id="dotPred1" cx="476" cy="75" r="4.5" fill="#0284c7" stroke="#ffffff" stroke-width="2" />
                        <circle id="dotPred2" cx="578" cy="55" r="4.5" fill="#0284c7" stroke="#ffffff" stroke-width="2" />
                        <circle id="dotPred3" cx="670" cy="35" r="4.5" fill="#0284c7" stroke="#ffffff" stroke-width="2" />
                    </svg>
                </div>
            </div>

        </div>

        <!-- 3. Bottom Alert 1: Perkiraan Kebutuhan Kemasan -->
        <div class="bg-[#d5e3ef] rounded-xl p-3.5 sm:p-4 flex items-center gap-3.5 sm:gap-4 border border-blue-200/50 shadow-2xs">
            <div class="text-[#0284c7] shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
                </svg>
            </div>
            <p id="alertPrediksiText" class="text-xs sm:text-sm text-gray-900 leading-snug">
                Berdasarkan tren penjualan, <span class="font-bold">perkiraan kebutuhan kemasan</span> anda untuk minggu ke depan adalah sebanyak <span class="font-bold text-[#0284c7]" id="prediksiCupVal">280 cup</span>.
            </p>
        </div>

        <!-- 4. Bottom Alert 2: Disclaimer Pertimbangan -->
        <div class="bg-[#f5edd8] rounded-xl p-3.5 sm:p-4 flex items-center gap-3.5 sm:gap-4 border border-amber-200/50 shadow-2xs">
            <div class="text-amber-800 shrink-0">
                <svg class="w-6 h-6 sm:w-7 sm:h-7" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>
            </div>
            <p class="text-xs sm:text-sm text-gray-900 leading-snug">
                Hasil ini adalah bahan pertimbangan untuk membantu persiapan matang, bukan pembelian otomatis.
            </p>
        </div>

    </div>

</div>

<!-- Interactive Prediction JS -->
<script>
    function calculatePrediction() {
        const btn = document.getElementById('btnHitung');
        const btnText = document.getElementById('btnHitungText');
        const spinner = document.getElementById('btnSpinner');

        btn.disabled = true;
        btnText.textContent = 'Menghitung...';
        spinner.classList.remove('hidden');

        setTimeout(() => {
            btn.disabled = false;
            btnText.textContent = 'Hitung Prediksi Stok Kemasan';
            spinner.classList.add('hidden');

            const select = document.getElementById('rentangData').value;
            const cupVal = document.getElementById('prediksiCupVal');
            
            const predLine = document.getElementById('prediksiLine');
            const predArea = document.getElementById('prediksiArea');
            const dot1 = document.getElementById('dotPred1');
            const dot2 = document.getElementById('dotPred2');
            const dot3 = document.getElementById('dotPred3');
            const lbl1 = document.getElementById('lblPred1');
            const lbl2 = document.getElementById('lblPred2');
            const lbl3 = document.getElementById('lblPred3');
            
            if (select === '4') {
                cupVal.textContent = '280 cup';
                if (predLine) predLine.setAttribute('points', '374,100 476,75 578,55 670,35');
                if (predArea) predArea.setAttribute('points', '374,100 476,75 578,55 670,35 670,215 374,215');
                if (dot1) { dot1.setAttribute('cx', '476'); dot1.setAttribute('cy', '75'); }
                if (dot2) { dot2.setAttribute('cx', '578'); dot2.setAttribute('cy', '55'); }
                if (dot3) { dot3.setAttribute('cx', '670'); dot3.setAttribute('cy', '35'); }
                if (lbl1) { lbl1.setAttribute('y', '65'); lbl1.textContent = '280'; }
                if (lbl2) { lbl2.setAttribute('y', '45'); lbl2.textContent = '310'; }
                if (lbl3) { lbl3.setAttribute('y', '25'); lbl3.textContent = '350'; }
            } else if (select === '3') {
                cupVal.textContent = '295 cup';
                if (predLine) predLine.setAttribute('points', '374,100 476,68 578,48 670,30');
                if (predArea) predArea.setAttribute('points', '374,100 476,68 578,48 670,30 670,215 374,215');
                if (dot1) { dot1.setAttribute('cx', '476'); dot1.setAttribute('cy', '68'); }
                if (dot2) { dot2.setAttribute('cx', '578'); dot2.setAttribute('cy', '48'); }
                if (dot3) { dot3.setAttribute('cx', '670'); dot3.setAttribute('cy', '30'); }
                if (lbl1) { lbl1.setAttribute('y', '58'); lbl1.textContent = '295'; }
                if (lbl2) { lbl2.setAttribute('y', '38'); lbl2.textContent = '325'; }
                if (lbl3) { lbl3.setAttribute('y', '20'); lbl3.textContent = '360'; }
            } else if (select === '2') {
                cupVal.textContent = '310 cup';
                if (predLine) predLine.setAttribute('points', '374,100 476,60 578,40 670,22');
                if (predArea) predArea.setAttribute('points', '374,100 476,60 578,40 670,22 670,215 374,215');
                if (dot1) { dot1.setAttribute('cx', '476'); dot1.setAttribute('cy', '60'); }
                if (dot2) { dot2.setAttribute('cx', '578'); dot2.setAttribute('cy', '40'); }
                if (dot3) { dot3.setAttribute('cx', '670'); dot3.setAttribute('cy', '22'); }
                if (lbl1) { lbl1.setAttribute('y', '50'); lbl1.textContent = '310'; }
                if (lbl2) { lbl2.setAttribute('y', '30'); lbl2.textContent = '340'; }
                if (lbl3) { lbl3.setAttribute('y', '14'); lbl3.textContent = '375'; }
            } else {
                cupVal.textContent = '320 cup';
                if (predLine) predLine.setAttribute('points', '374,100 476,55 578,35 670,18');
                if (predArea) predArea.setAttribute('points', '374,100 476,55 578,35 670,18 670,215 374,215');
                if (dot1) { dot1.setAttribute('cx', '476'); dot1.setAttribute('cy', '55'); }
                if (dot2) { dot2.setAttribute('cx', '578'); dot2.setAttribute('cy', '35'); }
                if (dot3) { dot3.setAttribute('cx', '670'); dot3.setAttribute('cy', '18'); }
                if (lbl1) { lbl1.setAttribute('y', '45'); lbl1.textContent = '320'; }
                if (lbl2) { lbl2.setAttribute('y', '25'); lbl2.textContent = '350'; }
                if (lbl3) { lbl3.setAttribute('y', '10'); lbl3.textContent = '390'; }
            }
        }, 400);
    }

    function updatePredictionChart() {
        calculatePrediction();
    }
</script>
@endsection

