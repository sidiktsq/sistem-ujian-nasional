@extends('layouts.app')

@section('page-title', 'Kelola User')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 700;">Kelola User</h2>
</div>

{{-- Search and Filter --}}
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" style="display: grid; grid-template-columns: 1fr 200px 120px; gap: 16px; align-items: end;">
        <div>
            <label class="form-label">Cari User</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Nama atau email...">
        </div>
        <div>
            <label class="form-label">Filter Role</label>
            <select name="role" class="form-control">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="murid" {{ request('role') === 'murid' ? 'selected' : '' }}>Murid</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Cari
        </button>
    </form>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Google ID</th>
                    <th>Terdaftar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 12px;">
                                @if($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 40px; height: 40px; border-radius: 50%; background: 
                                        @if($user->role === 'admin') rgba(239,68,68,0.2);
                                        @elseif($user->role === 'guru') rgba(79,70,229,0.2);
                                        @else rgba(5,150,105,0.2);
                                        @endif
                                        display: flex; align-items: center; justify-content: center; color: 
                                        @if($user->role === 'admin') #F87171;
                                        @elseif($user->role === 'guru') #818CF8;
                                        @else #34D399;
                                        @endif
                                        font-weight: 700;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <div style="font-weight: 600;">{{ $user->name }}</div>
                                    @if($user->google_id)
                                        <div style="font-size: 11px; color: var(--text-muted);">
                                            <i class="fab fa-google"></i> Google Account
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge" style="
                                @if($user->role === 'admin') background: rgba(239,68,68,0.15); color: #F87171;
                                @elseif($user->role === 'guru') background: rgba(79,70,229,0.15); color: #818CF8;
                                @else background: rgba(5,150,105,0.15); color: #34D399;
                                @endif
                            ">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td>
                            @if($user->google_id)
                                <i class="fab fa-google" style="color: #4285F4;"></i>
                            @else
                                <span style="color: var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td>
                            <div style="display: flex; gap: 8px;">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.toggle-role', $user) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" title="Ubah Role">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>
</div>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            Swal.fire({
                title: 'Hapus User?',
                text: "Data user ini akan dihapus permanen dari sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                background: '#1E293B',
                color: '#F1F5F9'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
