@extends('layouts.app')
@section('page-title', 'Dashboard Murid')

@section('content')
{{-- Stat Cards Circular --}}
<div class="stats-grid">
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        stroke="#34D399"
                        stroke-dasharray="326.73"
                        stroke-dashoffset="{{ 326.73 - (326.73 * min($totalUjian / 10, 1)) }}">
                </circle>
            </svg>
            <div class="text" style="color: #34D399">{{ $totalUjian }}</div>
        </div>
        <div class="stat-label-circular">Ujian Selesai</div>
        <div class="stat-sublabel-circular">Statistik Siswa</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        stroke="#818CF8"
                        stroke-dasharray="326.73"
                        stroke-dashoffset="{{ 326.73 - (326.73 * min($avgScore / 100, 1)) }}">
                </circle>
            </svg>
            <div class="text" style="color: #818CF8">{{ number_format($avgScore, 0) }}</div>
        </div>
        <div class="stat-label-circular">Rata-rata Nilai</div>
        <div class="stat-sublabel-circular">Statistik Siswa</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        stroke="#FCD34D"
                        stroke-dasharray="326.73"
                        stroke-dashoffset="{{ 326.73 - (326.73 * min($bestScore / 100, 1)) }}">
                </circle>
            </svg>
            <div class="text" style="color: #FCD34D">{{ number_format($bestScore, 0) }}</div>
        </div>
        <div class="stat-label-circular">Nilai Tertinggi</div>
        <div class="stat-sublabel-circular">Statistik Siswa</div>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1.2fr; gap:24px">
    {{-- Available Exams --}}
    <div>
        <div class="card">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:20px; display:flex; align-items:center; gap:10px">
                <i class="fas fa-pencil-alt" style="color:#34D399"></i> Ujian Tersedia
            </h3>
            @if($availableExams->isEmpty())
                <div style="text-align:center; padding:40px; color:var(--text-muted)">
                    <p>Tidak ada ujian aktif saat ini.</p>
                </div>
            @else
                <div style="display:grid; grid-template-columns:1fr; gap:12px">
                    @foreach($availableExams as $exam)
                    <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border); border-radius:12px; display:flex; justify-content:space-between; align-items:center">
                        <div>
                            <div style="font-weight:600; font-size:15px">{{ $exam->title }}</div>
                            <div style="font-size:12px; color:var(--text-muted); margin-top:4px">
                                <i class="far fa-clock"></i> {{ $exam->duration }} menit • <i class="far fa-list-alt"></i> {{ $exam->questions_count }} Soal
                            </div>
                        </div>
                        <a href="{{ route('murid.exams.show', $exam) }}" class="btn btn-green btn-sm">Buka Ujian</a>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Results --}}
    <div>
        <div class="card">
            <h3 style="font-size:16px; font-weight:700; margin-bottom:20px; display:flex; align-items:center; gap:10px">
                <i class="fas fa-history" style="color:#818CF8"></i> Nilai Terbaru
            </h3>
            @if($mySessions->isEmpty())
                <div style="text-align:center; padding:40px; color:var(--text-muted)">
                    <p style="font-size:13px">Anda belum mengerjakan ujian.</p>
                </div>
            @else
                @foreach($mySessions as $session)
                @php
                    $hasPendingGrading = $session->answers->contains(function($answer) {
                        return $answer->grading_status === 'pending';
                    });
                @endphp
                <div style="margin-bottom:12px; padding-bottom:12px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
                    <div>
                        <div style="font-size:13px; font-weight:600">{{ $session->exam->title }}</div>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px">{{ $session->submitted_at->diffForHumans() }}</div>
                    </div>
                    @if($hasPendingGrading)
                        <div style="text-align:right">
                            <div style="font-size:16px; font-weight:800; color:#9CA3AF">Menunggu</div>
                            <span class="badge" style="font-size:9px; padding:1px 6px; background:rgba(156,163,175,0.2); border:1px solid rgba(156,163,175,0.3); color:#9CA3AF">DINILAI</span>
                        </div>
                    @else
                        <div style="text-align:right">
                            <div style="font-size:16px; font-weight:800; color:{{ $session->score >= 70 ? '#34D399' : '#F87171' }}">{{ number_format($session->score, 0) }}</div>
                            <span class="badge {{ $session->score >= 70 ? 'badge-green' : 'badge-red' }}" style="font-size:9px; padding:1px 6px">{{ $session->grade }}</span>
                        </div>
                    @endif
                </div>
                @endforeach
                <a href="{{ route('murid.results.index') }}" style="display:block; text-align:center; font-size:12px; color:#818CF8; text-decoration:none; margin-top:10px; font-weight:600">LIHAT SEMUA HASIL</a>
            @endif
        </div>
    </div>
</div>
@endsection
