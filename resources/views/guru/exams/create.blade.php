@extends('layouts.app')
@section('page-title', 'Buat Ujian Baru')

@section('content')
<div style="max-width:700px">
    <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
    <div class="card">
        <h3 style="font-size:17px;font-weight:700;margin-bottom:24px"><i class="fas fa-file-plus" style="color:#818CF8;margin-right:8px"></i>Informasi Ujian</h3>
        <form method="POST" action="{{ route('guru.exams.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Judul Ujian <span style="color:#F87171">*</span></label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="cth: Ujian Akhir Semester Matematika" required>
                @error('title')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" placeholder="Deskripsi singkat tentang ujian ini...">{{ old('description') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Durasi (menit) <span style="color:#F87171">*</span></label>
                <input type="number" name="duration" class="form-control" value="{{ old('duration', 60) }}" min="1" max="300" required>
                @error('duration')<p class="error-text">{{ $message }}</p>@enderror
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                    <label for="is_active" style="font-size:14px;cursor:pointer">Aktifkan ujian (murid bisa mengerjakan)</label>
                </div>
            </div>
            <div style="display:flex;gap:10px;margin-top:8px">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan & Tambah Soal</button>
                <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
