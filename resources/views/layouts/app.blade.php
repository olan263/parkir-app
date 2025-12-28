<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir-App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .sidebar { width: 250px; background: #1e293b; min-height: 100vh; position: fixed; color: white; }
        .main-content { margin-left: 250px; padding: 30px; }
        .nav-link { color: #cbd5e1; padding: 12px 20px; }
        .nav-link:hover, .nav-link.active { background: #334155; color: white; border-left: 4px solid #3b82f6; }
        /* Fix tampilan pagination biar gak ada garis aneh */
        nav[role="navigation"] svg { width: 15px !important; }
        .pagination { --bs-pagination-padding-x: 15px; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-4"><h4 class="fw-bold text-primary">🅿️ PARKIR-APP</h4></div>
        <nav class="nav flex-column">
            <a class="nav-link {{ Request::is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line me-2"></i> Dashboard</a>
            <a class="nav-link {{ Request::is('parkir/masuk*') ? 'active' : '' }}" href="{{ route('parkir.view.masuk') }}"><i class="fas fa-sign-in-alt me-2"></i> Gate Masuk</a>
            <a class="nav-link {{ Request::is('parkir/keluar*') ? 'active' : '' }}" href="{{ route('parkir.view.keluar') }}"><i class="fas fa-sign-out-alt me-2"></i> Gate Keluar</a>
        </nav>
    </div>
    <div class="main-content">
        @yield('content')
    </div>
</body>
</html>