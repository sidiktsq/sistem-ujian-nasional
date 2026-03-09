<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }
        
        if ($request->role) {
            $query->where('role', $request->role);
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,guru,murid'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
        ]);
        
        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($data['password'])]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    public function toggleRole(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa mengubah role sendiri');
        }

        // Cycle through roles: admin -> guru -> murid -> admin
        $roles = ['admin', 'guru', 'murid'];
        $currentRoleIndex = array_search($user->role, $roles);
        $newRoleIndex = ($currentRoleIndex + 1) % count($roles);
        $user->role = $roles[$newRoleIndex];
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role}");
    }
}
