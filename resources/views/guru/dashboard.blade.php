@extends('layouts.app')
@section('page-title', 'Dashboard Guru')

@section('topbar-actions')
    <a href="{{ route('guru.exams.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Buat Ujian
    </a>
@endsection

@section('content')
{{-- Stat Cards --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(79,70,229,.15);color:#818CF8"><i class="fas fa-file-alt"></i></div>
        <div>
            <div class="stat-value">{{ $totalUjian }}</div>
            <div class="stat-label">Total Ujian</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,.15);color:#34D399"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="stat-value">{{ $ujianAktif }}</div>
            <div class="stat-label">Ujian Aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(245,158,11,.15);color:#FCD34D"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-value">{{ $totalSesi }}</div>
            <div class="stat-label">Total Peserta</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(239,68,68,.15);color:#F87171"><i class="fas fa-trophy"></i></div>
        <div>
            <div class="stat-value">{{ $selesai }}</div>
            <div class="stat-label">Ujian Selesai</div>
        </div>
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
