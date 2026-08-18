<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apex Champions Cup — Knockout Arena</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
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
                radial-gradient(circle at 50% 0%, rgba(240, 192, 64, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(0, 229, 255, 0.05) 0%, transparent 50%);
        }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card {
            background: var(--glass-white);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
        }
        .bracket-connector {
            position: relative;
        }
        .bracket-connector::after {
            content: '';
            position: absolute;
            right: -2rem;
            top: 50%;
            width: 2rem;
            height: 2px;
            background: rgba(255, 255, 255, 0.15);
        }
    </style>
</head>
<body class="min-h-screen pb-12">

    <!-- Header Navigation -->
    <nav class="border-b border-white/10 bg-black/40 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="w-10 h-10 bg-gradient-to-tr from-amber-500 to-yellow-300 rounded-xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                        <i data-lucide="trophy" class="w-6 h-6 text-black"></i>
                    </a>
                    <div>
                        <span class="font-black font-heading text-xl uppercase tracking-wider text-white">Apex Champions Cup</span>
                        <span class="text-xs text-amber-400 font-semibold block -mt-1">Knockout Bracket Arena</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">Home</a>
                    <a href="{{ route('stats') }}" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">Stats</a>
                    <a href="{{ route('predictions.leaderboard') }}" class="text-sm font-semibold text-gray-400 hover:text-white transition-colors">Predictions</a>
                    @auth
                        @if(auth()->user()->isManager())
                            <a href="{{ route('manager.dashboard') }}" class="text-sm font-bold uppercase tracking-wider text-amber-400 hover:text-amber-300">Manager Hub</a>
                        @elseif(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold uppercase tracking-wider text-cyan-400 hover:text-cyan-300">Admin Control</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-wider bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg border border-white/10 transition-colors">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">

        <!-- Hero Header -->
        <div class="glass-card p-8 mb-8 text-center relative overflow-hidden border border-amber-500/20">
            <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-widest mb-4">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Season 2024 Knockout Stage
            </div>

            <h1 class="text-4xl md:text-6xl font-black font-heading uppercase tracking-tight text-white mb-2">
                The Apex Champions Cup
            </h1>
            <p class="text-gray-400 max-w-2xl mx-auto text-sm md:text-base mb-6">
                Eight elite clubs. Single-elimination aggregate drama. Predict knockout winners and earn bonus points for the global leaderboard.
            </p>

            <!-- Tournament Quick Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-3xl mx-auto pt-4 border-t border-white/10">
                <div>
                    <span class="text-2xl font-black font-heading text-white">{{ $tournamentStats['total_goals'] }}</span>
                    <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider">Tournament Goals</span>
                </div>
                <div>
                    <span class="text-2xl font-black font-heading text-amber-400">{{ $tournamentStats['avg_goals_per_game'] }}</span>
                    <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider">Avg Goals / Match</span>
                </div>
                <div>
                    <span class="text-2xl font-black font-heading text-cyan-400">{{ $tournamentStats['top_scorer'] }}</span>
                    <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider">Top Scorer</span>
                </div>
                <div>
                    <span class="text-2xl font-black font-heading text-emerald-400">{{ $tournamentStats['clean_sheets'] }}</span>
                    <span class="block text-xs text-gray-400 font-medium uppercase tracking-wider">Clean Sheets</span>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-300 text-sm font-semibold flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Interactive Bracket Grid -->
        <div class="mb-12">
            <h2 class="text-xl font-bold font-heading uppercase tracking-wider text-amber-400 mb-6 flex items-center gap-2">
                <i data-lucide="git-merge" class="w-5 h-5"></i> Knockout Bracket Progression
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">

                <!-- Quarterfinals Column -->
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-gray-400 border-b border-white/10 pb-2 flex items-center justify-between">
                        <span>Quarterfinals</span>
                        <span class="text-emerald-400 font-semibold">Completed</span>
                    </div>

                    @foreach($quarterfinals as $qf)
                        <div class="glass-card p-4 hover:border-amber-500/40 transition-colors">
                            <div class="flex justify-between text-xs text-gray-400 mb-2 font-medium">
                                <span>{{ $qf['stage'] }}</span>
                                <span class="text-amber-400 font-bold">FT</span>
                            </div>
                            <!-- Team 1 -->
                            <div class="flex justify-between items-center py-1.5 {{ $qf['winner']->id === $qf['team1']->id ? 'font-bold text-white' : 'text-gray-400' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs font-black">
                                        {{ substr($qf['team1']->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm truncate max-w-[120px]">{{ $qf['team1']->name }}</span>
                                </div>
                                <span class="text-sm font-heading font-black">{{ $qf['score1'] }}</span>
                            </div>
                            <!-- Team 2 -->
                            <div class="flex justify-between items-center py-1.5 {{ $qf['winner']->id === $qf['team2']->id ? 'font-bold text-white' : 'text-gray-400' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center text-xs font-black">
                                        {{ substr($qf['team2']->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm truncate max-w-[120px]">{{ $qf['team2']->name }}</span>
                                </div>
                                <span class="text-sm font-heading font-black">{{ $qf['score2'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Semifinals Column -->
                <div class="space-y-6">
                    <div class="text-xs font-bold uppercase tracking-widest text-gray-400 border-b border-white/10 pb-2 flex items-center justify-between">
                        <span>Semifinals</span>
                        <span class="text-emerald-400 font-semibold">Completed</span>
                    </div>

                    @foreach($semifinals as $sf)
                        <div class="glass-card p-5 border-amber-500/30 hover:border-amber-500 transition-colors shadow-lg">
                            <div class="flex justify-between text-xs text-amber-400 mb-3 font-bold">
                                <span>{{ $sf['stage'] }}</span>
                                <span>FT</span>
                            </div>
                            <!-- Team 1 -->
                            <div class="flex justify-between items-center py-2 border-b border-white/5 {{ $sf['winner']->id === $sf['team1']->id ? 'font-bold text-white' : 'text-gray-400' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-black">
                                        {{ substr($sf['team1']->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold">{{ $sf['team1']->name }}</span>
                                </div>
                                <span class="text-base font-heading font-black">{{ $sf['score1'] }}</span>
                            </div>
                            <!-- Team 2 -->
                            <div class="flex justify-between items-center py-2 {{ $sf['winner']->id === $sf['team2']->id ? 'font-bold text-white' : 'text-gray-400' }}">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xs font-black">
                                        {{ substr($sf['team2']->name, 0, 1) }}
                                    </div>
                                    <span class="text-sm font-semibold">{{ $sf['team2']->name }}</span>
                                </div>
                                <span class="text-base font-heading font-black">{{ $sf['score2'] }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Grand Final Column -->
                <div class="space-y-4">
                    <div class="text-xs font-bold uppercase tracking-widest text-amber-400 border-b border-amber-500/30 pb-2 flex items-center justify-between">
                        <span>Grand Final</span>
                        <span class="text-cyan-400 font-bold animate-pulse">Upcoming</span>
                    </div>

                    <div class="glass-card p-6 border-2 border-amber-500/60 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 bg-gradient-to-l from-amber-500/30 to-transparent w-24 h-full pointer-events-none"></div>

                        <div class="text-center mb-4">
                            <i data-lucide="crown" class="w-8 h-8 text-amber-400 mx-auto mb-1"></i>
                            <span class="text-xs font-bold uppercase tracking-wider text-amber-400">Cup Final Matchday</span>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $final['date'] }} · {{ $final['stadium'] }}</p>
                        </div>

                        <!-- Finalist 1 -->
                        <div class="flex justify-between items-center py-3 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-black flex items-center justify-center text-sm font-black">
                                    {{ substr($final['team1']->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-base font-bold text-white block">{{ $final['team1']->name }}</span>
                                    <span class="text-xs text-gray-400">Rating: {{ $final['team1']->rating }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold bg-amber-500/20 text-amber-400 px-2.5 py-1 rounded-md">Finalist</span>
                        </div>

                        <div class="text-center my-2 text-xs font-bold text-gray-500 uppercase tracking-widest">VS</div>

                        <!-- Finalist 2 -->
                        <div class="flex justify-between items-center py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-amber-500 to-yellow-300 text-black flex items-center justify-center text-sm font-black">
                                    {{ substr($final['team2']->name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="text-base font-bold text-white block">{{ $final['team2']->name }}</span>
                                    <span class="text-xs text-gray-400">Rating: {{ $final['team2']->rating }}</span>
                                </div>
                            </div>
                            <span class="text-xs font-bold bg-amber-500/20 text-amber-400 px-2.5 py-1 rounded-md">Finalist</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Fan Bracket Prediction Challenge Form -->
        <div class="glass-card p-8 border-amber-500/30">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center">
                    <i data-lucide="award" class="w-5 h-5"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold font-heading uppercase text-white">Fan Bracket Challenge & Final Predictor</h3>
                    <p class="text-xs text-gray-400">Guess the Grand Final winner and exact score line to earn leaderboard points!</p>
                </div>
            </div>

            <form action="{{ route('tournaments.predict') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                @csrf
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Fan Display Name</label>
                    <input type="text" name="user_name" required placeholder="e.g. TacticalApex" class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-400">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Predicted Cup Champion</label>
                    <select name="predicted_champion" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:outline-none focus:border-amber-400">
                        <option value="{{ $final['team1']->name }}">{{ $final['team1']->name }}</option>
                        <option value="{{ $final['team2']->name }}">{{ $final['team2']->name }}</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Final Score ({{ $final['team1']->name }} - {{ $final['team2']->name }})</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="final_score_home" min="0" value="2" required class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2.5 text-sm text-center text-white focus:outline-none focus:border-amber-400">
                        <span class="text-gray-500 font-bold">-</span>
                        <input type="number" name="final_score_away" min="0" value="1" required class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-2.5 text-sm text-center text-white focus:outline-none focus:border-amber-400">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-yellow-400 hover:from-amber-400 hover:to-yellow-300 text-black font-bold font-heading text-sm uppercase tracking-wider px-6 py-2.5 rounded-lg shadow-lg shadow-amber-500/20 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="send" class="w-4 h-4"></i> Submit Cup Prediction
                    </button>
                </div>
            </form>
        </div>

    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
