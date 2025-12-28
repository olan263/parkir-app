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
        <aside class="w-64 bg-slate-800 text-white hidden md:flex flex-col shadow-xl">
            <div class="p-6 text-2xl font-bold bg-slate-900 text-center">
                🅿️ <span class="text-blue-400">PARKIR</span>-APP
            </div>

            <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-700 font-bold border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-chart-line w-6"></i> 
                    <span>Dashboard</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="text-[10px] uppercase text-gray-400 font-bold px-3 tracking-widest">Operasional Gate</p>
                </div>

                <a href="{{ route('parkir.view.masuk') }}" class="flex items-center p-3 rounded-lg hover:bg-green-600 transition group {{ request()->routeIs('parkir.view.masuk') ? 'bg-green-700 font-bold' : '' }}">
                    <i class="fas fa-sign-in-alt w-6 text-green-400 group-hover:text-white"></i> 
                    <span>Gate Masuk</span>
                </a>

                <a href="{{ route('parkir.view.keluar') }}" class="flex items-center p-3 rounded-lg hover:bg-red-600 transition group {{ request()->routeIs('parkir.view.keluar') ? 'bg-red-700 font-bold' : '' }}">
                    <i class="fas fa-sign-out-alt w-6 text-red-400 group-hover:text-white"></i> 
                    <span>Gate Keluar</span>
                </a>

                <div class="pt-6 pb-2">
                    <p class="text-[10px] uppercase text-gray-400 font-bold px-3 tracking-widest">Data & Laporan</p>
                </div>

                <a href="{{ route('admin.parkir') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('admin.parkir') ? 'bg-slate-700 font-bold border-l-4 border-blue-400' : '' }}">
                    <i class="fas fa-car w-6"></i> 
                    <span>Data Parkir</span>
                </a>

                <a href="{{ route('parkir.export.pdf') }}" class="flex items-center p-3 rounded-lg hover:bg-slate-700 transition">
                    <i class="fas fa-file-pdf w-6 text-red-400"></i> 
                    <span>Export PDF</span>
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700 text-xs text-center text-gray-400">
                &copy; 2025 Parkir App
            </div>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-white shadow-sm flex items-center justify-between px-8 border-b">
                <div class="flex items-center">
                    <button class="md:hidden mr-4 text-gray-600">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="text-xl font-semibold text-gray-700">
                        @if(request()->routeIs('admin.dashboard')) Dashboard Admin 
                        @elseif(request()->routeIs('parkir.view.masuk')) Operasional Gate Masuk
                        @elseif(request()->routeIs('parkir.view.keluar')) Operasional Gate Keluar
                        @else Management Parkir @endif
                    </h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-gray-700 leading-none">Admin Parkir</p>
                        <p class="text-[10px] text-gray-400 uppercase">Administrator</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white shadow-md border-2 border-white">
                        <i class="fas fa-user text-sm"></i>
                    </div>
                </div>
            </header>

            <div class="flex-1 overflow-y-auto p-4 md:p-8 bg-gray-50">
                @yield('content')
            </div>
        </main>
    </div>

</body>
</html>