@extends('layouts.app')
@section('page-title', 'Profil Siswa')

@section('topbar-actions')
    <a href="{{ route('murid.profile.edit') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i> Edit Profil
    </a>
@endsection

@section('content')
<div style="max-width:800px">
    <div class="card">
        <div style="display:flex; align-items:center; gap:24px; margin-bottom:32px">
            @if($user->avatar)
                <img src="{{ $user->avatar }}?t={{ time() }}" alt="{{ $user->name }}" style="width:120px; height:120px; border-radius:50%; object-fit:cover; border: 4px solid var(--card);">
            @else
                <div style="width:120px; height:120px; background:linear-gradient(135deg, #10b981, rgba(16,185,129,0.8)); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:700; color:white">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 style="font-size:24px; font-weight:700; margin-bottom:8px">{{ $user->name }}</h2>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px">
                    <i class="fas fa-envelope" style="color:var(--text-muted); font-size:14px"></i>
                    <span style="color:var(--text-muted)">{{ $user->email }}</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px">
                    <i class="fas fa-user-tag" style="color:var(--text-muted); font-size:14px"></i>
                    <span class="badge" style="background:#10b981; color:white">Siswa</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px">
                    <i class="fas fa-calendar" style="color:var(--text-muted); font-size:14px"></i>
                    <span style="color:var(--text-muted)">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px">
            <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid var(--border)">
                <h4 style="font-size:16px; font-weight:700; margin-bottom:16px; color:#10b981">
                    <i class="fas fa-clipboard-list"></i> Statistik Ujian
                </h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#10b981">{{ \App\Models\ExamSession::where('user_id', $user->id)->count() }}</div>
                        <div style="font-size:12px; color:var(--text-muted)">Total Ujian</div>
                    </div>
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#34D399">{{ \App\Models\ExamSession::where('user_id', $user->id)->where('status', 'submitted')->count() }}</div>
                        <div style="font-size:12px; color:var(--text-muted)">Selesai</div>
                    </div>
                </div>
            </div>

            <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid var(--border)">
                <h4 style="font-size:16px; font-weight:700; margin-bottom:16px; color:#10b981">
                    <i class="fas fa-chart-line"></i> Performa Akademik
                </h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#F59E0B">
                            @php
                                $avgScore = \App\Models\ExamSession::where('user_id', $user->id)
                                    ->where('status', 'submitted')
                                    ->whereDoesntHave('answers', function($query) {
                                        $query->where('grading_status', 'pending');
                                    })
                                    ->avg('score');
                            @endphp
                            {{ $avgScore ? number_format($avgScore, 0) : '0' }}
                        </div>
                        <div style="font-size:12px; color:var(--text-muted)">Rata-rata Nilai</div>
                    </div>
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#818CF8">
                            @php
                                $bestScore = \App\Models\ExamSession::where('user_id', $user->id)
                                    ->where('status', 'submitted')
                                    ->whereDoesntHave('answers', function($query) {
                                        $query->where('grading_status', 'pending');
                                    })
                                    ->max('score');
                            @endphp
                            {{ $bestScore ? number_format($bestScore, 0) : '0' }}
                        </div>
                        <div style="font-size:12px; color:var(--text-muted)">Nilai Tertinggi</div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
@endsection
