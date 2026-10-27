@extends('layouts.admin')

@section('title', 'Data Menu')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Menu</h1>
            <p class="text-sm text-gray-500 mt-0.5">Kelola daftar menu yang dijual di King Es Teler</p>
        </div>

        <button type="button"
                id="btnTambahMenu"
                onclick="openMenuModal()"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0c3b2e] hover:bg-[#08281f] active:scale-98 text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-150 cursor-pointer whitespace-nowrap">
            <svg class="w-4 h-4 shrink-0 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Tambah Menu Baru</span>
        </button>
    </div>

    <!-- Menu Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200/80 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left border-collapse" id="menuTable">
                <thead>
                    <tr class="bg-gray-100/90 text-gray-800 font-bold border-b border-gray-200">
                        <th class="py-3.5 px-6 w-20 text-left">No</th>
                        <th class="py-3.5 px-6 text-left">Nama Menu</th>
                        <th class="py-3.5 px-6 w-48 text-left">Harga</th>
                        <th class="py-3.5 px-6 w-60 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-800" id="menuTableBody">
                    @forelse($menus as $index => $item)
                        <tr class="hover:bg-gray-50/80 transition-colors" data-id="{{ $item->id }}">
                            <td class="py-4 px-6 text-gray-700">{{ $index + 1 }}.</td>
                            <td class="py-4 px-6 font-medium text-gray-900">{{ $item->nama_menu }}</td>
                            <td class="py-4 px-6 font-medium text-gray-900">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button"
                                            onclick="openEditModal(this)"
                                            data-id="{{ $item->id }}"
                                            data-name="{{ $item->nama_menu }}"
                                            data-harga="{{ $item->harga }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#f59e0b] hover:bg-[#d97706] text-white text-xs font-bold rounded-full transition-all duration-150 shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span>Edit</span>
                                    </button>
                                    <button type="button"
                                            onclick="hapusMenu(this)"
                                            data-id="{{ $item->id }}"
                                            class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#ef4444] hover:bg-[#dc2626] text-white text-xs font-bold rounded-full transition-all duration-150 shadow-xs cursor-pointer">
                                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="emptyRow">
                            <td colspan="4" class="py-8 text-center text-gray-500 font-medium">Belum ada menu tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Tambah Menu -->
<div id="menuModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0c3b2e]">Tambah Menu Baru</h3>
            <button type="button" onclick="closeMenuModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none cursor-pointer">&times;</button>
        </div>
        <form id="formTambahMenu" onsubmit="simpanMenu(event)" class="mt-4 space-y-4">
            <div>
                <label for="inputNamaMenu" class="block text-xs font-bold text-gray-700 mb-1">Nama Menu</label>
                <input id="inputNamaMenu" type="text" placeholder="Contoh: Es Teler Alpukat Kocok" required
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" />
            </div>
            <div>
                <label for="inputHarga" class="block text-xs font-bold text-gray-700 mb-1">Harga (Rp)</label>
                <input id="inputHarga" type="number" placeholder="8000" min="0" required
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" />
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeMenuModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-[#0c3b2e] hover:bg-[#0a2e22] text-white font-bold text-sm rounded-lg cursor-pointer">
                    Simpan Menu
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Menu -->
<div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl" onclick="event.stopPropagation()">
        <div class="flex items-center justify-between pb-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-[#0c3b2e]">Edit Menu</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-2xl leading-none cursor-pointer">&times;</button>
        </div>
        <form id="formEditMenu" onsubmit="simpanEdit(event)" class="mt-4 space-y-4">
            <input type="hidden" id="editMenuId" />
            <div>
                <label for="editNamaMenu" class="block text-xs font-bold text-gray-700 mb-1">Nama Menu</label>
                <input id="editNamaMenu" type="text" required
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" />
            </div>
            <div>
                <label for="editHarga" class="block text-xs font-bold text-gray-700 mb-1">Harga (Rp)</label>
                <input id="editHarga" type="number" min="0" required
                       class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-[#0c3b2e] focus:outline-none" />
            </div>
            <div class="pt-2 flex justify-end gap-2">
                <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg cursor-pointer">
                    Batal
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm rounded-lg cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notifikasi -->
<div id="toast" class="fixed bottom-6 right-6 z-[999] hidden">
    <div class="flex items-center gap-3 px-5 py-3.5 bg-[#0c3b2e] text-white text-sm font-semibold rounded-xl shadow-xl">
        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span id="toastMsg">Berhasil</span>
    </div>
</div>

<script>
    let editTargetRow = null;
    const csrfToken = '{{ csrf_token() }}';

    /* ── Helpers ─────────────────────────── */
    function formatRupiah(angka) {
        return 'Rp ' + parseInt(angka, 10).toLocaleString('id-ID');
    }

    function showToast(msg) {
        const el = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        el.classList.remove('hidden');
        setTimeout(() => el.classList.add('hidden'), 2500);
    }

    function renumberRows() {
        const rows = document.querySelectorAll('#menuTableBody tr[data-id]');
        rows.forEach((tr, i) => {
            tr.querySelector('td:first-child').textContent = (i + 1) + '.';
        });
    }

    /* ── Modal Tambah ─────────────────────── */
    function openMenuModal() {
        document.getElementById('menuModal').classList.remove('hidden');
        document.getElementById('inputNamaMenu').focus();
    }

    function closeMenuModal() {
        document.getElementById('menuModal').classList.add('hidden');
        document.getElementById('formTambahMenu').reset();
    }

    async function simpanMenu(e) {
        e.preventDefault();
        const nama  = document.getElementById('inputNamaMenu').value.trim();
        const harga = parseInt(document.getElementById('inputHarga').value, 10);

        try {
            const response = await fetch("{{ route('menu.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nama_menu: nama, harga: harga })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const tbody = document.getElementById('menuTableBody');
                const emptyRow = document.getElementById('emptyRow');
                if (emptyRow) emptyRow.remove();

                const newNo = tbody.querySelectorAll('tr[data-id]').length + 1;

                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50/80 transition-colors';
                tr.setAttribute('data-id', result.data.id);
                tr.innerHTML = `
                    <td class="py-4 px-6 text-gray-700">${newNo}.</td>
                    <td class="py-4 px-6 font-medium text-gray-900">${result.data.nama_menu}</td>
                    <td class="py-4 px-6 font-medium text-gray-900">${result.data.harga_formatted}</td>
                    <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <button type="button"
                                    onclick="openEditModal(this)"
                                    data-id="${result.data.id}"
                                    data-name="${result.data.nama_menu}"
                                    data-harga="${result.data.harga}"
                                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#f59e0b] hover:bg-[#d97706] text-white text-xs font-bold rounded-full transition-all duration-150 shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <span>Edit</span>
                            </button>
                            <button type="button"
                                    onclick="hapusMenu(this)"
                                    data-id="${result.data.id}"
                                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#ef4444] hover:bg-[#dc2626] text-white text-xs font-bold rounded-full transition-all duration-150 shadow-xs cursor-pointer">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </div>
                    </td>`;
                tbody.appendChild(tr);
                closeMenuModal();
                showToast(result.message || 'Menu berhasil ditambahkan!');
            } else {
                alert(result.message || 'Gagal menambahkan menu.');
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    /* ── Modal Edit ───────────────────────── */
    function openEditModal(btn) {
        editTargetRow = btn.closest('tr');
        document.getElementById('editMenuId').value   = btn.dataset.id;
        document.getElementById('editNamaMenu').value = btn.dataset.name;
        document.getElementById('editHarga').value    = btn.dataset.harga;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editNamaMenu').focus();
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        editTargetRow = null;
    }

    async function simpanEdit(e) {
        e.preventDefault();
        if (!editTargetRow) { return; }

        const id    = document.getElementById('editMenuId').value;
        const nama  = document.getElementById('editNamaMenu').value.trim();
        const harga = parseInt(document.getElementById('editHarga').value, 10);

        try {
            const response = await fetch(`/kelola-menu/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ nama_menu: nama, harga: harga })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const cells = editTargetRow.querySelectorAll('td');
                cells[1].textContent = result.data.nama_menu;
                cells[2].textContent = result.data.harga_formatted;

                // Update data attributes on edit button
                const editBtn = editTargetRow.querySelector('button[onclick^="openEditModal"]');
                if (editBtn) {
                    editBtn.dataset.name  = result.data.nama_menu;
                    editBtn.dataset.harga = result.data.harga;
                }

                closeEditModal();
                showToast(result.message || 'Menu berhasil diperbarui!');
            } else {
                alert(result.message || 'Gagal memperbarui menu.');
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    /* ── Hapus ────────────────────────────── */
    async function hapusMenu(btn) {
        const id = btn.dataset.id;
        if (!confirm('Yakin ingin menghapus menu ini?')) { return; }

        try {
            const response = await fetch(`/kelola-menu/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                btn.closest('tr').remove();
                renumberRows();
                showToast(result.message || 'Menu berhasil dihapus!');
            } else {
                alert(result.message || 'Gagal menghapus menu.');
            }
        } catch (err) {
            alert('Terjadi kesalahan koneksi.');
        }
    }

    /* ── Close modal on backdrop click ────── */
    document.getElementById('menuModal').addEventListener('click', closeMenuModal);
    document.getElementById('editModal').addEventListener('click', closeEditModal);
</script>
@endsection
