@extends('layouts.app')
@section('page-title', 'Daftar Ujian Aktif')

@section('content')
<div class="card">
    <h3 style="font-size:17px; font-weight:700; margin-bottom:24px"><i class="fas fa-pencil-alt" style="color:#34D399; margin-right:8px"></i>Ujian Pilihan Anda</h3>
    
    @if($exams->isEmpty())
        <div style="text-align:center; padding:60px; color:var(--text-muted)">
            <i class="fas fa-box-open" style="font-size:48px; margin-bottom:16px; display:block; color:#334155"></i>
            <p>Belum ada ujian yang tersedia saat ini.</p>
        </div>
    @else
        <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap:20px">
            @foreach($exams as $exam)
                @php $session = $exam->sessions->first(); @endphp
                <div class="card card-sm" style="background:rgba(255,255,255,0.01); border:1px solid {{ $session && $session->status == 'submitted' ? 'rgba(52,211,153,0.2)' : 'var(--border)' }}">
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px">
                        <span class="badge {{ $session && $session->status == 'submitted' ? 'badge-green' : 'badge-gray' }}">
                            <i class="fas {{ $session && $session->status == 'submitted' ? 'fa-check-circle' : 'fa-clock' }}"></i> 
                            {{ $session && $session->status == 'submitted' ? 'Selesai' : 'Belum Dikerjakan' }}
                        </span>
                        <span style="font-size:12px; color:var(--text-muted); font-weight:600">{{ $exam->duration }} Menit</span>
                    </div>

                    <h4 style="font-size:16px; font-weight:700; margin-bottom:8px">{{ $exam->title }}</h4>
                    <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px; line-height:1.5; min-height:40px">
                        {{ Str::limit($exam->description ?? 'Tidak ada deskripsi ujian.', 80) }}
                    </p>

                    <div style="display:flex; align-items:center; gap:12px; padding-top:16px; border-top:1px solid var(--border)">
                        <div style="flex:1">
                            <div style="font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px">Total Soal</div>
                            <div style="font-size:14px; font-weight:700">{{ $exam->questions_count }} Pertanyaan</div>
                        </div>
                        
                        @if($session && $session->status == 'submitted')
                            <a href="{{ route('murid.results.show', $session) }}" class="btn btn-secondary btn-sm">Lihat Hasil</a>
                        @else
                            <a href="{{ route('murid.exams.show', $exam) }}" class="btn btn-green btn-sm">Buka Ujian</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
