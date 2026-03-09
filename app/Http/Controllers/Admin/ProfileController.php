<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('admin.profile.show', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        ]);

        $user->update($data);

        return redirect()->route('admin.profile.show')
            ->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
        }

        $user->update([
            'password' => Hash::make($data['password'])
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }

    public function uploadAvatar(Request $request)
    {
        // Debug: Log request
        \Log::info('Upload avatar request received', [
            'has_file' => $request->hasFile('avatar'),
            'all_files' => $request->allFiles(),
        ]);

        // Check if file exists
        if (!$request->hasFile('avatar')) {
            return redirect()->back()->with('error', 'Tidak ada file yang diupload. Silakan pilih file terlebih dahulu.');
        }

        $file = $request->file('avatar');
        
        // Check if file is valid
        if (!$file || !$file->isValid()) {
            return redirect()->back()->with('error', 'File tidak valid. Silakan coba lagi.');
        }

        // Debug: Log file info
        \Log::info('File info', [
            'original_name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
            'is_valid' => $file->isValid(),
        ]);

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = Auth::user();
        
        // Delete old avatar if exists
        if ($user->avatar && !str_contains($user->avatar, 'google')) {
            $oldPath = str_replace(asset('uploads/'), 'uploads/', $user->avatar);
            if (file_exists(public_path($oldPath))) {
                unlink(public_path($oldPath));
                \Log::info('Deleted old avatar: ' . $oldPath);
            }
        }

        // Upload new avatar
        $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        
        \Log::info('Uploading avatar', [
            'original_name' => $file->getClientOriginalName(),
            'new_name' => $fileName,
            'size' => $file->getSize(),
            'mime_type' => $file->getClientMimeType(),
        ]);
        
        $file->move(public_path('uploads/avatars'), $fileName);
        
        $avatarUrl = asset('uploads/avatars/' . $fileName);
        
        \Log::info('Avatar uploaded successfully', ['url' => $avatarUrl]);
        
        $user->update([
            'avatar' => $avatarUrl
        ]);

        return redirect()->route('admin.profile.settings')
            ->with('success', 'Foto profil berhasil diperbarui');
    }

    public function removeAvatar()
    {
        $user = Auth::user();
        
        // Delete avatar file if not Google avatar
        if ($user->avatar && !str_contains($user->avatar, 'google')) {
            $oldPath = str_replace(asset('uploads/'), 'uploads/', $user->avatar);
            if (file_exists(public_path($oldPath))) {
                unlink(public_path($oldPath));
            }
        }

        $user->update(['avatar' => null]);

        return redirect()->route('admin.profile.settings')
            ->with('success', 'Foto profil berhasil dihapus');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirm_text' => ['required', 'string'],
        ]);

        if ($request->confirm_text !== 'HAPUS') {
            return back()->withErrors(['confirm_text' => 'Konfirmasi tidak valid']);
        }

        $user = Auth::user();
        
        // Prevent admin from deleting themselves if they're the only admin
        $adminCount = \App\Models\User::where('role', 'admin')->count();
        if ($adminCount <= 1) {
            return back()->withErrors(['confirm_text' => 'Tidak bisa menghapus akun admin terakhir. Silakan buat admin lain terlebih dahulu.']);
        }
        
        // Delete avatar file if exists
        if ($user->avatar && !str_contains($user->avatar, 'google')) {
            $oldPath = str_replace(asset('uploads/'), 'uploads/', $user->avatar);
            if (file_exists(public_path($oldPath))) {
                unlink(public_path($oldPath));
            }
        }

        // Delete user
        $user->delete();

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Akun Anda berhasil dihapus permanen');
    }

    public function settings()
    {
        return view('admin.profile.settings');
    }
}
