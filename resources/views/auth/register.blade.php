@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2>Buat Akun Baru</h2>
    <p class="sub">Isi data di bawah untuk mendaftar</p>

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" autocomplete="off">
        @csrf
        <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <div class="input-wrap">
                <i class="fas fa-user"></i>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required autofocus autocomplete="off">
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
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@email.com" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Kata Sandi</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Konfirmasi Kata Sandi</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi kata sandi" required autocomplete="off">
            </div>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fas fa-user-plus"></i> Daftar Sekarang
        </button>
    </form>
    
    {{-- Divider --}}
    <div style="text-align: center; margin: 20px 0; position: relative;">
        <div style="position: absolute; top: 50%; left: 0; right: 0; height: 1px; background: var(--border);"></div>
        <span style="background: var(--card); padding: 0 15px; color: var(--text-muted); font-size: 12px; position: relative;">atau daftar dengan</span>
    </div>
    
    {{-- Google Register Button --}}
    <form method="GET" action="{{ route('auth.google') }}">
        <button type="submit" class="btn-google" style="width: 100%; justify-content: center; margin-bottom: 15px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            Daftar dengan Google
        </button>
    </form>
</div>
<div class="auth-footer">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
</div>
@endsection
