<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - King Rekap | King Es Teler</title>
    <meta name="description" content="Halaman login Sistem Pengelolaan Penjualan & Prediksi Kemasan King Es Teler">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        ::-webkit-scrollbar {
            width: 6px;
        }
        
    </style>
</head>
<body class="min-h-screen w-full m-0 p-0 overflow-x-hidden selection:bg-[#0c3b2e] selection:text-white">

    <!-- Background Container -->
    <div class="relative min-h-screen w-full flex flex-col justify-center items-center py-10 px-4 sm:px-6 lg:px-8 bg-[#d8ebd9] bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/background.jpg') }}');">
        

        <!-- Main Wrapper -->
        <div class="relative z-10 w-full max-w-[490px] flex flex-col items-center">
            
            <!-- Header: Logo & Title -->
            <header class="text-center mb-6 w-full animate-fade-in">
                <!-- Logo King Rekap -->
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('images/logo-green.png') }}"
                         alt="Logo King Rekap"
                         class="h-14 sm:h-16 md:h-18 w-auto object-contain select-none transition-transform hover:scale-105 duration-200" />
                </div>
                
                <!-- Subtitle Title -->
                <h1 class="text-[#0c3b2e] font-extrabold text-[15px] sm:text-base md:text-[17px] leading-snug tracking-tight">
                    Sistem Pengelolaan Penjualan &amp; Prediksi Kemasan
                </h1>
                <h2 class="text-[#0c3b2e] font-extrabold text-[15px] sm:text-base md:text-[17px] leading-snug tracking-tight mt-0.5">
                    King Es Teler
                </h2>
            </header>

            <!-- Card Login -->
            <main class="w-full bg-white rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.09),0_4px_14px_-2px_rgba(0,0,0,0.04)] border border-gray-100/90 p-6 sm:p-8 md:p-9 transition-all">
                
                <form id="loginForm" method="POST" action="{{ route('login.post') }}" novalidate onsubmit="handleLogin(event)" class="space-y-4 sm:space-y-5">
                    @csrf
                    
                    <!-- Field: Username / Email -->
                    <div>
                        <label for="login_user" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Username / Email
                        </label>
                        <input type="text"
                               id="login_user"
                               name="username"
                               placeholder="Masukkan username atau email"
                               autocomplete="username"
                               required
                               class="w-full px-4 py-2.5 sm:py-3 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                        <p id="loginUserError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Field: Password -->
                    <div>
                        <label for="login_password" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="login_password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   autocomplete="current-password"
                                   required
                                   class="w-full px-4 py-2.5 sm:py-3 pr-11 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                            <button type="button"
                                    onclick="togglePasswordVisibility('login_password', 'eyeIconLogin')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0c3b2e] transition-colors p-1.5 focus:outline-none cursor-pointer"
                                    title="Lihat/Sembunyikan Password"
                                    aria-label="Lihat atau sembunyikan password">
                                <svg id="eyeIconLogin" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p id="loginPasswordError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Tombol Masuk -->
                    <div class="pt-2">
                        <button type="submit"
                                id="loginBtn"
                                class="w-full py-3 sm:py-3.5 px-4 bg-[#0e3c2f] hover:bg-[#0a2e22] active:scale-[0.99] text-white font-bold text-base sm:text-[17px] rounded-lg shadow-sm hover:shadow-md transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#0e3c2f] focus:ring-offset-2">
                            <span id="loginBtnText">Masuk</span>
                            <svg id="loginBtnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Footer: Belum punya akun? Daftar disini -->
                <div class="text-center mt-5 pt-1">
                    <p class="text-xs sm:text-sm text-gray-700">
                        Belum punya akun?
                        <a href="{{ route('register') }}"
                           class="text-[#00a8ff] hover:text-[#0284c7] font-semibold hover:underline transition-colors ml-1 focus:outline-none">
                            Daftar disini
                        </a>
                    </p>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Notifikasi Sukses Login (Front End Only Demo) -->
    <div id="loginSuccessModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs transition-opacity duration-200">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl transform transition-all scale-95 duration-200" id="loginModalCard">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-4">
                <svg class="h-9 w-9 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-[#0c3b2e] mb-1">Berhasil Masuk!</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-5 leading-relaxed">
                Selamat datang kembali, <span id="modalLoginUser" class="font-semibold text-gray-900"></span>! (Front End Demo).
            </p>

            <div class="flex flex-col gap-2">
                <a href="{{ route('dashboard') }}"
                   class="w-full py-2.5 px-4 bg-[#0e3c2f] hover:bg-[#0a2e22] text-white font-bold text-sm rounded-lg transition-colors cursor-pointer text-center">
                    Masuk ke Dashboard &rarr;
                </a>
                <a href="{{ route('register') }}"
                   class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition-colors cursor-pointer text-center">
                    Buka Halaman Daftar
                </a>
            </div>
        </div>
    </div>

    <!-- Frontend Interactive Script -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        async function handleLogin(event) {
            event.preventDefault();
            const user = document.getElementById('login_user').value.trim();
            const pass = document.getElementById('login_password').value;
            
            let hasError = false;
            document.getElementById('loginUserError').classList.add('hidden');
            document.getElementById('loginPasswordError').classList.add('hidden');

            if (!user) {
                document.getElementById('loginUserError').textContent = 'Username atau Email wajib diisi.';
                document.getElementById('loginUserError').classList.remove('hidden');
                hasError = true;
            }

            if (!pass) {
                document.getElementById('loginPasswordError').textContent = 'Password wajib diisi.';
                document.getElementById('loginPasswordError').classList.remove('hidden');
                hasError = true;
            }

            if (hasError) return;

            const btn = document.getElementById('loginBtn');
            const btnText = document.getElementById('loginBtnText');
            const btnSpinner = document.getElementById('loginBtnSpinner');

            btn.disabled = true;
            btnText.textContent = 'Memverifikasi...';
            btnSpinner.classList.remove('hidden');

            try {
                const token = document.querySelector('input[name="_token"]')?.value;
                const response = await fetch("{{ route('login.post') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify({
                        username: user,
                        password: pass
                    })
                });

                const result = await response.json();

                btn.disabled = false;
                btnText.textContent = 'Masuk';
                btnSpinner.classList.add('hidden');

                if (response.ok && result.success) {
                    document.getElementById('modalLoginUser').textContent = result.user?.name || user;
                    const modal = document.getElementById('loginSuccessModal');
                    const modalCard = document.getElementById('loginModalCard');
                    modal.classList.remove('hidden');
                    setTimeout(() => {
                        modalCard.classList.remove('scale-95');
                        modalCard.classList.add('scale-100');
                    }, 10);
                } else {
                    document.getElementById('loginPasswordError').textContent = result.message || 'Username/email atau password salah.';
                    document.getElementById('loginPasswordError').classList.remove('hidden');
                }
            } catch (err) {
                btn.disabled = false;
                btnText.textContent = 'Masuk';
                btnSpinner.classList.add('hidden');
                document.getElementById('loginPasswordError').textContent = 'Terjadi kesalahan saat memproses login.';
                document.getElementById('loginPasswordError').classList.remove('hidden');
            }
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginSuccessModal');
            const modalCard = document.getElementById('loginModalCard');
            modalCard.classList.remove('scale-100');
            modalCard.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        document.getElementById('loginSuccessModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoginModal();
            }
        });
    </script>
</body>
</html>
