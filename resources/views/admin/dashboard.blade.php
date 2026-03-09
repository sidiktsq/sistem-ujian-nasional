@extends('layouts.app')

@section('page-title', 'Dashboard Admin')

@section('content')
{{-- Admin Stats Grid --}}
<div class="stats-grid">
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #818CF8; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($stats['total_users'] / 100, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #818CF8">{{ $stats['total_users'] }}</div>
        </div>
        <div class="stat-label-circular">Total Users</div>
        <div class="stat-sublabel-circular">Statistik Sistem</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #F87171; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($stats['total_admins'] / 10, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #F87171">{{ $stats['total_admins'] }}</div>
        </div>
        <div class="stat-label-circular">Admin</div>
        <div class="stat-sublabel-circular">Statistik Sistem</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #34D399; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($stats['total_gurus'] / 50, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #34D399">{{ $stats['total_gurus'] }}</div>
        </div>
        <div class="stat-label-circular">Guru</div>
        <div class="stat-sublabel-circular">Statistik Sistem</div>
    </div>
    <div class="stat-card-circular">
        <div class="circular-progress">
            <svg width="120" height="120">
                <circle class="background" cx="60" cy="60" r="52"></circle>
                <circle class="progress" cx="60" cy="60" r="52" 
                        style="stroke: #FCD34D; stroke-dasharray: 326.73; stroke-dashoffset: {{ 326.73 - (326.73 * min($stats['total_murids'] / 200, 1)) }};">
                </circle>
            </svg>
            <div class="text" style="color: #FCD34D">{{ $stats['total_murids'] }}</div>
        </div>
        <div class="stat-label-circular">Murid</div>
        <div class="stat-sublabel-circular">Statistik Sistem</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
    {{-- Recent Users --}}
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-users" style="color: #818CF8;"></i> User Terbaru
        </h3>
        <div style="display: grid; gap: 12px;">
            @foreach($recentUsers as $user)
                <div style="padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div style="font-weight: 600; font-size: 14px;">{{ $user->name }}</div>
                        <div style="font-size: 12px; color: var(--text-muted);">{{ $user->email }}</div>
                    </div>
                    <span class="badge" style="
                        @if($user->role === 'admin') background: rgba(239,68,68,0.15); color: #F87171;
                        @elseif($user->role === 'guru') background: rgba(79,70,229,0.15); color: #818CF8;
                        @else background: rgba(5,150,105,0.15); color: #34D399;
                        @endif
                    ">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 16px; text-align: center;">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">Lihat Semua User</a>
        </div>
    </div>

    {{-- Recent Exams --}}
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-file-alt" style="color: #34D399;"></i> Ujian Terbaru
        </h3>
        <div style="display: grid; gap: 12px;">
            @foreach($recentExams as $exam)
                <div style="padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                    <div style="font-weight: 600; font-size: 14px; margin-bottom: 4px;">{{ $exam->title }}</div>
                    <div style="font-size: 12px; color: var(--text-muted);">Oleh: {{ $exam->creator->name }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="card" style="margin-top: 24px;">
    <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
        <i class="fas fa-tools" style="color: #F59E0B;"></i> Quick Actions
    </h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
        <a href="{{ route('admin.users.index') }}" class="btn" style="background: rgba(79,70,229,0.15); color: #818CF8; border: 1px solid rgba(79,70,229,0.3);">
            <i class="fas fa-users"></i> Kelola User
        </a>
        <a href="{{ route('admin.system.index') }}" class="btn" style="background: rgba(245,158,11,0.15); color: #FCD34D; border: 1px solid rgba(245,158,11,0.3);">
            <i class="fas fa-cog"></i> Pengaturan Sistem
        </a>
        <form method="POST" action="{{ route('admin.system.clear-cache') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn" style="background: rgba(16,185,129,0.15); color: #34D399; border: 1px solid rgba(16,185,129,0.3);">
                <i class="fas fa-broom"></i> Clear Cache
            </button>
        </form>
    </div>
</div>
@endsection
