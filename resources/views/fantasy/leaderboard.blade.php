<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantasy Leaderboard — MP League</title>
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
    </style>
</head>
<body class="min-h-screen">
    <nav class="border-b border-white/10 bg-black/40 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-400 rounded flex items-center justify-center">
                        <i data-lucide="trophy" class="w-5 h-5 text-black"></i>
                    </div>
                    <span class="font-black font-heading text-xl uppercase tracking-wider text-white">MP League</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white">Home</a>
                    <a href="{{ route('fantasy.index') }}" class="text-sm font-bold uppercase tracking-wider text-amber-400">Fantasy Hub</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="glass-card p-6 md:p-8 mb-8 border border-amber-500/20">
            <h1 class="font-heading text-4xl font-black uppercase text-white mb-2">Fantasy Global Leaderboard</h1>
            <p class="text-gray-400 text-sm">Full rankings of fantasy team managers calculated by total player performance points.</p>
        </div>

        <div class="glass-card p-6">
            <div class="space-y-4">
                <div class="grid grid-cols-12 text-xs font-bold uppercase tracking-wider text-gray-400 border-b border-white/10 pb-3">
                    <div class="col-span-1">Rank</div>
                    <div class="col-span-7">Team & Manager</div>
                    <div class="col-span-4 text-right">Fantasy Points</div>
                </div>

                @foreach($leaderboard as $index => $team)
                    <div class="grid grid-cols-12 items-center p-3 rounded-xl bg-white/5 border border-white/5">
                        <div class="col-span-1 font-heading font-black text-xl {{ $index === 0 ? 'text-amber-400' : 'text-gray-400' }}">
                            #{{ $loop->iteration }}
                        </div>
                        <div class="col-span-7">
                            <div class="text-sm font-bold text-white">{{ $team->name }}</div>
                            <div class="text-xs text-gray-400">{{ $team->user->name ?? 'Fantasy Manager' }}</div>
                        </div>
                        <div class="col-span-4 text-right font-heading font-black text-xl text-amber-400">
                            {{ $team->total_points }} <span class="text-xs font-normal text-gray-400">pts</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $leaderboard->links() }}
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
