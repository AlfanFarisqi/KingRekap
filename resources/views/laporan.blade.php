@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6 max-w-6xl">
    
    <!-- Print Only Official Header -->
    <div class="hidden print:block border-b-2 border-gray-900 pb-3 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-black tracking-tight">KING ES TELER</h1>
                <p class="text-xs text-gray-700">Laporan Penjualan &amp; Rekap Transaksi</p>
            </div>
            <div class="text-right text-xs text-gray-700">
                <p class="font-bold text-gray-900 text-sm">LAPORAN PENJUALAN</p>
                <p class="text-gray-500 mt-0.5">Dicetak: {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y, HH:mm') }} WIB</p>
            </div>
        </div>
    </div>

    <!-- Title Page (Screen Only) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 no-print">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-black tracking-tight">
            Laporan Penjualan
        </h1>

        <!-- Export / Action buttons -->
        <div class="flex items-center gap-2">
            <button type="button" 
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-bold text-gray-700 shadow-2xs transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Laporan</span>
            </button>
            <button type="button" 
                    onclick="exportTableToCSV('laporan-penjualan.csv')"
                    class="inline-flex items-center gap-2 px-3.5 py-2 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-bold text-gray-700 shadow-2xs transition-colors cursor-pointer">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Unduh CSV</span>
            </button>
        </div>
    </div>

    <!-- Filter Periode (Matches screenshot layout) -->
    <div class="space-y-3 pt-2 no-print">
        <h2 class="text-lg font-extrabold text-black">
            Filter Periode
        </h2>

        <div class="flex flex-wrap items-center gap-4 sm:gap-6">
            
            <!-- Segmented Buttons: Harian / Mingguan / Bulanan -->
            <div class="inline-flex rounded-lg overflow-hidden border border-gray-300 shadow-2xs">
                <button type="button" 
                        id="btnHarian"
                        onclick="setPeriod('harian')"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 bg-[#0c3b2e] text-white font-bold text-sm sm:text-[15px] transition-colors cursor-pointer">
                    Harian
                </button>
                <button type="button" 
                        id="btnMingguan"
                        onclick="setPeriod('mingguan')"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 bg-[#cbd5e1] hover:bg-gray-300 text-gray-900 font-bold text-sm sm:text-[15px] border-l border-r border-gray-300 transition-colors cursor-pointer">
                    Mingguan
                </button>
                <button type="button" 
                        id="btnBulanan"
                        onclick="setPeriod('bulanan')"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 bg-[#cbd5e1] hover:bg-gray-300 text-gray-900 font-bold text-sm sm:text-[15px] transition-colors cursor-pointer">
                    Bulanan
                </button>
            </div>

            <!-- Date Input & Tampilkan Button -->
            <div class="flex items-center gap-3">
                <div class="relative" id="calendarContainer">
                    <div class="relative flex items-center">
                        <input type="text" 
                               id="filterDate"
                               value="27/09/2026"
                               readonly
                               onclick="toggleCalendar()"
                               class="w-60 sm:w-68 pl-4 pr-11 py-2 sm:py-2.5 bg-[#dbe2ea] border border-gray-400 rounded-lg text-gray-900 font-bold text-sm sm:text-[15px] focus:outline-none focus:ring-2 focus:ring-[#0c3b2e] cursor-pointer truncate" />
                        <button type="button" 
                                onclick="toggleCalendar()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-gray-700 hover:text-[#0c3b2e] transition-colors cursor-pointer"
                                title="Buka Kalender Tanggal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown Kalender Interaktif -->
                    <div id="calendarDropdown" 
                         class="hidden absolute left-0 top-full mt-2 z-50 bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 w-72 sm:w-80 select-none">
                        
                        <!-- Header Bulan & Tahun Navigasi -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <button type="button" 
                                    onclick="changeCalendarMonth(-1)"
                                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-black cursor-pointer transition-colors"
                                    title="Bulan Sebelumnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="calendarMonthYear" class="text-sm font-extrabold text-[#0c3b2e]">
                                September 2026
                            </span>
                            <button type="button" 
                                    onclick="changeCalendarMonth(1)"
                                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-black cursor-pointer transition-colors"
                                    title="Bulan Berikutnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Header Hari (Min - Sab) -->
                        <div class="grid grid-cols-7 gap-1 pt-3 pb-2 text-center text-xs font-bold text-gray-400">
                            <span>Min</span>
                            <span>Sen</span>
                            <span>Sel</span>
                            <span>Rab</span>
                            <span>Kam</span>
                            <span>Jum</span>
                            <span>Sab</span>
                        </div>

                        <!-- Grid Tanggal Kalender -->
                        <div id="calendarDaysGrid" class="grid grid-cols-7 gap-1 text-center text-xs sm:text-sm font-semibold">
                            <!-- Diisi otomatis via JavaScript -->
                        </div>

                        <!-- Footer Kalender -->
                        <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <button type="button" 
                                    onclick="selectDemoDate()"
                                    class="font-bold text-[#0c3b2e] hover:underline cursor-pointer">
                                27 Sep 2026
                            </button>
                            <button type="button" 
                                    onclick="selectToday()"
                                    class="font-bold text-emerald-700 hover:underline cursor-pointer">
                                Hari Ini
                            </button>
                            <button type="button" 
                                    onclick="closeCalendar()"
                                    class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-md cursor-pointer transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

                <button type="button" 
                        onclick="applyFilter()"
                        class="px-5 sm:px-6 py-2 sm:py-2.5 bg-[#0c3b2e] hover:bg-[#0a2e22] active:scale-98 text-white font-bold text-sm sm:text-base rounded-lg shadow-2xs transition-all duration-150 cursor-pointer">
                    Tampilkan
                </button>
            </div>

        </div>
    </div>

    <!-- Data Table Container (Exact replica of user screenshot) -->
    <div class="bg-white rounded-xl shadow-xs border border-gray-300 overflow-hidden">
        
        <div class="overflow-x-auto">
            <table id="laporanTable" class="w-full border-collapse text-left">
                <!-- Header (light gray background, centered & bordered cells) -->
                <thead>
                    <tr class="bg-[#dbe2ea] text-gray-900 font-bold text-sm sm:text-base border-b border-gray-300">
                        <th class="py-3 px-4 text-center border-r border-gray-300 w-16">No</th>
                        <th class="py-3 px-6 text-center border-r border-gray-300 w-44">Tanggal</th>
                        <th class="py-3 px-6 text-left border-r border-gray-300">Nama Menu</th>
                        <th class="py-3 px-6 text-center border-r border-gray-300 w-36">Jumlah Terjual</th>
                        <th class="py-3 px-6 text-left w-48">Total</th>
                    </tr>
                </thead>
                <!-- Rows with grid lines -->
                <tbody id="tableBody" class="text-sm sm:text-base text-gray-900 divide-y divide-gray-300">
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">1</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Spesial</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">12</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 300.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">2</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">8</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 280.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">3</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler keju</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">10</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 450.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">4</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 150.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">6</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 168.000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Pagination Controls (Matches screenshot) -->
        <div class="px-4 py-3 bg-[#f1f5f9] flex items-center justify-end gap-1.5 border-t border-gray-300 no-print">
            <button type="button" 
                    class="w-7 h-7 flex items-center justify-center rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-100 disabled:opacity-50 text-xs font-bold cursor-pointer"
                    disabled>
                &lsaquo;
            </button>
            <button type="button" 
                    class="w-7 h-7 flex items-center justify-center rounded border border-gray-400 bg-white text-black font-bold text-xs shadow-2xs">
                1
            </button>
            <button type="button" 
                    class="w-7 h-7 flex items-center justify-center rounded border border-gray-300 bg-white text-gray-600 hover:bg-gray-100 disabled:opacity-50 text-xs font-bold cursor-pointer"
                    disabled>
                &rsaquo;
            </button>
        </div>

    </div>

    <!-- Bottom Card: Total Pendapatan (Periode Dipilih) (Matches screenshot) -->
    <div class="bg-[#d5dde8] rounded-2xl p-5 sm:p-7 flex items-center gap-4 sm:gap-6 border border-gray-300 shadow-2xs">
        
        <!-- Cash register / Payment icon -->
        <div class="text-black shrink-0">
            <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20 4H4c-1.11 0-1.99.89-1.99 2L2 18c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V6c0-1.11-.89-2-2-2zm0 14H4v-6h16v6zm0-10H4V6h16v2z"/>
                <circle cx="12" cy="15" r="2"/>
            </svg>
        </div>

        <!-- Text Description and Amount -->
        <div>
            <p class="text-sm sm:text-base font-bold text-gray-900 leading-tight">
                Total Pendapatan (Periode Dipilih)
            </p>
            <h3 id="totalRevenueText" class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-black tracking-tight mt-1">
                Rp 1.348.000
            </h3>
        </div>

    </div>

</div>

<!-- Interactive JS for Calendar, Filters, and Export -->
<script>
    let currentPeriod = 'harian';

    // Calendar state
    let calYear = 2026;
    let calMonth = 8; // 8 = September (0-indexed)
    let selectedDay = 27;
    let selectedMonth = 8;
    let selectedYear = 2026;

    const monthNamesIndo = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    const monthShortIndo = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
    ];

    function toggleCalendar() {
        const dropdown = document.getElementById('calendarDropdown');
        if (dropdown.classList.contains('hidden')) {
            renderCalendar();
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function closeCalendar() {
        const dropdown = document.getElementById('calendarDropdown');
        if (dropdown) dropdown.classList.add('hidden');
    }

    function changeCalendarMonth(delta) {
        calMonth += delta;
        if (calMonth < 0) {
            calMonth = 11;
            calYear--;
        } else if (calMonth > 11) {
            calMonth = 0;
            calYear++;
        }
        renderCalendar();
    }

    function renderCalendar() {
        const titleEl = document.getElementById('calendarMonthYear');
        const gridEl = document.getElementById('calendarDaysGrid');
        
        titleEl.textContent = monthNamesIndo[calMonth] + ' ' + calYear;
        gridEl.innerHTML = '';

        // Day of week of 1st day of month (0 = Sunday, 1 = Monday, etc.)
        const firstDayIndex = new Date(calYear, calMonth, 1).getDay();
        const totalDays = new Date(calYear, calMonth + 1, 0).getDate();

        // Previous month filler days
        const prevMonthTotalDays = new Date(calYear, calMonth, 0).getDate();
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const emptyBtn = document.createElement('div');
            emptyBtn.className = 'py-2 text-gray-300 text-xs cursor-default select-none';
            emptyBtn.textContent = prevMonthTotalDays - i;
            gridEl.appendChild(emptyBtn);
        }

        // Current month days
        for (let d = 1; d <= totalDays; d++) {
            const dayBtn = document.createElement('button');
            dayBtn.type = 'button';
            dayBtn.textContent = d;

            const isSelected = (d === selectedDay && calMonth === selectedMonth && calYear === selectedYear);

            if (isSelected) {
                dayBtn.className = 'py-2 rounded-lg bg-[#0c3b2e] text-white font-bold text-xs sm:text-sm shadow-xs cursor-pointer transition-transform hover:scale-105';
            } else {
                dayBtn.className = 'py-2 rounded-lg text-gray-800 hover:bg-emerald-50 hover:text-[#0c3b2e] font-medium text-xs sm:text-sm cursor-pointer transition-colors';
            }

            dayBtn.onclick = function() {
                selectDate(d, calMonth, calYear);
            };

            gridEl.appendChild(dayBtn);
        }
    }

    function selectDate(day, month, year) {
        selectedDay = day;
        selectedMonth = month;
        selectedYear = year;

        const dd = String(day).padStart(2, '0');
        const mm = String(month + 1).padStart(2, '0');

        if (currentPeriod === 'harian') {
            document.getElementById('filterDate').value = `${dd}/${mm}/${year}`;
        } else if (currentPeriod === 'mingguan') {
            const weekNum = Math.min(Math.ceil(day / 7), 4);
            document.getElementById('filterDate').value = `Minggu ke-${weekNum} ${monthShortIndo[month]} ${year}`;
        } else if (currentPeriod === 'bulanan') {
            document.getElementById('filterDate').value = `${monthNamesIndo[month]} ${year}`;
        }

        closeCalendar();
        applyFilter();
    }

    function selectToday() {
        const today = new Date();
        calMonth = 9; // Oktober
        calYear = 2026;
        selectDate(17, 9, 2026);
    }

    function selectDemoDate() {
        calMonth = 8; // September
        calYear = 2026;
        selectDate(27, 8, 2026);
    }

    // Close calendar when clicking outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('calendarContainer');
        if (container && !container.contains(event.target)) {
            closeCalendar();
        }
    });

    function setPeriod(period) {
        currentPeriod = period;
        const btnHarian = document.getElementById('btnHarian');
        const btnMingguan = document.getElementById('btnMingguan');
        const btnBulanan = document.getElementById('btnBulanan');
        const dateInput = document.getElementById('filterDate');

        // Reset styling
        [btnHarian, btnMingguan, btnBulanan].forEach(btn => {
            btn.className = "px-5 sm:px-6 py-2 sm:py-2.5 bg-[#cbd5e1] hover:bg-gray-300 text-gray-900 font-bold text-sm sm:text-[15px] border-gray-300 transition-colors cursor-pointer";
        });

        if (period === 'harian') {
            btnHarian.className = "px-5 sm:px-6 py-2 sm:py-2.5 bg-[#0c3b2e] text-white font-bold text-sm sm:text-[15px] transition-colors cursor-pointer";
            const dd = String(selectedDay).padStart(2, '0');
            const mm = String(selectedMonth + 1).padStart(2, '0');
            dateInput.value = `${dd}/${mm}/${selectedYear}`;
        } else if (period === 'mingguan') {
            btnMingguan.className = "px-5 sm:px-6 py-2 sm:py-2.5 bg-[#0c3b2e] text-white font-bold text-sm sm:text-[15px] border-l border-r border-gray-300 transition-colors cursor-pointer";
            const weekNum = Math.min(Math.ceil(selectedDay / 7), 4);
            dateInput.value = `Minggu ke-${weekNum} ${monthShortIndo[selectedMonth]} ${selectedYear}`;
        } else if (period === 'bulanan') {
            btnBulanan.className = "px-5 sm:px-6 py-2 sm:py-2.5 bg-[#0c3b2e] text-white font-bold text-sm sm:text-[15px] transition-colors cursor-pointer";
            dateInput.value = `${monthNamesIndo[selectedMonth]} ${selectedYear}`;
        }
        applyFilter();
    }

    function applyFilter() {
        const totalText = document.getElementById('totalRevenueText');
        const tbody = document.getElementById('tableBody');
        const dateStr = `${selectedDay} ${monthShortIndo[selectedMonth]} ${selectedYear}`;

        if (currentPeriod === 'harian') {
            if (selectedDay === 27 && selectedMonth === 8 && selectedYear === 2026) {
                totalText.textContent = 'Rp 1.348.000';
                tbody.innerHTML = `
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">1</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Spesial</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">12</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 300.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">2</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">8</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 280.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">3</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler keju</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">10</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 450.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">4</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 150.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">27 Sep 2026</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">6</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 168.000</td>
                    </tr>
                `;
            } else {
                totalText.textContent = 'Rp 1.145.000';
                tbody.innerHTML = `
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">1</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">${dateStr}</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Spesial</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">10</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 250.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">2</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">${dateStr}</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Original</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">9</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 315.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">3</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">${dateStr}</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Reguler</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">6</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 270.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">4</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">${dateStr}</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Spesial Buah</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 140.000</td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3 px-4 text-center font-bold border-r border-gray-300">5</td>
                        <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">${dateStr}</td>
                        <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Durian Monthong</td>
                        <td class="py-3 px-6 text-center font-bold border-r border-gray-300">4</td>
                        <td class="py-3 px-6 text-left font-bold text-black">Rp 170.000</td>
                    </tr>
                `;
            }
        } else if (currentPeriod === 'mingguan') {
            totalText.textContent = 'Rp 9.436.000';
            tbody.innerHTML = `
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">1</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">21 - 27 Sep 2026</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Spesial</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">92</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 2.300.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">2</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">21 - 27 Sep 2026</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Original</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">65</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 2.275.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">3</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">21 - 27 Sep 2026</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Keju Reguler</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">68</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 3.060.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">4</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">21 - 27 Sep 2026</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Spesial Buah</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">41</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 1.148.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">5</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">21 - 27 Sep 2026</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Es Teler Durian</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">22</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 653.000</td>
                </tr>
            `;
        } else if (currentPeriod === 'bulanan') {
            totalText.textContent = 'Rp 38.850.000';
            tbody.innerHTML = `
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">1</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">Sep 2026 (Minggu 1)</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Semua Varian Menu</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">260</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 9.100.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">2</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">Sep 2026 (Minggu 2)</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Semua Varian Menu</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">285</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 9.975.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">3</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">Sep 2026 (Minggu 3)</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Semua Varian Menu</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">295</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 10.339.000</td>
                </tr>
                <tr class="hover:bg-gray-50/80 transition-colors">
                    <td class="py-3 px-4 text-center font-bold border-r border-gray-300">4</td>
                    <td class="py-3 px-6 text-left border-r border-gray-300 whitespace-nowrap font-medium">Sep 2026 (Minggu 4)</td>
                    <td class="py-3 px-6 text-left font-bold border-r border-gray-300">Semua Varian Menu</td>
                    <td class="py-3 px-6 text-center font-bold border-r border-gray-300">270</td>
                    <td class="py-3 px-6 text-left font-bold text-black">Rp 9.436.000</td>
                </tr>
            `;
        }
    }

    function exportTableToCSV(filename) {
        let csv = [];
        let rows = document.querySelectorAll("#laporanTable tr");
        for (let i = 0; i < rows.length; i++) {
            let row = [], cols = rows[i].querySelectorAll("td, th");
            for (let j = 0; j < cols.length; j++) {
                row.push('"' + cols[j].innerText.trim() + '"');
            }
            csv.push(row.join(","));
        }
        let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
        let downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
@endsection
