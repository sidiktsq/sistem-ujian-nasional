@extends('layouts.app')
@section('page-title', 'Dashboard Guru')

@section('topbar-actions')
    <a href="{{ route('guru.exams.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Buat Ujian
    </a>
@endsection

@section('content')
{{-- Stat Cards Circular --}}
<div class="stats-grid">
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #818CF8; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($totalUjian / 10, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #818CF8">{{ $totalUjian }}</div>
        </div>
        <div class="stat-label-circular">Total Ujian</div>
        <div class="stat-sublabel-circular">Statistik Mengajar</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #34D399; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($ujianAktif / max($totalUjian, 1), 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #34D399">{{ $ujianAktif }}</div>
        </div>
        <div class="stat-label-circular">Ujian Aktif</div>
        <div class="stat-sublabel-circular">Statistik Mengajar</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #FCD34D; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($totalSesi / 50, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #FCD34D">{{ $totalSesi }}</div>
        </div>
        <div class="stat-label-circular">Total Peserta</div>
        <div class="stat-sublabel-circular">Statistik Siswa</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #F87171; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($selesai / max($totalSesi, 1), 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #F87171">{{ $selesai }}</div>
        </div>
        <div class="stat-label-circular">Ujian Selesai</div>
        <div class="stat-sublabel-circular">Statistik Siswa</div>
    </div>
</div>

{{-- Recent Exams --}}
<div class="card">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <h3 style="font-size:16px;font-weight:700"><i class="fas fa-clock" style="color:#818CF8;margin-right:8px"></i>Ujian Terbaru</h3>
        <a href="{{ route('guru.exams.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
    </div>
    @if($recentExams->isEmpty())
        <div style="text-align:center;padding:40px;color:#64748B">
            <i class="fas fa-folder-open" style="font-size:40px;margin-bottom:12px;display:block"></i>
            <p>Belum ada ujian. <a href="{{ route('guru.exams.create') }}" style="color:#818CF8">Buat ujian pertama!</a></p>
        </div>
    @else
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Judul</th><th>Soal</th><th>Durasi</th><th>Status</th><th>Peserta</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($recentExams as $exam)
                    <tr>
                        <td style="font-weight:600">{{ $exam->title }}</td>
                        <td><span class="badge badge-blue">{{ $exam->questions_count }} soal</span></td>
                        <td>{{ $exam->duration }} menit</td>
                        <td>
                            @if($exam->is_active)
                                <span class="badge badge-green"><i class="fas fa-circle" style="font-size:8px"></i> Aktif</span>
                            @else
                                <span class="badge badge-gray">Nonaktif</span>
                            @endif
                        </td>
                        <td>{{ $exam->sessions->count() }} selesai</td>
                        <td>
                            <a href="{{ route('guru.exams.questions.index', $exam) }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-list"></i> Soal
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
