@extends('layouts.app')

@section('page-title', 'Profil Admin')

@section('content')
<div style="max-width: 800px;">
    <div class="card">
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 24px;">
            @if(auth()->user()->avatar)
                <img src="{{ auth()->user()->avatar }}?t={{ time() }}" alt="{{ auth()->user()->name }}" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid var(--card);">
            @else
                <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(239,68,68,0.2); display: flex; align-items: center; justify-content: center; color: #F87171; font-size: 32px; font-weight: 700;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 4px;">{{ auth()->user()->name }}</h2>
                <div style="color: var(--text-muted);">{{ auth()->user()->email }}</div>
                <span class="badge" style="background: rgba(239,68,68,0.15); color: #F87171; margin-top: 8px; display: inline-block;">
                    <i class="fas fa-crown"></i> {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
            <div>
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-muted);">Informasi Akun</h3>
                <div style="display: grid; gap: 12px;">
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">User ID</div>
                        <div style="font-weight: 600;">{{ auth()->user()->id }}</div>
                    </div>
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Terdaftar Sejak</div>
                        <div style="font-weight: 600;">{{ auth()->user()->created_at->format('d F Y') }}</div>
                    </div>
                    @if(auth()->user()->google_id)
                    <div>
                        <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 4px;">Google ID</div>
                        <div style="font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <i class="fab fa-google" style="color: #4285F4;"></i>
                            {{ auth()->user()->google_id }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 16px; color: var(--text-muted);">Quick Actions</h3>
                <div style="display: grid; gap: 12px;">
                    <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary">
                        <i class="fas fa-edit"></i> Edit Profil
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn" style="background: rgba(79,70,229,0.15); color: #818CF8; border: 1px solid rgba(79,70,229,0.3);">
                        <i class="fas fa-users"></i> Kelola User
                    </a>
                    <a href="{{ route('admin.system.index') }}" class="btn" style="background: rgba(245,158,11,0.15); color: #FCD34D; border: 1px solid rgba(245,158,11,0.3);">
                        <i class="fas fa-cog"></i> Pengaturan Sistem
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
