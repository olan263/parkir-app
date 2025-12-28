<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parkir-App | Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --sidebar-width: 260px;
            --primary-dark: #1e293b;
            --accent-color: #3b82f6;
        }

        body { 
            background-color: #f8f9fa; 
            font-family: 'Inter', sans-serif; 
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-width);
            background: var(--primary-dark); 
            min-height: 100vh; 
            position: fixed;
            transition: all 0.3s;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: 0.2s;
        }

        .nav-link:hover, .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid var(--accent-color);
        }

        .nav-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.4);
            padding: 1.5rem 1.5rem 0.5rem;
            font-weight: 700;
        }

        /* Content Area */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
        }

        /* Components */
        .stat-card { border: none; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1); }
        .table-container { 
            background: white; 
            border-radius: 12px; 
            padding: 1.5rem; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
        }

        /* Fix Pagination SVG */
        nav svg { width: 20px; }
        .pagination { margin-bottom: 0; }

        @media (max-width: 991px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .main-content { margin-left: 0; }
            .sidebar.active { margin-left: 0; }
        }
    </style>
</head>
<body>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h4 class="text-white fw-bold mb-0">
                <span class="text-primary">P</span> PARKIR-APP
            </h4>
        </div>
        
        <nav class="nav flex-column">
            <a class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-th-large"></i> Dashboard
            </a>

            <div class="nav-label">Operasional Gate</div>
            <a class="nav-link {{ Route::is('parkir.view.masuk') ? 'active' : '' }}" href="{{ route('parkir.view.masuk') }}">
                <i class="fas fa-sign-in-alt"></i> Gate Masuk
            </a>
            <a class="nav-link {{ Route::is('parkir.view.keluar') ? 'active' : '' }}" href="{{ route('parkir.view.keluar') }}">
                <i class="fas fa-sign-out-alt"></i> Gate Keluar
            </a>

            <div class="nav-label">Data & Laporan</div>
            <a class="nav-link" href="{{ route('admin.parkir') }}">
                <i class="fas fa-database"></i> Data Parkir
            </a>
            <a class="nav-link text-danger" href="{{ route('parkir.export.pdf') }}">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
        </nav>

        <div class="position-absolute bottom-0 p-3 w-100 border-top border-secondary">
            <small class="text-muted">© 2025 Parkir App</small>
        </div>
    </aside>

    <main class="main-content">
        <div class="d-lg-none mb-4">
            <button class="btn btn-dark" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple Toggle for Mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
</body>
</html>