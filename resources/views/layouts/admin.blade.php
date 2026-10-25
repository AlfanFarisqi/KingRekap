<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - King Rekap | King Es Teler</title>
    <meta name="description" content="Sistem Pengelolaan Penjualan & Prediksi Kemasan King Es Teler">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        /* Print Styles: Hide navigation, header & expand body */
        @media print {
            #mainSidebar,
            aside,
            header,
            #mobileBackdrop,
            .no-print {
                display: none !important;
            }
            body {
                background: #ffffff !important;
                color: #000000 !important;
                min-height: auto !important;
            }
            .flex-1.flex.flex-col {
                padding-left: 0 !important;
                margin-left: 0 !important;
            }
            div[class*="pl-"] {
                padding-left: 0 !important;
            }
            div[class*="md:pl-"] {
                padding-left: 0 !important;
            }
            main {
                padding: 0.5rem !important;
                margin: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f8fafc] text-gray-800 min-h-screen flex antialiased selection:bg-[#0e3c2f] selection:text-white">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="mobileBackdrop" 
         onclick="toggleMobileSidebar()" 
         class="fixed inset-0 z-40 bg-black/50 backdrop-blur-xs transition-opacity duration-300 opacity-0 pointer-events-none md:hidden">
    </div>

    <!-- Sidebar Container -->
    <aside id="mainSidebar" 
           class="fixed inset-y-0 left-0 z-50 w-60 md:w-64 bg-[#0c3b2e] flex flex-col transition-transform duration-300 -translate-x-full md:translate-x-0 shadow-2xl md:shadow-none shrink-0">
        
        <!-- Sidebar Header: Logo King Rekap -->
        <div class="h-16 px-5 flex items-center justify-center relative border-b border-white/10 shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 transition-transform hover:scale-102">
                <img src="{{ asset('images/logo.png') }}" 
                     alt="King Rekap" 
                     class="h-8 sm:h-9 w-auto object-contain select-none drop-shadow">
            </a>
            
            <!-- Mobile Close Button (absolute so it doesn't shift logo from center) -->
            <button type="button" 
                    onclick="toggleMobileSidebar()" 
                    class="md:hidden absolute right-2 top-1/2 -translate-y-1/2 text-white/80 hover:text-white p-1.5 rounded-lg hover:bg-white/10 focus:outline-none"
                    aria-label="Tutup Menu">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Sidebar Navigation List -->
        <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
            
            <!-- 1. Dashboard -->
            <a href="{{ route('dashboard') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all duration-150 {{ request()->routeIs('dashboard') ? 'border-2 border-[#10b981] bg-[#10b981]/15 text-white font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-semibold' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('dashboard') ? 'text-[#34d399]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <!-- 2. Kelola Menu -->
            <a href="{{ route('menu.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all duration-150 {{ request()->routeIs('menu.*') ? 'border-2 border-[#10b981] bg-[#10b981]/15 text-white font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-semibold' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('menu.*') ? 'text-[#34d399]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 6h.01M4 12h.01M4 18h.01M8 6h12M8 12h12M8 18h12"/>
                </svg>
                <span>Kelola Menu</span>
            </a>

            <!-- 3. Data Penjualan -->
            <a href="{{ route('penjualan.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all duration-150 {{ request()->routeIs('penjualan.*') ? 'border-2 border-[#10b981] bg-[#10b981]/15 text-white font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-semibold' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('penjualan.*') ? 'text-[#34d399]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>Data Penjualan</span>
            </a>

            <!-- 4. Laporan Penjualan -->
            <a href="{{ route('laporan.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all duration-150 {{ request()->routeIs('laporan.*') ? 'border-2 border-[#10b981] bg-[#10b981]/15 text-white font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-semibold' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('laporan.*') ? 'text-[#34d399]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Laporan Penjualan</span>
            </a>

            <!-- 5. Prediksi Kemasan -->
            <a href="{{ route('prediksi.index') }}" 
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-sm transition-all duration-150 {{ request()->routeIs('prediksi.*') ? 'border-2 border-[#10b981] bg-[#10b981]/15 text-white font-bold shadow-sm' : 'text-white/85 hover:text-white hover:bg-white/10 font-semibold' }}">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('prediksi.*') ? 'text-[#34d399]' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>Prediksi Kemasan</span>
            </a>
        </nav>

        <!-- Sidebar Footer: King Es Teler Info -->
        <div class="p-3.5 border-t border-white/10 text-center shrink-0">
            <p class="text-white/60 text-xs font-medium">King Es Teler v1.0</p>
            <p class="text-white/40 text-[11px] mt-0.5">Sistem Pengelolaan &amp; Rekap</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col md:pl-60 lg:pl-64 min-w-0 min-h-screen">
        
        <!-- Header / Top Navbar (Matches User Design Screenshot) -->
        <header class="sticky top-0 z-30 h-14 sm:h-16 bg-white border-b border-gray-200/80 px-4 sm:px-6 flex items-center justify-between shrink-0 shadow-xs">
            
            <!-- Left: Mobile Menu Toggle & Realtime Date Indicator (Pojok Kiri Setara Logout) -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <button type="button" 
                        onclick="toggleMobileSidebar()" 
                        class="md:hidden text-gray-700 hover:text-gray-900 p-2 -ml-2 rounded-lg hover:bg-gray-100 focus:outline-none cursor-pointer"
                        aria-label="Buka Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Realtime Date Badge -->
                <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50/90 hover:bg-gray-100 rounded-lg border border-gray-200/80 text-xs font-semibold text-gray-700 shadow-2xs transition-colors">
                    <svg class="w-4 h-4 text-emerald-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span id="realtimeHeaderDate" class="font-bold text-gray-800">
                        {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </span>
                </div>
            </div>

            <!-- Right: Admin Profile and Log Out Button -->
            <div class="flex items-center gap-3">
                <!-- User Profile Info -->
                <div class="flex items-center gap-2 text-gray-900">
                    <div class="w-8 h-8 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <span class="font-bold text-sm text-gray-800">Admin</span>
                </div>

                <!-- Log Out Button -->
                <a href="{{ route('login') }}" 
                   class="px-3.5 sm:px-4 py-1.5 bg-[#e2e8f0] hover:bg-[#cbd5e1] active:bg-[#94a3b8] text-gray-800 text-xs sm:text-sm font-semibold rounded-lg transition-all duration-150 shadow-xs cursor-pointer">
                    Log Out
                </a>
            </div>
        </header>

        <!-- Main Body Page Container -->
        <main class="flex-1 p-4 sm:p-5 md:p-6 w-full max-w-[1360px] mx-auto">
            @yield('content')
        </main>
    </div>

    <!-- Toggle Mobile Sidebar Script -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            const backdrop = document.getElementById('mobileBackdrop');
            const isOpen = !sidebar.classList.contains('-translate-x-full');

            if (isOpen) {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-100');
            } else {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
            }
        }

        // Sinkronisasi realtime tanggal hari ini format Indonesia
        function updateRealtimeHeaderDate() {
            const dateEl = document.getElementById('realtimeHeaderDate');
            if (dateEl) {
                try {
                    const now = new Date();
                    const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' };
                    dateEl.textContent = now.toLocaleDateString('id-ID', options);
                } catch (e) {
                    // Fallback to PHP rendered date
                }
            }
        }
        document.addEventListener('DOMContentLoaded', updateRealtimeHeaderDate);
    </script>
    @stack('scripts')
</body>
</html>
