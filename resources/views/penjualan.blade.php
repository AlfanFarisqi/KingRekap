@extends('layouts.admin')

@section('title', 'Data Penjualan')

@section('content')
<div class="space-y-6 max-w-6xl">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-gray-200/80">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-[#0c3b2e] tracking-tight">
                Data Penjualan
            </h1>
            <p class="text-sm sm:text-base text-gray-600 mt-1">
                Catat pesanan penjualan harian kasir King Es Teler dan lihat histori transaksi.
            </p>
        </div>
    </div>

    <!-- Form Input Card -->
    <div id="inputFormCard" class="bg-white rounded-2xl p-6 border border-gray-200 shadow-sm">
        <h2 class="text-lg font-extrabold text-[#0c3b2e] mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Formulir Pencatatan Penjualan</span>
        </h2>

        <form id="salesForm" onsubmit="handleNewSale(event)" class="flex flex-wrap lg:flex-nowrap items-end gap-4">
            <!-- Varian Menu (terlebar, grow) -->
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Varian Menu</label>
                <select id="menuSelect" onchange="updatePriceCalculation()" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none cursor-pointer">
                    <option value="8000" data-name="Es Teler Original">Es Teler Original - Rp 8.000</option>
                    <option value="10000" data-name="Es Teler Keju">Es Teler Keju - Rp 10.000</option>
                    <option value="12000" data-name="Es Teler Keju Special">Es Teler Keju Special - Rp 12.000</option>
                </select>
            </div>

            <!-- Jumlah Porsi (lebar tetap) -->
            <div class="w-32 shrink-0">
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Jumlah Porsi</label>
                <input type="number" id="qtyInput" min="1" value="1" oninput="updatePriceCalculation()" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-bold text-gray-900 focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" required />
            </div>

            <!-- Tanggal Transaksi (kalender custom seperti laporan) -->
            <div class="shrink-0">
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Transaksi</label>
                <div class="relative" id="penjualanCalendarContainer">
                    <div class="relative flex items-center">
                        <input type="text"
                               id="penjualanFilterDate"
                               readonly
                               onclick="penjualanToggleCalendar()"
                               class="w-44 pl-4 pr-11 py-2.5 bg-[#dbe2ea] border border-gray-400 rounded-lg text-gray-900 font-bold text-sm focus:outline-none focus:ring-2 focus:ring-[#0c3b2e] cursor-pointer truncate" />
                        <button type="button"
                                onclick="penjualanToggleCalendar()"
                                class="absolute right-2.5 top-1/2 -translate-y-1/2 p-1 text-gray-700 hover:text-[#0c3b2e] transition-colors cursor-pointer"
                                title="Buka Kalender Tanggal">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Dropdown Kalender Interaktif -->
                    <div id="penjualanCalendarDropdown"
                         class="hidden absolute left-0 top-full mt-2 z-50 bg-white rounded-2xl shadow-2xl border border-gray-200 p-4 w-72 sm:w-80 select-none">

                        <!-- Header Bulan & Tahun Navigasi -->
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                            <button type="button"
                                    onclick="penjualanChangeCalendarMonth(-1)"
                                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-black cursor-pointer transition-colors"
                                    title="Bulan Sebelumnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <span id="penjualanCalendarMonthYear" class="text-sm font-extrabold text-[#0c3b2e]"></span>
                            <button type="button"
                                    onclick="penjualanChangeCalendarMonth(1)"
                                    class="p-1.5 rounded-lg text-gray-600 hover:bg-gray-100 hover:text-black cursor-pointer transition-colors"
                                    title="Bulan Berikutnya">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>

                        <!-- Header Hari (Min - Sab) -->
                        <div class="grid grid-cols-7 gap-1 pt-3 pb-2 text-center text-xs font-bold text-gray-400">
                            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
                        </div>

                        <!-- Grid Tanggal Kalender -->
                        <div id="penjualanCalendarDaysGrid" class="grid grid-cols-7 gap-1 text-center text-xs sm:text-sm font-semibold">
                            <!-- Diisi otomatis via JavaScript -->
                        </div>

                        <!-- Footer Kalender -->
                        <div class="pt-3 mt-3 border-t border-gray-100 flex items-center justify-end">
                            <button type="button"
                                    onclick="penjualanSelectToday()"
                                    class="font-bold text-emerald-700 hover:underline cursor-pointer text-xs mr-auto">
                                Hari Ini
                            </button>
                            <button type="button"
                                    onclick="penjualanCloseCalendar()"
                                    class="px-2.5 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-md cursor-pointer transition-colors text-xs">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Simpan -->
            <div class="shrink-0">
                <button type="submit" class="py-2.5 px-5 bg-[#0c3b2e] hover:bg-[#08281f] text-white font-bold text-sm rounded-lg shadow-sm transition-all cursor-pointer flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Simpan Transaksi</span>
                </button>
            </div>
        </form>

        <!-- Live info perhitungan otomatis -->
        <div class="mt-4 pt-3.5 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-xs">
            <span class="text-gray-500">Perhitungan otomatis: <strong id="calcFormula" class="text-gray-800 font-semibold">1 Porsi × Rp 8.000</strong></span>
            <div class="inline-flex items-center gap-1.5 font-bold text-emerald-800 bg-emerald-50 px-3 py-1 rounded-md border border-emerald-200/80 self-start sm:self-auto">
                <span>Total Otomatis:</span>
                <span id="autoTotalPreview" class="text-[#0c3b2e] font-extrabold text-sm">Rp 8.000</span>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="p-4 sm:px-6 bg-gray-50/80 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-extrabold text-base text-gray-900">Riwayat Transaksi Penjualan Hari Ini</h3>
                <p class="text-xs text-gray-500 mt-0.5">Total harga dihitung otomatis dari jumlah porsi dikalikan harga menu</p>
            </div>
            <div id="summaryBadge" class="text-xs font-bold text-emerald-800 bg-emerald-50 px-3.5 py-1.5 rounded-lg border border-emerald-200 shrink-0">
                Total Hari Ini: 41 Porsi (Rp 416.000)
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm" id="salesTable">
                <thead>
                    <tr class="bg-gray-100/90 text-gray-800 font-bold border-b border-gray-200">
                        <th class="py-3.5 px-4 text-center w-16">No</th>
                        <th class="py-3.5 px-4 text-center w-32">Tanggal</th>
                        <th class="py-3.5 px-6">Nama Menu</th>
                        <th class="py-3.5 px-6 text-center w-32">Jumlah</th>
                        <th class="py-3.5 px-6 text-right w-44">Total Harga</th>
                        <th class="py-3.5 px-4 text-center w-28">Status</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody" class="divide-y divide-gray-100 text-gray-900">
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-700">1</td>
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-900">Es Teler Keju Special</td>
                        <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="12">12 Porsi</td>
                        <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="144000">Rp 144.000</td>
                        <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-700">2</td>
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-900">Es Teler Original</td>
                        <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="8">8 Porsi</td>
                        <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="64000">Rp 64.000</td>
                        <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-700">3</td>
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-900">Es Teler Keju</td>
                        <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="10">10 Porsi</td>
                        <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="100000">Rp 100.000</td>
                        <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-700">4</td>
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-900">Es Teler Keju Special</td>
                        <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="5">5 Porsi</td>
                        <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="60000">Rp 60.000</td>
                        <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
                    </tr>
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-center font-bold text-gray-700">5</td>
                        <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</td>
                        <td class="py-3.5 px-6 font-semibold text-gray-900">Es Teler Original</td>
                        <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="6">6 Porsi</td>
                        <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="48000">Rp 48.000</td>
                        <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Toast Notifikasi -->
<div id="toast" class="fixed bottom-6 right-6 z-[999] hidden">
    <div class="flex items-center gap-3 px-5 py-3.5 bg-[#0c3b2e] text-white text-sm font-semibold rounded-xl shadow-xl">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="toastMsg">Transaksi berhasil dicatat!</span>
    </div>
</div>

<script>
    function formatRupiah(num) {
        return 'Rp ' + parseInt(num, 10).toLocaleString('id-ID');
    }

    function getRealtimeDateString(date = new Date()) {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const d = date.getDate();
        const m = months[date.getMonth()];
        const y = date.getFullYear();
        return `${d} ${m} ${y}`;
    }

    // =============================================
    // KALENDER PENJUALAN (sama seperti di laporan)
    // =============================================
    const penjualanMonthNamesIndo = [
        'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];
    const penjualanMonthShortIndo = [
        'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'
    ];

    let penjualanCalYear  = new Date().getFullYear();
    let penjualanCalMonth = new Date().getMonth();
    let penjualanSelDay   = new Date().getDate();
    let penjualanSelMonth = new Date().getMonth();
    let penjualanSelYear  = new Date().getFullYear();

    function penjualanToggleCalendar() {
        const dropdown = document.getElementById('penjualanCalendarDropdown');
        if (dropdown.classList.contains('hidden')) {
            penjualanRenderCalendar();
            dropdown.classList.remove('hidden');
        } else {
            dropdown.classList.add('hidden');
        }
    }

    function penjualanCloseCalendar() {
        const dropdown = document.getElementById('penjualanCalendarDropdown');
        if (dropdown) { dropdown.classList.add('hidden'); }
    }

    function penjualanChangeCalendarMonth(delta) {
        penjualanCalMonth += delta;
        if (penjualanCalMonth < 0)  { penjualanCalMonth = 11; penjualanCalYear--; }
        if (penjualanCalMonth > 11) { penjualanCalMonth = 0;  penjualanCalYear++; }
        penjualanRenderCalendar();
    }

    function penjualanRenderCalendar() {
        const titleEl = document.getElementById('penjualanCalendarMonthYear');
        const gridEl  = document.getElementById('penjualanCalendarDaysGrid');
        titleEl.textContent = penjualanMonthNamesIndo[penjualanCalMonth] + ' ' + penjualanCalYear;
        gridEl.innerHTML = '';

        const firstDayIndex  = new Date(penjualanCalYear, penjualanCalMonth, 1).getDay();
        const totalDays      = new Date(penjualanCalYear, penjualanCalMonth + 1, 0).getDate();
        const prevMonthTotal = new Date(penjualanCalYear, penjualanCalMonth, 0).getDate();

        for (let i = firstDayIndex - 1; i >= 0; i--) {
            const el = document.createElement('div');
            el.className = 'py-2 text-gray-300 text-xs cursor-default select-none';
            el.textContent = prevMonthTotal - i;
            gridEl.appendChild(el);
        }
        for (let d = 1; d <= totalDays; d++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.textContent = d;
            const isSel = (d === penjualanSelDay && penjualanCalMonth === penjualanSelMonth && penjualanCalYear === penjualanSelYear);
            btn.className = isSel
                ? 'py-2 rounded-lg bg-[#0c3b2e] text-white font-bold text-xs sm:text-sm shadow-xs cursor-pointer transition-transform hover:scale-105'
                : 'py-2 rounded-lg text-gray-800 hover:bg-emerald-50 hover:text-[#0c3b2e] font-medium text-xs sm:text-sm cursor-pointer transition-colors';
            btn.onclick = () => penjualanSelectDate(d, penjualanCalMonth, penjualanCalYear);
            gridEl.appendChild(btn);
        }
    }

    function penjualanSelectDate(day, month, year) {
        penjualanSelDay   = day;
        penjualanSelMonth = month;
        penjualanSelYear  = year;
        document.getElementById('penjualanFilterDate').value =
            `${String(day).padStart(2,'0')}/${String(month+1).padStart(2,'0')}/${year}`;
        penjualanCloseCalendar();
    }

    function penjualanSelectToday() {
        const now = new Date();
        penjualanCalYear  = now.getFullYear();
        penjualanCalMonth = now.getMonth();
        penjualanSelectDate(now.getDate(), now.getMonth(), now.getFullYear());
    }

    function penjualanGetSelectedDateStr() {
        return `${penjualanSelDay} ${penjualanMonthShortIndo[penjualanSelMonth]} ${penjualanSelYear}`;
    }

    // Tutup kalender kalau klik di luar
    document.addEventListener('click', function(event) {
        const container = document.getElementById('penjualanCalendarContainer');
        if (container && !container.contains(event.target)) {
            penjualanCloseCalendar();
        }
    });

    function showToast(msg) {
        const el = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2500);
    }

    function updatePriceCalculation() {
        const select = document.getElementById('menuSelect');
        const unitPrice = parseInt(select.value, 10) || 0;
        const qty = parseInt(document.getElementById('qtyInput').value, 10) || 0;
        const total = unitPrice * qty;

        const formulaEl = document.getElementById('calcFormula');
        const previewEl = document.getElementById('autoTotalPreview');
        if (formulaEl) {
            formulaEl.textContent = `${qty} Porsi × ${formatRupiah(unitPrice)}`;
        }
        if (previewEl) {
            previewEl.textContent = formatRupiah(total);
        }
    }

    function renumberAndRecalculate() {
        const tbody = document.getElementById('salesTableBody');
        const rows = tbody.querySelectorAll('tr');
        let totalPorsi = 0;
        let totalRevenue = 0;

        rows.forEach((tr, index) => {
            const noCell = tr.querySelector('td:first-child');
            if (noCell) {
                noCell.textContent = index + 1;
            }
            const qtyCell = tr.querySelector('td[data-qty]');
            const totalCell = tr.querySelector('td[data-total]');
            if (qtyCell) {
                totalPorsi += parseInt(qtyCell.dataset.qty, 10) || 0;
            }
            if (totalCell) {
                totalRevenue += parseInt(totalCell.dataset.total, 10) || 0;
            }
        });

        const badge = document.getElementById('summaryBadge');
        if (badge) {
            badge.textContent = `Total Hari Ini: ${totalPorsi} Porsi (${formatRupiah(totalRevenue)})`;
        }
    }

    function handleNewSale(e) {
        e.preventDefault();
        const select = document.getElementById('menuSelect');
        const menuName = select.options[select.selectedIndex].getAttribute('data-name');
        const unitPrice = parseInt(select.value, 10);
        const qty = parseInt(document.getElementById('qtyInput').value, 10) || 1;
        
        // HITUNGAN OTOMATIS: Jumlah Menu Terjual dikalikan dengan Harganya di Menu
        const total = unitPrice * qty;

        // Ambil tanggal dari kalender penjualan
        const tanggalStr = penjualanGetSelectedDateStr();

        const tbody = document.getElementById('salesTableBody');
        const tr = document.createElement('tr');
        tr.className = "hover:bg-gray-50/80 bg-emerald-50/40 transition-colors animate-fade-in";
        tr.innerHTML = `
            <td class="py-3.5 px-4 text-center font-bold text-gray-700">1</td>
            <td class="py-3.5 px-4 text-center text-xs font-semibold text-emerald-800 date-cell">${tanggalStr}</td>
            <td class="py-3.5 px-6 font-semibold text-gray-900">${menuName}</td>
            <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="${qty}">${qty} Porsi</td>
            <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="${total}">${formatRupiah(total)}</td>
            <td class="py-3.5 px-4 text-center"><span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-800 rounded-md">Sukses</span></td>
        `;
        tbody.prepend(tr);

        renumberAndRecalculate();
        showToast(`Transaksi ${qty} Porsi ${menuName} (${formatRupiah(total)}) berhasil disimpan!`);

        document.getElementById('qtyInput').value = 1;
        updatePriceCalculation();
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        penjualanSelectToday();

        const currentDate = getRealtimeDateString();
        document.querySelectorAll('.date-cell').forEach(cell => {
            cell.textContent = currentDate;
        });
        updatePriceCalculation();
        renumberAndRecalculate();
    });
</script>
@endsection
