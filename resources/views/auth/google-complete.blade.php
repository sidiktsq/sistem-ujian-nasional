@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2>Verifikasi Data Akun</h2>
    <p class="sub">Lengkapi data berikut untuk menyelesaikan pendaftaran</p>

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('auth.google.complete') }}">
        @csrf
        <input type="hidden" name="google_id" value="{{ $googleUser->getId() }}">
        <input type="hidden" name="email" value="{{ $googleUser->getEmail() }}">
        <input type="hidden" name="avatar" value="{{ $googleUser->getAvatar() }}">
        
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" name="name" class="form-control" value="{{ old('name', $googleUser->getName()) }}" placeholder="Masukkan nama lengkap" required autofocus autocomplete="off">
            </div>
        </div>
        
        <div class="form-group">
            <label class="form-label">Daftar Sebagai</label>
            <div class="input-wrap">
                <i class="fas fa-user-tag"></i>
                <select name="role" class="form-control" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="murid" {{ old('role') == 'murid' ? 'selected' : '' }}>Murid</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                </select>
            </div>
        </div>

        {{-- Google Account Info --}}
        <div style="background: rgba(66,133,244,0.1); border: 1px solid rgba(66,133,244,0.3); border-radius: 8px; padding: 12px; margin-bottom: 20px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                <img src="{{ $googleUser->getAvatar() }}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                <div>
                    <div style="font-weight: 600; color: #4285F4;">{{ $googleUser->getName() }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">{{ $googleUser->getEmail() }}</div>
                </div>
            </div>
            <div style="font-size: 11px; color: var(--text-muted);">
                <i class="fab fa-google"></i> Akun Google terverifikasi
            </div>
        </div>

        <button type="submit" class="btn-submit">
            <i class="fas fa-check"></i> Selesaikan Pendaftaran
        </button>
    </form>
    
    <div style="text-align: center; margin-top: 20px;">
        <a href="{{ route('login') }}" style="color: var(--text-muted); font-size: 13px;">
            <i class="fas fa-arrow-left"></i> Kembali ke Login
        </a>
    </div>
</div>
@endsection
