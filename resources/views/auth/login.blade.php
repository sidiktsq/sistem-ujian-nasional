@extends('layouts.guest')

@section('content')
<div class="auth-card">
    <h2>Selamat Datang!</h2>
    <p class="sub">Masuk ke akun Anda untuk melanjutkan</p>

    @if($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
        @csrf
        <div class="form-group">
            <label class="form-label">Alamat Email</label>
            <div class="input-wrap">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus autocomplete="off">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Kata Sandi</label>
            <div class="input-wrap">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control" placeholder="Masukkan kata sandi" required autocomplete="off">
            </div>
        </div>
        <button type="submit" class="btn-submit">
            <i class="fas fa-sign-in-alt"></i> Masuk
        </button>
    </form>
</div>
<div class="auth-footer">
    Belum punya akun? <a href="{{ route('register') }}">Daftar Sekarang</a>
</div>

<!-- <div style="margin-top: 24px; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08); border-radius: 12px; padding: 16px;">
    <p style="font-size: 12px; color: #64748B; margin-bottom: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: .5px;">Demo Akun</p>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 8px;">
        <div style="background: rgba(79,70,229,.1); border-radius: 8px; padding: 10px;">
            <p style="font-size: 12px; color: #818CF8; font-weight: 700; margin-bottom: 4px;"><i class="fas fa-chalkboard-teacher"></i> GURU</p>
            <p style="font-size: 11px; color: #94A3B8;">guru@ujian.com</p>
            <p style="font-size: 11px; color: #94A3B8;">password</p>
        </div>
        <div style="background: rgba(5,150,105,.1); border-radius: 8px; padding: 10px;">
            <p style="font-size: 12px; color: #34D399; font-weight: 700; margin-bottom: 4px;"><i class="fas fa-user-graduate"></i> MURID</p>
            <p style="font-size: 11px; color: #94A3B8;">murid@ujian.com</p>
            <p style="font-size: 11px; color: #94A3B8;">password</p>
        </div>
    </div> -->
</div>
@endsection
