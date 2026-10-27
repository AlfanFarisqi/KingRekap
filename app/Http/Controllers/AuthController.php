<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Tampilkan formulir masuk (login).
     */
    public function showLogin(): View
    {
        return view('auth.login');
    }

    /**
     * Proses autentikasi login.
     */
    public function login(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $loginInput = $request->input('username');
        $password = $request->input('password');

        // Autentikasi dengan email atau username
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $loginInput,
            'password' => $password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil masuk!',
                    'redirect' => route('dashboard'),
                    'user' => [
                        'name' => Auth::user()->name,
                        'username' => Auth::user()->username,
                    ],
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Username/email atau password salah.',
            ], 422);
        }

        throw ValidationException::withMessages([
            'username' => ['Username/email atau password salah.'],
        ]);
    }

    /**
     * Tampilkan formulir pendaftaran.
     */
    public function showRegister(): View
    {
        return view('auth.register');
    }

    /**
     * Proses pendaftaran akun baru.
     */
    public function register(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['username'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dibuat!',
                'redirect' => route('dashboard'),
                'user' => [
                    'name' => $user->name,
                    'username' => $user->username,
                ],
            ]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Proses logout user.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
