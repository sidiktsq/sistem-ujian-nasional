@extends('layouts.app')
@section('page-title', 'Edit Profil Siswa')

@section('content')
<div style="max-width:800px">
    <a href="{{ route('murid.profile.show') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali ke Profil
    </a>

    <div class="card">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:24px">Edit Profil</h3>

        <form method="POST" action="{{ route('murid.profile.update') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Nama Lengkap <span style="color:#F87171">*</span></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                @error('name')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span style="color:#F87171">*</span></label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                @error('email')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:32px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
                <a href="{{ route('murid.profile.show') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>

    <div class="card" style="margin-top:24px" id="password">
        <h3 style="font-size:20px; font-weight:700; margin-bottom:24px">Ubah Password</h3>

        <form method="POST" action="{{ route('murid.profile.password') }}">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">Password Saat Ini <span style="color:#F87171">*</span></label>
                <input type="password" name="current_password" class="form-control" required>
                @error('current_password')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password Baru <span style="color:#F87171">*</span></label>
                <input type="password" name="password" class="form-control" required>
                <p style="font-size:11px; color:var(--text-muted); margin-top:4px">Minimal 8 karakter</p>
                @error('password')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Konfirmasi Password Baru <span style="color:#F87171">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required>
                @error('password_confirmation')<p class="error-text">{{ $message }}</p>@enderror
            </div>

            <div style="display:flex; gap:10px; margin-top:32px">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Ubah Password
                </button>
                <a href="{{ route('murid.profile.show') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
