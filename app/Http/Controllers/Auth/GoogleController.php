<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
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
            
            // Check if user already exists
            $user = User::where('google_id', $googleUser->getId())->first();
            
            if ($user) {
                // User exists, login and redirect
                Auth::login($user);
                return redirect($this->redirectRoute());
            }
            
            // Check if user exists with same email
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            
            if ($existingUser) {
                // Link Google account to existing user
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                Auth::login($existingUser);
                return redirect($this->redirectRoute());
            }
            
            // New user - show verification form
            session(['google_user' => $googleUser]);
            return redirect()->route('auth.google.complete');
            
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.');
        }
    }
    
    public function complete()
    {
        if (!session('google_user')) {
            return redirect()->route('login');
        }
        
        $googleUser = session('google_user');
        return view('auth.google-complete', compact('googleUser'));
    }
    
    public function storeComplete(Request $request)
    {
        if (!session('google_user')) {
            return redirect()->route('login');
        }
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'in:guru,murid'],
            'google_id' => ['required'],
            'email' => ['required', 'email'],
        ]);

        $googleUser = session('google_user');
        
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'google_id' => $data['google_id'],
            'avatar' => $request->avatar,
            'role' => $data['role'],
            'password' => bcrypt(Str::random(32)), // Random password
            'email_verified_at' => now(),
        ]);
        
        // Clear session
        session()->forget('google_user');
        
        Auth::login($user);
        return redirect($this->redirectRoute());
    }
    
    private function redirectRoute()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        } elseif ($user->role === 'guru') {
            return route('guru.dashboard');
        } else {
            return route('murid.dashboard');
        }
    }
}
