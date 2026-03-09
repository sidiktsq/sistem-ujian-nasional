<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cek apakah user sudah ada berdasarkan google_id
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // User sudah ada, login langsung
                Auth::login($user);
                return redirect()->intended($this->redirectRoute());
            } else {
                // Cek apakah email sudah ada
                $existingUser = User::where('email', $googleUser->getEmail())->first();
                
                if ($existingUser) {
                    // Update google_id untuk user yang sudah ada
                    $existingUser->update([
                        'google_id' => $googleUser->getId(),
                    ]);
                    Auth::login($existingUser);
                    return redirect()->intended($this->redirectRoute());
                } else {
                    // Buat user baru
                    $newUser = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'role' => 'murid', // Default role
                        'password' => bcrypt('password'), // Random password
                        'email_verified_at' => now(),
                    ]);
                    
                    Auth::login($newUser);
                    return redirect()->intended($this->redirectRoute());
                }
            }
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
    
    private function redirectRoute()
    {
        $user = Auth::user();
        if ($user->role === 'guru') {
            return route('guru.dashboard');
        } else {
            return route('murid.dashboard');
        }
    }
}
