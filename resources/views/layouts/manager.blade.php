<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #06090e;
            --accent-gold: #f0c040;
            --glass-white: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--bg-dark);
            color: #e8edf4;
            background-image:
                radial-gradient(circle at 20% 30%, rgba(240, 192, 64, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 80% 70%, rgba(0, 229, 255, 0.05) 0%, transparent 40%);
        }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card {
            background: var(--glass-white);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
        }
        .text-accent-gold { color: var(--accent-gold); }
        .bg-accent-gold { background-color: var(--accent-gold); }
        .bg-bg-dark { background-color: var(--bg-dark); }
    </style>
</head>
<body class="min-h-screen">
    <nav class="border-b border-white/10 bg-black/20 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-accent-gold rounded flex items-center justify-center">
                        <i data-lucide="layout-dashboard" class="w-5 h-5 text-black"></i>
                    </div>
                    <span class="font-black font-heading text-xl uppercase tracking-wider">Manager Panel</span>
                </div>
                <div class="hidden md:flex items-center gap-6 ml-10">
                    <a href="{{ route('manager.dashboard') }}" class="text-sm font-bold uppercase tracking-wider {{ request()->routeIs('manager.dashboard') ? 'text-accent-gold' : 'text-gray-400 hover:text-white' }}">Squad</a>
                    <a href="{{ route('manager.training.index') }}" class="text-sm font-bold uppercase tracking-wider {{ request()->routeIs('manager.training.index') ? 'text-accent-gold' : 'text-gray-400 hover:text-white' }}">Training</a>
                    <a href="{{ route('manager.injuries.index') }}" class="text-sm font-bold uppercase tracking-wider {{ request()->routeIs('manager.injuries.index') ? 'text-accent-gold' : 'text-gray-400 hover:text-white' }}">Injuries</a>
                    <a href="{{ route('manager.transfers.index') }}" class="text-sm font-bold uppercase tracking-wider {{ request()->routeIs('manager.transfers.index') ? 'text-accent-gold' : 'text-gray-400 hover:text-white' }}">Transfers</a>

                    <div class="relative group">
                        <button class="text-sm font-bold uppercase tracking-wider text-gray-400 group-hover:text-white flex items-center gap-1">
                            Operations <i data-lucide="chevron-down" class="w-3 h-3"></i>
                        </button>
                        <div class="absolute top-full left-0 mt-2 w-48 glass-card border border-white/10 hidden group-hover:block z-50 overflow-hidden">
                            <a href="{{ route('manager.finance.index') }}" class="block px-4 py-3 text-sm text-gray-400 hover:text-accent-gold hover:bg-white/5 transition-colors">Finance</a>
                            <a href="{{ route('manager.equipment.index') }}" class="block px-4 py-3 text-sm text-gray-400 hover:text-accent-gold hover:bg-white/5 transition-colors">Equipment</a>
                            <a href="{{ route('manager.scouting.index') }}" class="block px-4 py-3 text-sm text-gray-400 hover:text-accent-gold hover:bg-white/5 transition-colors">Scouting</a>
                            <a href="{{ route('manager.reports.index') }}" class="block px-4 py-3 text-sm text-gray-400 hover:text-accent-gold hover:bg-white/5 border-t border-white/5 transition-colors">Reports</a>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <span class="text-sm text-gray-400 hidden lg:inline">Welcome, {{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-400 transition-colors">Sign Out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
