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

    <!-- Header Screen Only -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-gray-200/80 no-print">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3b2e] tracking-tight">
                Laporan Penjualan
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                Rekapitulasi transaksi, performa penjualan menu, dan ringkasan omzet King Es Teler.
            </p>
        </div>

        <!-- Action buttons -->
        <div class="flex items-center gap-2.5">
            <button type="button" 
                    onclick="window.print()"
                    class="inline-flex items-center gap-2 px-3.5 py-2.5 bg-white hover:bg-gray-50 border border-gray-300 rounded-lg text-xs font-bold text-gray-700 shadow-2xs hover:shadow transition-all cursor-pointer">
                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak Laporan</span>
            </button>
            <button type="button" 
                    onclick="exportTableToCSV('laporan-penjualan.csv')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0c3b2e] hover:bg-[#08281f] text-white rounded-lg text-xs font-bold shadow-sm hover:shadow transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Unduh CSV</span>
            </button>
        </div>
    </div>

    <!-- Filter Card: Selaras dengan Form Input Card Data Penjualan -->
    <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm no-print">
        <h2 class="text-lg font-extrabold text-[#0c3b2e] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            <span>Filter Periode Laporan</span>
        </h2>

        <div class="flex flex-wrap lg:flex-nowrap items-end gap-4">
            
            <!-- Segmented Buttons: Semua / Harian / Mingguan / Bulanan -->
            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Jangka Waktu Periode</label>
                <div class="inline-flex p-1 bg-gray-100 rounded-xl border border-gray-200/80">
                    <button type="button" 
                            id="btnSemua"
                            onclick="setPeriod('semua')"
                            class="px-4 py-2 {{ ($periode ?? 'semua') === 'semua' ? 'bg-[#0c3b2e] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60' }} rounded-lg font-bold text-xs sm:text-sm transition-all cursor-pointer">
                        Semua
                    </button>
                    <button type="button" 
                            id="btnHarian"
                            onclick="setPeriod('harian')"
                            class="px-4 py-2 {{ ($periode ?? '') === 'harian' ? 'bg-[#0c3b2e] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60' }} rounded-lg font-bold text-xs sm:text-sm transition-all cursor-pointer">
                        Harian
                    </button>
                    <button type="button" 
                            id="btnMingguan"
                            onclick="setPeriod('mingguan')"
                            class="px-4 py-2 {{ ($periode ?? '') === 'mingguan' ? 'bg-[#0c3b2e] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60' }} rounded-lg font-bold text-xs sm:text-sm transition-all cursor-pointer">
                        Mingguan
                    </button>
                    <button type="button" 
                            id="btnBulanan"
                            onclick="setPeriod('bulanan')"
                            class="px-4 py-2 {{ ($periode ?? '') === 'bulanan' ? 'bg-[#0c3b2e] text-white shadow-xs' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-200/60' }} rounded-lg font-bold text-xs sm:text-sm transition-all cursor-pointer">
                        Bulanan
                    </button>
                </div>
            </div>

            <!-- Tanggal Input & Dropdown Kalender -->
            <div class="flex-1 min-w-[220px]">
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Tanggal / Acuan Periode</label>
                <div class="relative" id="calendarContainer">
                    <div class="relative flex items-center">
                        <input type="text" 
                               id="filterDate"
                               value="{{ ($periode ?? 'semua') === 'semua' ? 'Semua Riwayat Transaksi' : $selectedDate }}"
                               readonly
                               onclick="toggleCalendar()"
                               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none cursor-pointer truncate" />
                        <button type="button" 
                                onclick="toggleCalendar()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-gray-500 hover:text-[#0c3b2e] transition-colors cursor-pointer"
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
                            <span id="calendarMonthYear" class="text-sm font-extrabold text-[#0c3b2e]"></span>
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
                            <!-- Diisi via JavaScript -->
                        </div>

                        <!-- Footer Kalender -->
                        <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-between text-xs">
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
            </div>

            <!-- Tombol Aksi Filter -->
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" 
                        onclick="applyFilter()"
                        class="py-2.5 px-5 bg-[#0c3b2e] hover:bg-[#08281f] text-white font-bold text-sm rounded-lg shadow-sm transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <span>Terapkan Filter</span>
                </button>
                <button type="button" 
                        onclick="setPeriod('semua')"
                        class="py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-lg transition-colors cursor-pointer whitespace-nowrap">
                    Reset
                </button>
            </div>

        </div>

        <!-- Info Bar Status Filter Aktif -->
        <div class="mt-4 pt-3.5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
            <div class="text-gray-500">
                Status Filter: <span id="filterStatusText" class="font-bold text-[#0c3b2e]">{{ ($periode ?? 'semua') === 'semua' ? 'Menampilkan Semua Riwayat Transaksi' : 'Periode ' . ucfirst($periode ?? '') }}</span>
            </div>
            <div class="text-gray-400">
                Data diperbarui secara riil dari database transaksi
            </div>
        </div>

    </div>

    <!-- Tabel Rekap Transaksi (Selaras dengan Riwayat Transaksi Penjualan) -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        
        <!-- Header Card Tabel -->
        <div class="p-4 sm:px-6 bg-gray-50/80 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-base text-gray-900">Rekapitulasi Data Transaksi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Daftar transaksi penjualan terperinci King Es Teler</p>
            </div>
            <div id="summaryBadge" class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3.5 py-1.5 rounded-lg border border-emerald-200 shrink-0">
                Total: {{ $totalPorsi }} Porsi (Rp {{ number_format($totalPendapatan, 0, ',', '.') }})
            </div>
        </div>

        <!-- Tabel Transaksi -->
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm" id="laporanTable">
                <thead>
                    <tr class="bg-gray-100/90 text-gray-800 font-bold border-b border-gray-200">
                        <th class="py-3.5 px-4 text-center w-16">No</th>
                        <th class="py-3.5 px-4 text-center w-36">Tanggal</th>
                        <th class="py-3.5 px-6">Nama Menu</th>
                        <th class="py-3.5 px-6 text-center w-32">Jumlah</th>
                        <th class="py-3.5 px-6 text-right w-44">Total Harga</th>
                        <th class="py-3.5 px-4 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100 text-gray-900">
                    @forelse($laporan as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMM Y') }}
                            </td>
                            <td class="py-3.5 px-6 font-semibold text-gray-900">
                                {{ $item->menu->nama_menu ?? $item->nama_menu ?? 'Es Teler' }}
                            </td>
                            <td class="py-3.5 px-6 text-center font-bold text-gray-800">
                                {{ $item->jumlah }} Porsi
                            </td>
                            <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]">
                                Rp {{ number_format($item->total_harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($item->status ?? 'sukses') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyReportRow">
                            <td colspan="6" class="py-8 text-center text-gray-500 font-medium">
                                Tidak ada transaksi penjualan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Table Footer / Summary Row -->
        <div class="px-6 py-4 bg-gray-50/80 border-t border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-gray-600 font-medium">
            <div>
                Menampilkan data laporan penjualan terverifikasi kasir.
            </div>
            <div class="flex items-center gap-4">
                <span>Total Omzet: <strong id="footerTotalOmzet" class="text-[#0c3b2e] font-extrabold text-sm">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</strong></span>
            </div>
        </div>

    </div>

</div>

<!-- Interactive JS for Calendar, Filters, and Export -->
<script>
    let currentPeriod = '{{ $periode ?? "semua" }}';

    // Calendar state
    const initialDateStr = '{{ $selectedDate }}';
    const dateParts = initialDateStr.split('/');
    let initDay = dateParts.length === 3 ? parseInt(dateParts[0], 10) : new Date().getDate();
    let initMonth = dateParts.length === 3 ? (parseInt(dateParts[1], 10) - 1) : new Date().getMonth();
    let initYear = dateParts.length === 3 ? parseInt(dateParts[2], 10) : new Date().getFullYear();

    let calYear = initYear;
    let calMonth = initMonth;
    let selectedDay = initDay;
    let selectedMonth = initMonth;
    let selectedYear = initYear;

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

        const firstDayIndex = new Date(calYear, calMonth, 1).getDay();
        const totalDays = new Date(calYear, calMonth + 1, 0).getDate();

        const prevMonthTotalDays = new Date(calYear, calMonth, 0).getDate();
        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const emptyBtn = document.createElement('div');
            emptyBtn.className = 'py-2 text-gray-300 text-xs cursor-default select-none';
            emptyBtn.textContent = prevMonthTotalDays - i;
            gridEl.appendChild(emptyBtn);
        }

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

        if (currentPeriod === 'semua') {
            currentPeriod = 'harian';
            updatePeriodButtonsUI('harian');
        }

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
        calMonth = today.getMonth();
        calYear = today.getFullYear();
        selectDate(today.getDate(), today.getMonth(), today.getFullYear());
    }

    document.addEventListener('click', function(event) {
        const container = document.getElementById('calendarContainer');
        if (container && !container.contains(event.target)) {
            closeCalendar();
        }
    });

    function updatePeriodButtonsUI(period) {
        const btnSemua = document.getElementById('btnSemua');
        const btnHarian = document.getElementById('btnHarian');
        const btnMingguan = document.getElementById('btnMingguan');
        const btnBulanan = document.getElementById('btnBulanan');

        const inactiveClass = "px-4 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-200/60 rounded-lg font-semibold text-xs sm:text-sm transition-all cursor-pointer";
        const activeClass = "px-4 py-2 bg-[#0c3b2e] text-white rounded-lg font-bold text-xs sm:text-sm shadow-xs transition-all cursor-pointer";

        [btnSemua, btnHarian, btnMingguan, btnBulanan].forEach(btn => {
            if (btn) btn.className = inactiveClass;
        });

        if (period === 'semua' && btnSemua) btnSemua.className = activeClass;
        if (period === 'harian' && btnHarian) btnHarian.className = activeClass;
        if (period === 'mingguan' && btnMingguan) btnMingguan.className = activeClass;
        if (period === 'bulanan' && btnBulanan) btnBulanan.className = activeClass;
    }

    function setPeriod(period) {
        currentPeriod = period;
        updatePeriodButtonsUI(period);

        const dateInput = document.getElementById('filterDate');

        if (period === 'semua') {
            dateInput.value = 'Semua Riwayat Transaksi';
        } else if (period === 'harian') {
            const dd = String(selectedDay).padStart(2, '0');
            const mm = String(selectedMonth + 1).padStart(2, '0');
            dateInput.value = `${dd}/${mm}/${selectedYear}`;
        } else if (period === 'mingguan') {
            const weekNum = Math.min(Math.ceil(selectedDay / 7), 4);
            dateInput.value = `Minggu ke-${weekNum} ${monthShortIndo[selectedMonth]} ${selectedYear}`;
        } else if (period === 'bulanan') {
            dateInput.value = `${monthNamesIndo[selectedMonth]} ${selectedYear}`;
        }

        applyFilter();
    }

    function formatRupiah(number) {
        return 'Rp ' + Number(number).toLocaleString('id-ID');
    }

    async function applyFilter() {
        const tbody = document.getElementById('tableBody');
        const summaryBadge = document.getElementById('summaryBadge');
        const filterStatusText = document.getElementById('filterStatusText');
        const footerTotalOmzet = document.getElementById('footerTotalOmzet');

        const dd = String(selectedDay).padStart(2, '0');
        const mm = String(selectedMonth + 1).padStart(2, '0');
        const dateParam = `${dd}/${mm}/${selectedYear}`;

        try {
            const url = `{{ route('laporan.data') }}?periode=${currentPeriod}&tanggal=${encodeURIComponent(dateParam)}`;
            const response = await fetch(url, {
                headers: { 'Accept': 'application/json' }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                if (footerTotalOmzet) footerTotalOmzet.textContent = result.totalPendapatanFormatted;

                // Update badge and status text
                if (summaryBadge) {
                    summaryBadge.textContent = `Total: ${result.totalPorsi} Porsi (${result.totalPendapatanFormatted})`;
                }
                
                if (filterStatusText) {
                    if (currentPeriod === 'semua') {
                        filterStatusText.textContent = `Menampilkan Semua Riwayat Transaksi (${result.data.length} transaksi)`;
                    } else if (currentPeriod === 'harian') {
                        filterStatusText.textContent = `Filter Harian: ${result.tanggal} (${result.data.length} transaksi)`;
                    } else if (currentPeriod === 'mingguan') {
                        filterStatusText.textContent = `Filter Mingguan: ${result.tanggal} (${result.data.length} transaksi)`;
                    } else if (currentPeriod === 'bulanan') {
                        filterStatusText.textContent = `Filter Bulanan: ${result.tanggal} (${result.data.length} transaksi)`;
                    }
                }

                // Update Table
                if (result.data && result.data.length > 0) {
                    tbody.innerHTML = result.data.map(item => `
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-gray-700">${item.no}</td>
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 whitespace-nowrap">${item.tanggal}</td>
                            <td class="py-3.5 px-6 font-semibold text-gray-900">${item.nama_menu}</td>
                            <td class="py-3.5 px-6 text-center font-bold text-gray-800">${item.jumlah} Porsi</td>
                            <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]">${item.total_harga_formatted}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Sukses
                                </span>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="py-8 text-center text-gray-500 font-medium">Tidak ada transaksi penjualan pada periode ini.</td>
                        </tr>
                    `;
                }
            }
        } catch (e) {
            console.error('Error in applyFilter:', e);
        }
    }

    function exportTableToCSV(filename) {
        const dd = String(selectedDay).padStart(2, '0');
        const mm = String(selectedMonth + 1).padStart(2, '0');
        const dateParam = `${dd}/${mm}/${selectedYear}`;
        window.location.href = `{{ route('laporan.export') }}?periode=${currentPeriod}&tanggal=${encodeURIComponent(dateParam)}`;
    }
</script>
@endsection
