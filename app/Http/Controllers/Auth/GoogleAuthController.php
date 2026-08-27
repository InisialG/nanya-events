<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', 'Fitur Login Google belum dikonfigurasi. Harap isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada file .env.');
        }

        try {
            return Socialite::driver('google')
                ->setScopes(['openid', 'email', 'profile'])
                ->redirect();
        } catch (\Exception $e) {
            Log::error('Google Auth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal mengarahkan ke Google. Periksa apakah GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET pada .env sudah valid.');
        }
    }

    public function handleGoogleCallback()
    {
        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with('error', 'Fitur Login Google belum dikonfigurasi.');
        }

        try {
            // Gunakan fallback stateless() jika terjadi InvalidStateException / session mismatch
            try {
                $googleUser = Socialite::driver('google')->user();
            } catch (\Exception $e) {
                $googleUser = Socialite::driver('google')->stateless()->user();
            }

            if (!$googleUser || empty($googleUser->email)) {
                return redirect()->route('login')->with('error', 'Gagal mengambil informasi profil dari akun Google Anda.');
            }

            $user = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name ?? $googleUser->nickname ?? 'Pengguna Google',
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(16)),
                ]);

                $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
                $user->assignRole($userRole);
            } else {
                if (empty($user->google_id)) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                }
            }

            Auth::login($user, true);

            return redirect()->intended('/')->with('success', 'Selamat datang! Berhasil masuk dengan akun Google.');
        } catch (\Exception $e) {
            Log::error('Google Auth Callback Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal memproses autentikasi Google: ' . $e->getMessage());
        }
    }
}
