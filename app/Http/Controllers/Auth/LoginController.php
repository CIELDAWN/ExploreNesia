<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Show the application's login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Redirect berdasarkan role
            $user = Auth::user();
            
            // Check if user is active
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Silakan hubungi administrator.',
                ])->onlyInput('email');
            }
            
            switch($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'mitra':
                    return redirect()->route('mitra.dashboard');
                case 'user':
                default:
                    return redirect()->route('user.home');
            }
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect ke Google untuk proses OAuth.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback dari Google setelah autentikasi.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }

        if (!$googleUser->getEmail()) {
            return redirect()->route('login')->with('error', 'Email Google tidak ditemukan.');
        }

        // Cari user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Buat user baru role user
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => 'user',
                'phone' => null,
                'address' => null,
                'avatar' => null,
                'is_active' => true,
            ]);
        }

        // Jika akun dinonaktifkan, jangan izinkan login
        if (!$user->is_active) {
            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        Auth::login($user, true);

        // Jika user baru atau belum ada phone, arahkan ke halaman profil untuk lengkapi data
        if (!$user->phone) {
            return redirect()->route('user.profile')
                ->with('success', 'Akun berhasil dibuat dari Google. Silakan lengkapi profil Anda.');
        }

        // Redirect sesuai role (gunakan logika yang sama dengan login biasa)
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'mitra':
                return redirect()->route('mitra.dashboard');
            case 'user':
            default:
                return redirect()->route('user.home');
        }
    }
}
