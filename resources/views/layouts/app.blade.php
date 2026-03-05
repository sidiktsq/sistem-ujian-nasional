<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Sistem Ujian Online' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --guru-primary: #4F46E5;
            --guru-secondary: #6366F1;
            --murid-primary: #059669;
            --murid-secondary: #10B981;
            --dark: #0F172A;
            --dark2: #1E293B;
            --dark3: #334155;
            --card: #1E293B;
            --border: rgba(255,255,255,0.08);
            --text: #F1F5F9;
            --text-muted: #94A3B8;
            --red: #EF4444;
            --yellow: #F59E0B;
            --sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--dark);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--dark2);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            z-index: 100;
            transition: transform .3s;
        }
        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-brand .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand .logo-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px;
        }
        .sidebar-brand .logo-text { font-weight: 700; font-size: 15px; line-height: 1.3; }
        .sidebar-brand .logo-text small { display: block; font-size: 11px; font-weight: 400; color: var(--text-muted); }

        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-user .avatar {
            width: 36px; height: 36px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }
        .sidebar-user .user-info { flex: 1; min-width: 0; }
        .sidebar-user .user-name { font-size: 13px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .sidebar-user .user-role { font-size: 11px; color: var(--text-muted); }

        .sidebar-nav { flex: 1; padding: 12px 0; overflow-y: auto; }
        .nav-section { padding: 8px 20px 4px; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; margin: 2px 8px;
            border-radius: 8px; color: var(--text-muted);
            text-decoration: none; font-size: 14px; font-weight: 500;
            transition: all .2s;
        }
        .nav-item:hover, .nav-item.active {
            color: var(--text);
            background: rgba(255,255,255,0.06);
        }
        .nav-item.active { color: #fff; }
        .nav-item i { width: 18px; text-align: center; font-size: 15px; }

        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .logout-btn {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: #FDA4AF; font-size: 14px; font-weight: 500;
            cursor: pointer; border: none; background: rgba(239,68,68,.08);
            width: 100%; transition: background .2s;
        }
        .logout-btn:hover { background: rgba(239,68,68,.16); }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            background: var(--dark2);
            border-bottom: 1px solid var(--border);
            padding: 14px 28px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 18px; font-weight: 700; }
        .topbar-actions { display: flex; gap: 10px; align-items: center; }

        .page-content { padding: 28px; flex: 1; }

        /* ── Cards ── */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
        }
        .card-sm { padding: 16px; }

        /* ── Stats Grid ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            display: flex; align-items: flex-start; gap: 16px;
        }
        .stat-icon {
            width: 48px; height: 48px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; flex-shrink: 0;
        }
        .stat-value { font-size: 28px; font-weight: 800; line-height: 1; }
        .stat-label { font-size: 13px; color: var(--text-muted); margin-top: 4px; }

        /* ── Table ── */
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; border-bottom: 1px solid var(--border); }
        td { padding: 14px 16px; font-size: 14px; border-bottom: 1px solid rgba(255,255,255,0.04); }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── Badges ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;
        }
        .badge-green { background: rgba(16,185,129,.15); color: #34D399; }
        .badge-red { background: rgba(239,68,68,.15); color: #F87171; }
        .badge-blue { background: rgba(99,102,241,.15); color: #818CF8; }
        .badge-yellow { background: rgba(245,158,11,.15); color: #FCD34D; }
        .badge-gray { background: rgba(148,163,184,.12); color: var(--text-muted); }

        /* ── Buttons ── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 9px 18px; border-radius: 10px; font-size: 14px; font-weight: 600;
            cursor: pointer; border: none; text-decoration: none; transition: all .2s;
        }
        .btn-primary { background: var(--guru-primary); color: #fff; }
        .btn-primary:hover { background: var(--guru-secondary); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(79,70,229,.4); }
        .btn-green { background: var(--murid-primary); color: #fff; }
        .btn-green:hover { background: var(--murid-secondary); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(5,150,105,.4); }
        .btn-danger { background: rgba(239,68,68,.15); color: #F87171; border: 1px solid rgba(239,68,68,.2); }
        .btn-danger:hover { background: rgba(239,68,68,.25); }
        .btn-secondary { background: rgba(255,255,255,.08); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: rgba(255,255,255,.12); }
        .btn-sm { padding: 6px 12px; font-size: 13px; }

        /* ── Forms ── */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text-muted); }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: var(--dark) !important; border: 1px solid var(--border);
            border-radius: 10px; color: var(--text) !important; font-size: 14px;
            transition: border-color .2s;
        }
        .form-control::placeholder { color: var(--text-muted); opacity: 1; }
        .form-control::-webkit-input-placeholder { color: var(--text-muted); }
        .form-control:-ms-input-placeholder { color: var(--text-muted); }
        .form-control:focus { 
            outline: none; border-color: var(--guru-primary); 
            box-shadow: 0 0 0 3px rgba(79,70,229,.15); 
            color: var(--text) !important;
            background: var(--dark) !important;
        }
        .form-control:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px var(--dark) inset !important;
            -webkit-text-fill-color: var(--text) !important;
        }
        .form-control:-webkit-autofill:hover {
            -webkit-box-shadow: 0 0 0 1000px var(--dark) inset !important;
            -webkit-text-fill-color: var(--text) !important;
        }
        .form-control:-webkit-autofill:focus {
            -webkit-box-shadow: 0 0 0 1000px var(--dark) inset !important;
            -webkit-text-fill-color: var(--text) !important;
        }
        textarea.form-control { resize: vertical; min-height: 100px; }
        select.form-control { cursor: pointer; color: var(--text) !important; background: var(--dark) !important; }
        .form-check { display: flex; align-items: center; gap: 10px; }
        .form-check input[type=checkbox] { width: 18px; height: 18px; cursor: pointer; accent-color: var(--guru-primary); }

        /* ── Alert / Flash ── */
        .alert {
            padding: 14px 18px; border-radius: 10px; margin-bottom: 20px;
            display: flex; align-items: flex-start; gap: 10px; font-size: 14px;
        }
        .alert-success { background: rgba(16,185,129,.12); border: 1px solid rgba(16,185,129,.25); color: #34D399; }
        .alert-error { background: rgba(239,68,68,.12); border: 1px solid rgba(239,68,68,.25); color: #F87171; }
        .alert-info { background: rgba(99,102,241,.12); border: 1px solid rgba(99,102,241,.25); color: #818CF8; }

        /* ── Pagination ── */
        .pagination { display: flex; gap: 4px; margin-top: 20px; }
        .pagination a, .pagination span {
            padding: 7px 13px; border-radius: 8px; font-size: 13px;
            background: var(--dark2); border: 1px solid var(--border); color: var(--text-muted); text-decoration: none;
        }
        .pagination .active span { background: var(--guru-primary); border-color: var(--guru-primary); color: #fff; }

        /* ── Mobile hamburger ── */
        .hamburger { display: none; background: none; border: none; color: var(--text); font-size: 20px; cursor: pointer; padding: 4px; }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .hamburger { display: block; }
            .page-content { padding: 16px; }
        }
    </style>
    @stack('styles')
</head>
<body>

@php $role = auth()->user()->role; @endphp

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="logo">
            <div class="logo-icon" style="background: {{ $role === 'guru' ? 'rgba(79,70,229,.2)' : 'rgba(5,150,105,.2)' }}; color: {{ $role === 'guru' ? '#818CF8' : '#34D399' }}">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="logo-text">
                ExamPro
                <small>Sistem Ujian Online</small>
            </div>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="avatar" style="background: {{ $role === 'guru' ? 'rgba(79,70,229,.25)' : 'rgba(5,150,105,.25)' }}; color: {{ $role === 'guru' ? '#818CF8' : '#34D399' }}">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="user-info">
            <div class="user-name">{{ auth()->user()->name }}</div>
            <div class="user-role">{{ ucfirst($role) }}</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @if($role === 'guru')
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" style="{{ request()->routeIs('guru.dashboard') ? 'color:#818CF8; background:rgba(79,70,229,.15)' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('guru.exams.index') }}" class="nav-item {{ request()->routeIs('guru.exams.*') ? 'active' : '' }}" style="{{ request()->routeIs('guru.exams.*') ? 'color:#818CF8; background:rgba(79,70,229,.15)' : '' }}">
                <i class="fas fa-file-alt"></i> Kelola Ujian
            </a>
        @else
            <div class="nav-section">Menu Utama</div>
            <a href="{{ route('murid.dashboard') }}" class="nav-item {{ request()->routeIs('murid.dashboard') ? 'active' : '' }}" style="{{ request()->routeIs('murid.dashboard') ? 'color:#34D399; background:rgba(5,150,105,.15)' : '' }}">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="{{ route('murid.exams.index') }}" class="nav-item {{ request()->routeIs('murid.exams.*') ? 'active' : '' }}" style="{{ request()->routeIs('murid.exams.*') ? 'color:#34D399; background:rgba(5,150,105,.15)' : '' }}">
                <i class="fas fa-pencil-alt"></i> Kerjakan Ujian
            </a>
            <a href="{{ route('murid.results.index') }}" class="nav-item {{ request()->routeIs('murid.results.*') ? 'active' : '' }}" style="{{ request()->routeIs('murid.results.*') ? 'color:#34D399; background:rgba(5,150,105,.15)' : '' }}">
                <i class="fas fa-trophy"></i> Nilai Saya
            </a>
        @endif
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<div class="main-content">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:12px">
            <button class="hamburger" onclick="document.getElementById('sidebar').classList.toggle('open')">
                <i class="fas fa-bars"></i>
            </button>
            <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        </div>
        <div class="topbar-actions">
            @yield('topbar-actions')
        </div>
    </div>

    <div class="page-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info"><i class="fas fa-info-circle"></i> {{ session('info') }}</div>
        @endif

        @yield('content')
    </div>
</div>

<script>
    // Close sidebar on outside click (mobile)
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth <= 768 && !sidebar.contains(e.target) && !e.target.closest('.hamburger')) {
            sidebar.classList.remove('open');
        }
    });
</script>
@stack('scripts')
</body>
</html>
