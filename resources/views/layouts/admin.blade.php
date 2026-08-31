<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Control Panel — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gold': '#f0c040',
                        'gold2': '#c8930a',
                        'gold3': '#fff0a0',
                        'accent': '#00e5ff',
                        'accent2': '#007fa8',
                        'custom-red': '#ff3b3b',
                        'custom-green': '#22c55e',
                        'bg-dark': '#06090e',
                        'bg-dark2': '#0d1117',
                        'bg-dark3': '#161b24',
                        'bg-dark4': '#1e2530',
                        'border-dark': '#1e2a38',
                        'border-dark2': '#2a3848',
                        'text-light': '#e8edf4',
                        'muted': '#6b7a8d',
                    },
                    fontFamily: {
                        'display': ['Bebas Neue', 'sans-serif'],
                        'heading': ['Barlow Condensed', 'sans-serif'],
                        'body': ['Barlow', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --bg-dark: #06090e;
            --accent-gold: #f0c040;
            --glass-white: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--bg-dark);
            color: #e8edf4;
        }
        .glass-card {
            background: var(--glass-white);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
        }
        .text-accent-gold { color: var(--accent-gold); }
        .bg-accent-gold { background-color: var(--accent-gold); }

        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #0d1117;
        }
        ::-webkit-scrollbar-thumb {
            background: #1e2a38;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #f0c040;
        }
    </style>
</head>
<body class="min-h-screen bg-bg-dark text-text-light flex flex-col font-body antialiased selection:bg-gold selection:text-black">

    <!-- Top Live Status Ticker -->
    <div class="h-8 bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 border-b border-border-dark flex items-center overflow-hidden px-4 text-xs">
        <div class="flex items-center gap-2 bg-custom-red/20 text-custom-red px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider animate-pulse mr-4">
            <span class="w-1.5 h-1.5 rounded-full bg-custom-red"></span>
            System Live
        </div>
        <div class="flex items-center gap-6 text-[11px] text-muted overflow-x-auto whitespace-nowrap">
            <span>⚡ Administrator Portal</span>
            <span class="text-border-dark2">•</span>
            <span>Season 2024/25 Active</span>
            <span class="text-border-dark2">•</span>
            <span>CAPE COAST, UCC League</span>
        </div>
        <div class="ml-auto flex items-center gap-3 text-[11px] text-gold font-mono">
            <span>Matchweek 32</span>
        </div>
    </div>

    <!-- Navigation Header -->
    <nav class="border-b border-border-dark bg-bg-dark2/90 backdrop-blur-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Brand & Links -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 no-underline group">
                        <div class="w-10 h-10 bg-gradient-to-br from-gold to-gold2 rounded-xl flex items-center justify-center shadow-lg shadow-gold/10 group-hover:scale-105 transition-transform">
                            <i data-lucide="shield-check" class="w-6 h-6 text-black"></i>
                        </div>
                        <div>
                            <span class="font-black font-heading text-lg tracking-wider text-text-light block leading-none">MP LEAGUE</span>
                            <span class="text-[9px] text-gold font-bold tracking-widest uppercase">Admin Control Hub</span>
                        </div>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center gap-1 bg-bg-dark3/80 p-1 rounded-xl border border-border-dark">
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.teams.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.teams.*') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            Teams
                        </a>
                        <a href="{{ route('admin.games.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.games.*') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            Matches
                        </a>
                        <a href="{{ route('admin.articles.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.articles.*') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            News
                        </a>
                        <a href="{{ route('admin.scouts.index') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.scouts.*') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            Scouts
                        </a>
                        <a href="{{ route('admin.payments') }}" class="px-4 py-1.5 rounded-lg text-xs font-heading font-bold uppercase tracking-wider transition-all {{ request()->routeIs('admin.payments') ? 'bg-gold text-black shadow-md' : 'text-muted hover:text-text-light hover:bg-white/5' }}">
                            Payments
                        </a>
                    </div>
                </div>

                <!-- User Controls & Home -->
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" target="_blank" class="hidden sm:flex items-center gap-1.5 text-xs font-heading font-bold uppercase tracking-wider text-muted hover:text-accent bg-bg-dark3 px-3 py-1.5 rounded-lg border border-border-dark hover:border-accent/40 transition-all">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                        Live Portal
                    </a>
                    <div class="h-6 w-px bg-border-dark hidden sm:block"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-accent to-accent2 flex items-center justify-center text-black font-extrabold text-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                        </div>
                        <div class="hidden lg:block text-left">
                            <div class="text-xs font-bold text-text-light leading-none">{{ auth()->user()->name ?? 'Administrator' }}</div>
                            <div class="text-[9px] text-accent uppercase font-bold tracking-wider">Super Admin</div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="ml-1">
                            @csrf
                            <button type="submit" title="Logout" class="p-2 rounded-lg text-muted hover:text-custom-red hover:bg-custom-red/10 transition-colors">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Body -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-border-dark bg-bg-dark2 py-6 text-center text-xs text-muted">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2">
            <div>© {{ date('Y') }} MP League Management System — CAPE COAST, UCC</div>
            <div class="flex items-center gap-4 text-[11px]">
                <span class="text-custom-green">● Server Online</span>
                <a href="{{ route('home') }}" class="text-muted hover:text-gold transition-colors">Front Office</a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
