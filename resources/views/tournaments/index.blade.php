<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Apex Champions Cup — Knockout Bracket Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #06090e;
            --accent-cyan: #00e5ff;
            --accent-gold: #f0c040;
            --glass-card: rgba(22, 27, 36, 0.75);
            --glass-border: rgba(255, 255, 255, 0.08);
        }
        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--bg-dark);
            color: #e8edf4;
        }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-panel {
            background: var(--glass-card);
            backdrop-filter: blur(16px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
        }
        .text-accent-cyan { color: var(--accent-cyan); }
        .text-accent-gold { color: var(--accent-gold); }
        .bg-accent-gold { background-color: var(--accent-gold); }
        .bg-accent-cyan { background-color: var(--accent-cyan); }
    </style>
</head>
<body class="min-h-screen flex flex-col">

    <!-- Header Navigation -->
    <header class="border-b border-white/10 bg-black/40 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-gradient-to-br from-amber-400 to-yellow-600 rounded-lg flex items-center justify-center font-extrabold text-black">
                        <i data-lucide="trophy" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <span class="font-black font-heading text-lg tracking-wider text-white uppercase block leading-none">Apex Champions Cup</span>
                        <span class="text-[9px] text-amber-400 tracking-widest font-bold uppercase">Knockout Tournament Hub</span>
                    </div>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-white transition-colors">Home</a>
                    <a href="{{ route('stats') }}" class="text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-white transition-colors">Stats</a>
                    <a href="{{ route('predictions.leaderboard') }}" class="text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-white transition-colors">Leaderboard</a>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-10">

        <!-- Banner / Alert -->
        @if(session('success'))
            <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-sm font-semibold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Hero Section -->
        <section class="glass-panel p-8 relative overflow-hidden bg-gradient-to-br from-amber-500/10 via-transparent to-cyan-500/10 border-amber-500/20">
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="max-w-xl space-y-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-400/10 border border-amber-400/30 text-amber-400 text-xs font-extrabold uppercase tracking-widest">
                        <i data-lucide="flame" class="w-3.5 h-3.5"></i> Knockout Phase 2024/25
                    </div>
                    <h1 class="font-heading text-4xl sm:text-5xl font-black uppercase text-white tracking-tight">The Road To Glory</h1>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Track live tournament bracket progression from the Quarter-finals to the Wembley Final. Submit your champion prediction and challenge top fans!
                    </p>
                </div>
                <div class="grid grid-cols-2 gap-4 w-full md:w-auto">
                    <div class="p-4 glass-panel text-center">
                        <div class="text-2xl font-black font-heading text-amber-400">{{ $stats['total_goals'] }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Total Goals</div>
                    </div>
                    <div class="p-4 glass-panel text-center">
                        <div class="text-2xl font-black font-heading text-cyan-400">{{ $stats['avg_goals_per_game'] }}</div>
                        <div class="text-[10px] text-gray-400 uppercase tracking-widest font-bold">Goals / Match</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Bracket Grid -->
        <section class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-heading text-2xl font-black uppercase text-white tracking-wider flex items-center gap-2">
                        <i data-lucide="git-branch" class="w-5 h-5 text-cyan-400"></i> Cup Bracket Diagram
                    </h2>
                    <p class="text-xs text-gray-400">Quarter-Finals &bull; Semi-Finals &bull; Final</p>
                </div>
                <span class="text-xs font-bold text-amber-400 bg-amber-400/10 border border-amber-400/20 px-3 py-1 rounded-full uppercase tracking-wider">
                    Final Matchweek May 18
                </span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
                <!-- Quarter Finals -->
                <div class="space-y-4">
                    <div class="text-xs font-black uppercase text-cyan-400 tracking-widest border-b border-cyan-500/20 pb-2 flex items-center justify-between">
                        <span>Quarter-Finals</span>
                        <span class="text-gray-500">4 Matches</span>
                    </div>
                    @foreach($qf as $match)
                        <div class="glass-panel p-4 space-y-2 hover:border-cyan-500/40 transition-colors">
                            <div class="flex justify-between items-center text-[10px] text-gray-400">
                                <span>{{ $match['date'] }}</span>
                                <span class="text-emerald-400 font-bold uppercase">{{ $match['status'] }}</span>
                            </div>
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-sm font-semibold {{ $match['winner'] === ($match['home']->team_name ?? '') ? 'text-amber-400 font-bold' : 'text-gray-300' }}">
                                    <span>{{ $match['home']->team_name ?? 'TBD' }}</span>
                                    <span class="font-heading font-black">{{ explode(' – ', $match['score'])[0] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm font-semibold {{ $match['winner'] === ($match['away']->team_name ?? '') ? 'text-amber-400 font-bold' : 'text-gray-300' }}">
                                    <span>{{ $match['away']->team_name ?? 'TBD' }}</span>
                                    <span class="font-heading font-black">{{ explode(' – ', $match['score'])[1] ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Semi Finals -->
                <div class="space-y-6">
                    <div class="text-xs font-black uppercase text-amber-400 tracking-widest border-b border-amber-500/20 pb-2 flex items-center justify-between">
                        <span>Semi-Finals</span>
                        <span class="text-gray-500">2 Matches</span>
                    </div>
                    @foreach($sf as $match)
                        <div class="glass-panel p-5 space-y-3 border-amber-500/20 hover:border-amber-500/40 transition-colors">
                            <div class="flex justify-between items-center text-[10px] text-gray-400">
                                <span>{{ $match['date'] }}</span>
                                <span class="text-emerald-400 font-bold uppercase">{{ $match['status'] }}</span>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex justify-between items-center text-sm font-semibold {{ $match['winner'] === $match['home_name'] ? 'text-amber-400 font-bold' : 'text-gray-300' }}">
                                    <span>{{ $match['home_name'] }}</span>
                                    <span class="font-heading font-black">{{ explode(' – ', $match['score'])[0] ?? '' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm font-semibold {{ $match['winner'] === $match['away_name'] ? 'text-amber-400 font-bold' : 'text-gray-300' }}">
                                    <span>{{ $match['away_name'] }}</span>
                                    <span class="font-heading font-black">{{ explode(' – ', $match['score'])[1] ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Final -->
                <div class="space-y-4">
                    <div class="text-xs font-black uppercase text-amber-400 tracking-widest border-b border-amber-500/30 pb-2 flex items-center justify-between">
                        <span>The Grand Final</span>
                        <span class="text-amber-400 font-bold">&starf; Winner Takes All</span>
                    </div>
                    <div class="glass-panel p-6 space-y-4 border-2 border-amber-500/30 bg-gradient-to-b from-amber-500/10 to-transparent text-center relative overflow-hidden">
                        <div class="absolute -right-6 -bottom-6 opacity-10">
                            <i data-lucide="trophy" class="w-32 h-32 text-amber-400"></i>
                        </div>
                        <div class="text-xs text-amber-400 font-bold uppercase tracking-wider">{{ $final['date'] }}</div>
                        <div class="py-2 space-y-2">
                            <div class="text-lg font-heading font-black text-white uppercase">{{ $final['home_name'] }}</div>
                            <div class="text-xs font-bold text-gray-400">VS</div>
                            <div class="text-lg font-heading font-black text-white uppercase">{{ $final['away_name'] }}</div>
                        </div>
                        <div class="pt-2 border-t border-white/10 text-xs font-extrabold text-cyan-400 uppercase tracking-widest">
                            Grand Champion Trophy
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Fan Champion Prediction Challenge -->
        <section class="glass-panel p-6 space-y-4 border-cyan-500/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-cyan-500/20 border border-cyan-500/40 flex items-center justify-center text-cyan-400">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="font-heading text-xl font-black uppercase text-white tracking-wider">Fan Bracket Prediction Challenge</h3>
                    <p class="text-xs text-gray-400">Submit your predicted Apex Champions Cup winner to earn prediction bonus points.</p>
                </div>
            </div>

            <form action="{{ route('tournaments.predict') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2">
                @csrf
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Fan / Manager Name</label>
                    <input type="text" name="user_name" required placeholder="Your Name" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-400">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Predicted Champion</label>
                    <select name="predicted_champion" required class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:border-cyan-400">
                        <option value="">Select Champion Team</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-yellow-500 text-black font-heading font-black text-sm uppercase tracking-wider py-2.5 rounded-lg hover:from-amber-300 hover:to-yellow-400 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Submit Prediction
                    </button>
                </div>
            </form>
        </section>

    </main>

    <footer class="border-t border-white/10 bg-black/40 py-6 mt-12 text-center text-xs text-gray-500">
        &copy; 2025 MP League CAPE COAST, UCC &bull; Apex Champions Cup Hub
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
