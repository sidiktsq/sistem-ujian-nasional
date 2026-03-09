@extends('layouts.app')

@section('page-title', 'Edit User: ' . $user->name)

@section('content')
<div style="max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700;">Edit User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        
        <div class="card">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div>
                    <div class="form-group">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="guru" {{ $user->role === 'guru' ? 'selected' : '' }}>Guru</option>
                            <option value="murid" {{ $user->role === 'murid' ? 'selected' : '' }}>Murid</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <div class="form-group">
                        <label class="form-label">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                    
                    <div style="padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">Informasi Akun</div>
                        <div style="display: grid; gap: 8px; font-size: 13px;">
                            <div><strong>ID:</strong> {{ $user->id }}</div>
                            <div><strong>Terdaftar:</strong> {{ $user->created_at->format('d M Y H:i') }}</div>
                            @if($user->google_id)
                                <div><strong>Google ID:</strong> {{ $user->google_id }}</div>
                            @endif
                            @if($user->avatar)
                                <div><strong>Avatar:</strong> <a href="{{ $user->avatar }}" target="_blank">Lihat</a></div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border); display: flex; gap: 12px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
