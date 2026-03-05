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
</div>
<div class="auth-footer">
    Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
</div>
@endsection
