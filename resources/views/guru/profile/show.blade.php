@extends('layouts.app')
@section('page-title', 'Profil Guru')

@section('topbar-actions')
    <a href="{{ route('guru.profile.edit') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-edit"></i> Edit Profil
    </a>
@endsection

@section('content')
<div style="max-width:800px">
    <div class="card">
        <div style="display:flex; align-items:center; gap:24px; margin-bottom:32px">
            <div style="width:120px; height:120px; background:linear-gradient(135deg, var(--guru-primary), rgba(129,140,248,0.8)); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:48px; font-weight:700; color:white">
                {{ strtoupper(substr($user->name, 0, 2)) }}
            </div>
            <div>
                <h2 style="font-size:24px; font-weight:700; margin-bottom:8px">{{ $user->name }}</h2>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px">
                    <i class="fas fa-envelope" style="color:var(--text-muted); font-size:14px"></i>
                    <span style="color:var(--text-muted)">{{ $user->email }}</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px">
                    <i class="fas fa-user-tag" style="color:var(--text-muted); font-size:14px"></i>
                    <span class="badge" style="background:var(--guru-primary); color:white">Guru</span>
                </div>
                <div style="display:flex; align-items:center; gap:8px">
                    <i class="fas fa-calendar" style="color:var(--text-muted); font-size:14px"></i>
                    <span style="color:var(--text-muted)">Bergabung {{ $user->created_at->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:24px">
            <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid var(--border)">
                <h4 style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--guru-primary)">
                    <i class="fas fa-book"></i> Statistik Mengajar
                </h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                    <div>
                        <div style="font-size:24px; font-weight:700; color:var(--guru-primary)">{{ $user->exams()->count() }}</div>
                        <div style="font-size:12px; color:var(--text-muted)">Total Ujian</div>
                    </div>
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#34D399">{{ $user->exams()->where('is_active', true)->count() }}</div>
                        <div style="font-size:12px; color:var(--text-muted)">Ujian Aktif</div>
                    </div>
                </div>
            </div>

            <div style="background:rgba(255,255,255,0.02); padding:20px; border-radius:12px; border:1px solid var(--border)">
                <h4 style="font-size:16px; font-weight:700; margin-bottom:16px; color:var(--guru-primary)">
                    <i class="fas fa-users"></i> Statistik Siswa
                </h4>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px">
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#F59E0B">
                            {{ \App\Models\ExamSession::whereIn('exam_id', $user->exams()->pluck('id'))->distinct('user_id')->count('user_id') }}
                        </div>
                        <div style="font-size:12px; color:var(--text-muted)">Total Siswa</div>
                    </div>
                    <div>
                        <div style="font-size:24px; font-weight:700; color:#818CF8">
                            {{ \App\Models\ExamSession::whereIn('exam_id', $user->exams()->pluck('id'))->where('status', 'submitted')->count() }}
                        </div>
                        <div style="font-size:12px; color:var(--text-muted)">Ujian Selesai</div>
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top:32px; padding-top:24px; border-top:1px solid var(--border)">
            <h4 style="font-size:16px; font-weight:700; margin-bottom:16px">Keamanan</h4>
            <div style="display:flex; justify-content:space-between; align-items:center; padding:16px; background:rgba(255,255,255,0.02); border-radius:8px; border:1px solid var(--border)">
                <div>
                    <div style="font-weight:600; margin-bottom:4px">Password</div>
                    <div style="font-size:12px; color:var(--text-muted)">Terakhir diubah: {{ $user->updated_at->diffForHumans() }}</div>
                </div>
                <a href="{{ route('guru.profile.edit') }}#password" class="btn btn-secondary btn-sm">
                    <i class="fas fa-key"></i> Ubah Password
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
