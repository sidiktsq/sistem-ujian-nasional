<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Ujian Online' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #0F172A;
            color: #F1F5F9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-bg {
            position: fixed; inset: 0;
            background: radial-gradient(ellipse at 30% 20%, rgba(79,70,229,.18) 0%, transparent 60%),
                        radial-gradient(ellipse at 80% 80%, rgba(5,150,105,.12) 0%, transparent 50%);
        }
        .auth-container {
            position: relative; z-index: 1;
            width: 100%; max-width: 440px;
            padding: 20px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-logo .icon {
            width: 64px; height: 64px; border-radius: 18px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            display: flex; align-items: center; justify-content: center;
            font-size: 28px; margin: 0 auto 12px;
            box-shadow: 0 8px 32px rgba(79,70,229,.4);
        }
        .auth-logo h1 { font-size: 24px; font-weight: 800; }
        .auth-logo p { font-size: 14px; color: #94A3B8; margin-top: 4px; }
        .auth-card {
            background: #1E293B;
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 20px;
            padding: 32px;
        }
        .auth-card h2 { font-size: 20px; font-weight: 700; margin-bottom: 6px; }
        .auth-card p.sub { font-size: 14px; color: #94A3B8; margin-bottom: 28px; }
        .form-group { margin-bottom: 18px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #94A3B8; margin-bottom: 8px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B; font-size: 15px; }
        .form-control {
            width: 100%; padding: 11px 14px 11px 42px;
            background: #0F172A; border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; color: #F1F5F9; font-size: 14px;
            transition: border-color .2s;
        }
        .form-control:focus { outline: none; border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79,70,229,.15); }
        select.form-control { padding-left: 42px; cursor: pointer; }
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4F46E5, #7C3AED);
            border: none; border-radius: 10px; color: #fff;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s; margin-top: 8px;
        }
        .btn-submit:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(79,70,229,.4); }
        .btn-google {
            width: 100%; padding: 13px;
            background: #fff; border: 1px solid #dadce0;
            border-radius: 10px; color: #3c4043;
            font-size: 15px; font-weight: 700; cursor: pointer;
            transition: all .2s; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-google:hover { 
            background: #f8f9fa; 
            box-shadow: 0 1px 2px 0 rgba(60,64,67,0.3), 0 1px 3px 1px rgba(60,64,67,0.15);
            transform: translateY(-1px);
        }
        .auth-footer { text-align: center; margin-top: 20px; font-size: 14px; color: #64748B; }
        .auth-footer a { color: #818CF8; text-decoration: none; font-weight: 600; }
        .auth-footer a:hover { color: #A5B4FC; }
        .error-text { color: #F87171; font-size: 12px; margin-top: 6px; }
        .alert-error {
            background: rgba(239,68,68,.1); border: 1px solid rgba(239,68,68,.2);
            border-radius: 10px; padding: 12px 16px; margin-bottom: 20px;
            font-size: 13px; color: #F87171; display: flex; align-items: center; gap: 8px;
        }
    </style>
</head>
<body>
<div class="auth-bg"></div>
<div class="auth-container">
    <div class="auth-logo">
        <div class="icon"><i class="fas fa-graduation-cap" style="color:#fff"></i></div>
        <h1>Ujian Nasional</h1>
        <p>Sistem Ujian Online</p>
    </div>
    @yield('content')
</div>
</body>
</html>
