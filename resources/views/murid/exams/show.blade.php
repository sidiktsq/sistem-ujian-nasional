@extends('layouts.app')
@section('page-title', 'Persiapan Ujian')

@section('content')
<div style="max-width:700px; margin: 0 auto">
    <a href="{{ route('murid.exams.index') }}" class="btn btn-secondary btn-sm" style="margin-bottom:20px">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card" style="padding:40px; text-align:center">
        <div style="width:80px; height:80px; background:rgba(16,185,129,0.1); border-radius:30% 70% 70% 30% / 30% 30% 70% 70%; display:flex; align-items:center; justify-content:center; font-size:32px; color:#34D399; margin: 0 auto 24px">
            <i class="fas fa-file-signature"></i>
        </div>
        
        <h2 style="font-size:24px; font-weight:800; margin-bottom:12px">{{ $exam->title }}</h2>
        <p style="color:var(--text-muted); line-height:1.6; margin-bottom:32px">
            {{ $exam->description ?? 'Anda akan mengerjakan ujian ini secara online. Pastikan koneksi internet stabil sebelum memulai pengerjaan.' }}
        </p>

        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px; margin-bottom:40px">
            <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:12px; padding:16px">
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px">WAKTU PENGERJAAN</div>
                <div style="font-size:20px; font-weight:800; color:#FCD34D">{{ $exam->duration }} Menit</div>
            </div>
            <div style="background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:12px; padding:16px">
                <div style="font-size:13px; color:var(--text-muted); margin-bottom:4px">JUMLAH SOAL</div>
                <div style="font-size:20px; font-weight:800; color:#818CF8">{{ $exam->questions_count }} Soal</div>
            </div>
        </div>

        <div style="background:rgba(245,158,11,0.05); border:1px solid rgba(245,158,11,0.2); border-radius:12px; padding:16px; margin-bottom:32px; text-align:left; display:flex; gap:12px">
            <i class="fas fa-exclamation-triangle" style="color:#FCD34D; font-size:18px; margin-top:3px"></i>
            <div>
                <p style="font-size:13px; font-weight:700; color:#FCD34D; margin-bottom:4px">PENTING:</p>
                <p style="font-size:12px; color:var(--text-muted); line-height:1.5">Setelah menekan tombol "Mulai", waktu akan terus berjalan meskipun Anda menutup halaman ini.</p>
            </div>
        </div>

        @if($existingSession)
            <div class="alert alert-info">
                <i class="fas fa-check-circle"></i> Anda telah menyelesaikan ujian ini dengan skor <strong>{{ number_format($existingSession->score, 0) }}</strong>.
            </div>
            <div style="display:flex; gap:10px; justify-content:center">
                <a href="{{ route('murid.results.show', $existingSession) }}" class="btn btn-primary"><i class="fas fa-eye"></i> Lihat Hasil</a>
                <a href="{{ route('murid.exams.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        @else
            <form method="POST" action="{{ route('murid.exams.start', $exam) }}">
                @csrf
                <button type="submit" class="btn btn-green btn-lg" style="padding:16px 48px; font-size:16px"><i class="fas fa-play"></i> MULAI UJIAN SEKARANG</button>
            </form>
        @endif
    </div>
</div>
@endsection
