<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — MP League</title>
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
    </style>
</head>
<body class="min-h-screen">
    <nav class="border-b border-white/10 bg-black/20 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-cyan-500 rounded flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-black"></i>
                    </div>
                    <span class="font-black font-heading text-xl uppercase tracking-wider">Admin Control</span>
                </div>
                <div class="flex items-center gap-6">
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Site Home</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-400">Logout</button>
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
