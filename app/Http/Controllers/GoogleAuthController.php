<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;

class GoogleAuthController extends Controller
{
    // Mengarahkan user ke halaman login Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Menangani kembalian data dari Google
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Cek apakah user sudah ada berdasarkan email atau google_id
            $user = User::updateOrCreate([
                'email' => $googleUser->email,
            ], [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => null, // Tanpa password
            ]);

            // Login otomatis
            Auth::login($user);

            // Arahkan ke halaman profile / dashboard sesuai flow Anda
            return redirect()->route('dashboard'); 
            
        } catch (\Exception $e) {
            // Jika gagal (misal batal memilih akun), kembalikan ke halaman login
            return redirect('/')->with('error', 'Gagal login menggunakan Google.');
        }
    }
    
    // Fungsi untuk Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}