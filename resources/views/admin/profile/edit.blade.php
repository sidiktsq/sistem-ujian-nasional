@extends('layouts.app')

@section('page-title', 'Edit Profil Admin')

@section('content')
<div style="max-width: 800px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 style="font-size: 24px; font-weight: 700;">Edit Profil</h2>
        <a href="{{ route('admin.profile.show') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf
        <div class="card">
            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Informasi Pribadi</h3>
            
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="form-control" required>
            </div>
            
            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </div>
    </form>


</div>
@endsection
