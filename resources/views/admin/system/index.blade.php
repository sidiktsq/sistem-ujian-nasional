@extends('layouts.app')

@section('page-title', 'Pengaturan Sistem')

@section('content')
<div style="max-width: 800px;">
    <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 24px;">Pengaturan Sistem</h2>
    
    {{-- System Information --}}
    <div class="card" style="margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-info-circle" style="color: #818CF8;"></i> Informasi Sistem
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
            <div style="padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Laravel Version</div>
                <div style="font-size: 16px; font-weight: 600;">{{ app()->version() }}</div>
            </div>
            <div style="padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">PHP Version</div>
                <div style="font-size: 16px; font-weight: 600;">{{ PHP_VERSION }}</div>
            </div>
            <div style="padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Environment</div>
                <div style="font-size: 16px; font-weight: 600;">{{ config('app.env') }}</div>
            </div>
            <div style="padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Debug Mode</div>
                <div style="font-size: 16px; font-weight: 600;">
                    {{ config('app.debug') ? 'ON' : 'OFF' }}
                </div>
            </div>
        </div>
    </div>

    {{-- System Actions --}}
    <div class="card" style="margin-bottom: 24px;">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-tools" style="color: #F59E0B;"></i> Maintenance Actions
        </h3>
        <div style="display: grid; gap: 16px;">
            <form method="POST" action="{{ route('admin.system.clear-cache') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                @csrf
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Clear All Cache</div>
                    <div style="font-size: 13px; color: var(--text-muted);">Membersihkan cache aplikasi, config, view, dan route</div>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-broom"></i> Clear Cache
                </button>
            </form>
            
            <form method="POST" action="{{ route('admin.system.migrate') }}" style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;" onsubmit="return confirm('Yakin ingin menjalankan migration? Ini akan memperbarui database structure.')">
                @csrf
                <div>
                    <div style="font-weight: 600; margin-bottom: 4px;">Run Database Migration</div>
                    <div style="font-size: 13px; color: var(--text-muted);">Menjalankan migration yang belum di-execute</div>
                </div>
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-database"></i> Migrate
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics -->
    <div class="card">
        <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
            <i class="fas fa-chart-bar" style="color: #34D399;"></i> Statistik Database
        </h3>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 16px;">
            <div style="text-align: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #818CF8;">
                    {{ \App\Models\User::count() }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">Users</div>
            </div>
            <div style="text-align: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #34D399;">
                    {{ \App\Models\Exam::count() }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">Ujian</div>
            </div>
            <div style="text-align: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #FCD34D;">
                    {{ \App\Models\Question::count() }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">Soal</div>
            </div>
            <div style="text-align: center; padding: 16px; background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 8px;">
                <div style="font-size: 24px; font-weight: 700; color: #F87171;">
                    {{ \App\Models\ExamSession::count() }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">Sessions</div>
            </div>
        </div>
    </div>
</div>
@endsection
