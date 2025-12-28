<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Parkir App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex h-screen">
        <aside class="w-64 bg-slate-800 text-white hidden md:flex flex-col">
            <div class="p-6 text-2xl font-bold bg-slate-900 text-center">
                🅿️ <span class="text-blue-400">PARKIR</span>-APP
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="/admin/dashboard" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition bg-slate-700">
                    <i class="fas fa-chart-line mr-3"></i> Dashboard
                </a>
                <a href="/admin/parkir" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition">
                    <i class="fas fa-car mr-3"></i> Data Parkir
                </a>
                <a href="/parkir/export/pdf" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition">
                    <i class="fas fa-file-pdf mr-3"></i> Export PDF
                </a>
            </nav>
            <div class="p-4 border-t border-slate-700 text-sm text-center text-gray-400">
                &copy; 2025 Parkir App
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-gray-700">Dashboard Admin</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Admin Parkir</span>
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>