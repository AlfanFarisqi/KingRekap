<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - King Rekap | King Es Teler</title>
    <meta name="description" content="Halaman pendaftaran Sistem Pengelolaan Penjualan & Prediksi Kemasan King Es Teler">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom smooth scrollbar if needed */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
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

            <!-- Card Pendaftaran -->
            <main class="w-full bg-white rounded-2xl shadow-[0_12px_36px_-6px_rgba(0,0,0,0.09),0_4px_14px_-2px_rgba(0,0,0,0.04)] border border-gray-100/90 p-6 sm:p-8 md:p-9 transition-all">
                
                <!-- Alert Message Container (Dynamic via JS) -->
                <div id="alertBox" class="hidden mb-5 p-3.5 rounded-lg text-sm font-medium transition-all"></div>

                <form id="registerForm" novalidate onsubmit="handleRegister(event)" class="space-y-4 sm:space-y-4.5">
                    
                    <!-- Field: Username -->
                    <div>
                        <label for="username" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Username
                        </label>
                        <input type="text"
                               id="username"
                               name="username"
                               placeholder="Masukkan username"
                               autocomplete="username"
                               required
                               class="w-full px-4 py-2.5 sm:py-3 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                        <p id="usernameError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Field: Email -->
                    <div>
                        <label for="email" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               placeholder="Masukkan email"
                               autocomplete="email"
                               required
                               class="w-full px-4 py-2.5 sm:py-3 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                        <p id="emailError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Field: Password -->
                    <div>
                        <label for="password" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   autocomplete="new-password"
                                   required
                                   class="w-full px-4 py-2.5 sm:py-3 pr-11 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                            <button type="button"
                                    onclick="togglePasswordVisibility('password', 'eyeIconPassword')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0c3b2e] transition-colors p-1.5 focus:outline-none cursor-pointer"
                                    title="Lihat/Sembunyikan Password"
                                    aria-label="Lihat atau sembunyikan password">
                                <svg id="eyeIconPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <!-- Eye Open Icon -->
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p id="passwordError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Field: Konfirmasi Password -->
                    <div>
                        <label for="password_confirmation" class="block text-[#0c3b2e] font-bold text-sm sm:text-base mb-1.5 sm:mb-2">
                            Konfirmasi Password
                        </label>
                        <div class="relative">
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Masukkan password"
                                   autocomplete="new-password"
                                   required
                                   class="w-full px-4 py-2.5 sm:py-3 pr-11 bg-[#f8fafc] border border-[#e2e8f0] rounded-lg text-gray-800 placeholder:text-gray-400 placeholder:font-normal text-sm sm:text-base focus:outline-none focus:border-[#0c3b2e] focus:bg-white focus:ring-2 focus:ring-[#0c3b2e]/15 transition-all duration-150" />
                            <button type="button"
                                    onclick="togglePasswordVisibility('password_confirmation', 'eyeIconConfirm')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-[#0c3b2e] transition-colors p-1.5 focus:outline-none cursor-pointer"
                                    title="Lihat/Sembunyikan Konfirmasi Password"
                                    aria-label="Lihat atau sembunyikan konfirmasi password">
                                <svg id="eyeIconConfirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                        </div>
                        <p id="confirmError" class="hidden text-xs text-rose-600 mt-1 font-medium"></p>
                    </div>

                    <!-- Tombol Daftar -->
                    <div class="pt-2">
                        <button type="submit"
                                id="submitBtn"
                                class="w-full py-3 sm:py-3.5 px-4 bg-[#0f3e2e] hover:bg-[#0a2e22] active:scale-[0.99] text-white font-bold text-base sm:text-[17px] rounded-lg shadow-sm hover:shadow-md transition-all duration-150 flex items-center justify-center gap-2 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#0f3e2e] focus:ring-offset-2">
                            <span id="btnText">Daftar</span>
                            <svg id="btnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </form>

                <!-- Footer: Sudah memiliki akun? Masuk Disini -->
                <div class="text-center mt-5 pt-1">
                    <p class="text-xs sm:text-sm text-gray-700">
                        Sudah memiliki akun?
                        <a href="{{ route('login') }}"
                           class="text-[#00a8ff] hover:text-[#0284c7] font-semibold hover:underline transition-colors ml-1 focus:outline-none">
                            Masuk Disini
                        </a>
                    </p>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal Notifikasi Sukses Pendaftaran (Front End Only Demo) -->
    <div id="successModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-black/40 backdrop-blur-xs transition-opacity duration-200">
        <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-center shadow-2xl transform transition-all scale-95 duration-200" id="modalCard">
            <!-- Checkmark Icon -->
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-emerald-100 mb-4">
                <svg class="h-9 w-9 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            
            <h3 class="text-lg font-bold text-[#0c3b2e] mb-1">Pendaftaran Berhasil!</h3>
            <p class="text-xs sm:text-sm text-gray-600 mb-5 leading-relaxed">
                Akun <span id="modalUsername" class="font-semibold text-gray-900"></span> telah berhasil didaftarkan (Front End Demo).
            </p>

            <div class="flex flex-col gap-2">
                <a href="{{ route('login') }}"
                   class="w-full py-2.5 px-4 bg-[#0f3e2e] hover:bg-[#0a2e22] text-white font-bold text-sm rounded-lg transition-colors cursor-pointer text-center">
                    Masuk ke Halaman Login
                </a>
                <button type="button"
                        onclick="closeModal()"
                        class="w-full py-2.5 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg transition-colors cursor-pointer">
                    Tetap di Halaman Ini
                </button>
            </div>
        </div>
    </div>

    <!-- Frontend Interactive Script -->
    <script>
        // Toggle password show/hide
        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            
            if (input.type === 'password') {
                input.type = 'text';
                // Switch to eye closed icon
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                input.type = 'password';
                // Switch to eye open icon
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        }

        // Frontend Registration Handler (No Backend)
        function handleRegister(event) {
            event.preventDefault();

            // Clear previous errors
            clearErrors();

            const username = document.getElementById('username').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;

            let hasError = false;

            // 1. Validate Username
            if (!username) {
                showFieldError('usernameError', 'Username wajib diisi.');
                hasError = true;
            } else if (username.length < 3) {
                showFieldError('usernameError', 'Username minimal 3 karakter.');
                hasError = true;
            }

            // 2. Validate Email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!email) {
                showFieldError('emailError', 'Email wajib diisi.');
                hasError = true;
            } else if (!emailRegex.test(email)) {
                showFieldError('emailError', 'Format email tidak valid.');
                hasError = true;
            }

            // 3. Validate Password
            if (!password) {
                showFieldError('passwordError', 'Password wajib diisi.');
                hasError = true;
            } else if (password.length < 6) {
                showFieldError('passwordError', 'Password minimal 6 karakter.');
                hasError = true;
            }

            // 4. Validate Confirm Password
            if (!confirmPassword) {
                showFieldError('confirmError', 'Konfirmasi password wajib diisi.');
                hasError = true;
            } else if (password !== confirmPassword) {
                showFieldError('confirmError', 'Konfirmasi password tidak cocok dengan password.');
                hasError = true;
            }

            if (hasError) return;

            // Animate Button Loading
            const btn = document.getElementById('submitBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            btn.disabled = true;
            btnText.textContent = 'Mendaftarkan...';
            btnSpinner.classList.remove('hidden');

            // Simulate Front-End Network delay
            setTimeout(() => {
                btn.disabled = false;
                btnText.textContent = 'Daftar';
                btnSpinner.classList.add('hidden');

                // Show Success Modal
                document.getElementById('modalUsername').textContent = username;
                const modal = document.getElementById('successModal');
                const modalCard = document.getElementById('modalCard');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modalCard.classList.remove('scale-95');
                    modalCard.classList.add('scale-100');
                }, 10);

                // Optional: Save to localStorage for demo persistence
                try {
                    const demoUsers = JSON.parse(localStorage.getItem('kingrekap_users') || '[]');
                    demoUsers.push({ username, email, registeredAt: new Date().toISOString() });
                    localStorage.setItem('kingrekap_users', JSON.stringify(demoUsers));
                } catch(e) {}

            }, 600);
        }

        function showFieldError(elementId, message) {
            const el = document.getElementById(elementId);
            if (el) {
                el.textContent = message;
                el.classList.remove('hidden');
            }
        }

        function clearErrors() {
            ['usernameError', 'emailError', 'passwordError', 'confirmError'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = '';
                    el.classList.add('hidden');
                }
            });
        }

        function closeModal() {
            const modal = document.getElementById('successModal');
            const modalCard = document.getElementById('modalCard');
            modalCard.classList.remove('scale-100');
            modalCard.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close modal when clicking outside
        document.getElementById('successModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
</body>
</html>
