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
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" data-price="{{ $menu->harga }}" data-name="{{ $menu->nama_menu }}">{{ $menu->nama_menu }} - Rp {{ number_format($menu->harga, 0, ',', '.') }}</option>
                    @endforeach
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
                Total Hari Ini: {{ $totalPorsi }} Porsi (Rp {{ number_format($totalPendapatan, 0, ',', '.') }})
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
                        <th class="py-3.5 px-4 text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody id="salesTableBody" class="divide-y divide-gray-100 text-gray-900">
                    @forelse($penjualanHariIni as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="py-3.5 px-4 text-center font-bold text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3.5 px-4 text-center text-xs font-semibold text-gray-600 date-cell">{{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->isoFormat('D MMM Y') }}</td>
                            <td class="py-3.5 px-6 font-semibold text-gray-900">{{ $item->menu->nama_menu ?? $item->nama_menu }}</td>
                            <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="{{ $item->jumlah }}">{{ $item->jumlah }} Porsi</td>
                            <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="{{ $item->total_harga }}">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <button onclick="openEditModal({{ $item->id }}, {{ $item->menu_id }}, {{ $item->jumlah }}, '{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}')"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-lg border border-amber-200 transition-colors cursor-pointer"
                                        title="Edit Transaksi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </button>
                                    <button onclick="confirmDelete({{ $item->id }}, this)"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg border border-red-200 transition-colors cursor-pointer"
                                        title="Hapus Transaksi">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptySalesRow">
                            <td colspan="6" class="py-8 text-center text-gray-500 font-medium">Belum ada transaksi pada tanggal ini.</td>
                        </tr>
                    @endforelse
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

<!-- Modal Edit Transaksi -->
<div id="editModal" class="fixed inset-0 z-[1000] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeEditModal()"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 sm:p-8 z-10">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-lg font-extrabold text-[#0c3b2e]">Edit Transaksi</h3>
            <button onclick="closeEditModal()" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-500 transition-colors cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="editForm" onsubmit="handleEditSubmit(event)" class="space-y-4">
            <input type="hidden" id="editId" />

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Varian Menu</label>
                <select id="editMenuSelect" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold text-gray-800 focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none">
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}" data-price="{{ $menu->harga }}">{{ $menu->nama_menu }} - Rp {{ number_format($menu->harga, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Jumlah Porsi</label>
                <input type="number" id="editQty" min="1" value="1" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-bold focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" required />
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 mb-1.5">Tanggal Transaksi</label>
                <input type="text" id="editTanggal" placeholder="dd/mm/yyyy" class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-300 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" required />
            </div>

            <div class="flex gap-3 pt-2">
                <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-sm rounded-lg transition-colors cursor-pointer">
                    Batal
                </button>
                <button type="submit" id="editSubmitBtn" class="flex-1 py-2.5 px-4 bg-[#0c3b2e] hover:bg-[#08281f] text-white font-bold text-sm rounded-lg transition-colors cursor-pointer flex items-center justify-center gap-2">
                    <span id="editBtnText">Simpan Perubahan</span>
                </button>
            </div>
        </form>
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
        if (!select || !select.options || select.selectedIndex < 0) return;
        const selectedOption = select.options[select.selectedIndex];
        const unitPrice = selectedOption ? (parseInt(selectedOption.getAttribute('data-price') || selectedOption.value, 10) || 0) : 0;
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
        const rows = tbody.querySelectorAll('tr:not(#emptySalesRow)');
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

    async function handleNewSale(e) {
        e.preventDefault();
        const select = document.getElementById('menuSelect');
        if (!select || select.selectedIndex < 0) return;
        const selectedOption = select.options[select.selectedIndex];
        const menuId = select.value;
        const qty = parseInt(document.getElementById('qtyInput').value, 10) || 1;
        const tanggalStr = document.getElementById('penjualanFilterDate').value;

        try {
            const response = await fetch("{{ route('penjualan.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    menu_id: menuId,
                    jumlah: qty,
                    tanggal: tanggalStr
                })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const tbody = document.getElementById('salesTableBody');
                const emptyRow = document.getElementById('emptySalesRow');
                if (emptyRow) emptyRow.remove();

                const tr = document.createElement('tr');
                tr.className = "hover:bg-gray-50/80 bg-emerald-50/40 transition-colors animate-fade-in";
                tr.innerHTML = `
                    <td class="py-3.5 px-4 text-center font-bold text-gray-700">1</td>
                    <td class="py-3.5 px-4 text-center text-xs font-semibold text-emerald-800 date-cell">${result.data.tanggal_formatted}</td>
                    <td class="py-3.5 px-6 font-semibold text-gray-900">${result.data.nama_menu}</td>
                    <td class="py-3.5 px-6 text-center font-bold text-gray-800" data-qty="${result.data.jumlah}">${result.data.jumlah} Porsi</td>
                    <td class="py-3.5 px-6 text-right font-extrabold text-[#0c3b2e]" data-total="${result.data.total_harga}">${result.data.total_harga_formatted}</td>
                    <td class="py-3.5 px-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <button onclick="openEditModal(${result.data.id}, ${result.data.menu_id}, ${result.data.jumlah}, '${result.data.tanggal_dmY}')"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-amber-50 hover:bg-amber-100 text-amber-700 font-bold text-xs rounded-lg border border-amber-200 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit
                            </button>
                            <button onclick="confirmDelete(${result.data.id}, this)"
                                class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 font-bold text-xs rounded-lg border border-red-200 transition-colors cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus
                            </button>
                        </div>
                    </td>
                `;
                tbody.prepend(tr);

                renumberAndRecalculate();
                showToast(result.message);

                document.getElementById('qtyInput').value = 1;
                updatePriceCalculation();
            } else {
                alert(result.message || 'Gagal menyimpan transaksi.');
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    // =============================================
    // EDIT & DELETE
    // =============================================
    let currentEditRow = null;

    function openEditModal(id, menuId, jumlah, tanggal) {
        document.getElementById('editId').value = id;
        document.getElementById('editQty').value = jumlah;
        document.getElementById('editTanggal').value = tanggal;

        const select = document.getElementById('editMenuSelect');
        for (let i = 0; i < select.options.length; i++) {
            if (parseInt(select.options[i].value) === parseInt(menuId)) {
                select.selectedIndex = i;
                break;
            }
        }

        document.getElementById('editModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        currentEditRow = null;
    }

    async function handleEditSubmit(e) {
        e.preventDefault();
        const id      = document.getElementById('editId').value;
        const menuId  = document.getElementById('editMenuSelect').value;
        const jumlah  = document.getElementById('editQty').value;
        const tanggal = document.getElementById('editTanggal').value;

        const btn     = document.getElementById('editSubmitBtn');
        const btnText = document.getElementById('editBtnText');
        btn.disabled  = true;
        btnText.textContent = 'Menyimpan...';

        try {
            const response = await fetch(`/data-penjualan/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ menu_id: menuId, jumlah: jumlah, tanggal: tanggal })
            });

            const result = await response.json();
            btn.disabled = false;
            btnText.textContent = 'Simpan Perubahan';

            if (response.ok && result.success) {
                closeEditModal();
                showToast(result.message);
                // Reload halaman agar data terbaru tampil
                setTimeout(() => window.location.reload(), 800);
            } else {
                alert(result.message || 'Gagal memperbarui transaksi.');
            }
        } catch (err) {
            btn.disabled = false;
            btnText.textContent = 'Simpan Perubahan';
            alert('Terjadi kesalahan koneksi.');
        }
    }

    async function confirmDelete(id, btn) {
        if (!confirm('Yakin ingin menghapus transaksi ini?')) return;

        const row = btn.closest('tr');
        btn.disabled = true;
        btn.textContent = '...';

        try {
            const response = await fetch(`/data-penjualan/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                row.remove();
                const tbody = document.getElementById('salesTableBody');
                if (tbody.querySelectorAll('tr:not(#emptySalesRow)').length === 0) {
                    tbody.innerHTML = `<tr id="emptySalesRow"><td colspan="6" class="py-8 text-center text-gray-500 font-medium">Belum ada transaksi pada tanggal ini.</td></tr>`;
                }
                renumberAndRecalculate();
                showToast(result.message);
            } else {
                btn.disabled = false;
                alert(result.message || 'Gagal menghapus transaksi.');
            }
        } catch (err) {
            btn.disabled = false;
            alert('Terjadi kesalahan koneksi.');
        }
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        penjualanSelectToday();
        updatePriceCalculation();
        renumberAndRecalculate();
    });
</script>
@endsection
