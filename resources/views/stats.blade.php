<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Statistics & Leaderboard — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@300;400;500;600;700;800;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
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
              'muted2': '#99aabb',
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
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
        .accent-cyan { color: #00e5ff; }
        .bg-cyan { background-color: #00e5ff; }
    </style>
</head>
<body class="p-4 md:p-8 bg-[#04060a]">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-10 border-b border-white/10 pb-6">
            <div>
                <a href="{{ route('home') }}" class="accent-gold hover:underline font-heading font-bold text-sm tracking-widest uppercase flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Home
                </a>
                <h1 class="font-display text-5xl md:text-7xl tracking-tight leading-none bg-gradient-to-r from-white via-gray-100 to-amber-200 bg-clip-text text-transparent">League Hub</h1>
            </div>

            <!-- Tab Buttons -->
            <div class="flex gap-2 p-1 bg-white/5 border border-white/10 rounded-xl">
                <button id="player-tab-btn" onclick="switchTab('player-stats-tab', this)" class="tab-btn px-6 py-2.5 rounded-lg font-heading font-bold text-sm tracking-wider uppercase transition-all duration-200 bg-gold text-black shadow-lg">
                    Player Statistics
                </button>
                <button id="predictions-tab-btn" onclick="switchTab('predictions-tab', this)" class="tab-btn px-6 py-2.5 rounded-lg font-heading font-bold text-sm tracking-wider uppercase transition-all duration-200 text-gray-400 hover:text-white hover:bg-white/5">
                    Prediction Leaderboard
                </button>
            </div>
        </div>
        <a href="{{ route('home') }}" class="font-heading text-xs font-bold uppercase text-accent border border-accent/25 rounded-md px-4 py-2 hover:bg-accent/5 transition-all">← Back to Home</a>
    </div>

        <!-- Tab Content 1: Player Statistics -->
        <div id="player-stats-tab" class="tab-content space-y-12">
            <!-- Top stats row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Goals Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">⚽</span> Top Scorers
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($topScorers as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl accent-gold">{{ $player->goals }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No goals recorded yet.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Assists Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">👟</span> Top Assists
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($topAssists as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-blue-400' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl text-blue-400">{{ $player->assists }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No assists recorded yet.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Top Rated Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">⭐</span> Highest Rated
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($topRated as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-emerald-400' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl text-emerald-400">{{ number_format($player->rating / 10, 1) }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No ratings available yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Second row of stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Clean Sheets Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">🧤</span> Clean Sheets
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($mostCleanSheets as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-cyan-400' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl text-cyan-400">{{ $player->clean_sheets }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No clean sheets recorded yet.</p>
                        @endforelse
                    </div>
                </section>

                <!-- MOTM Awards Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">🏆</span> MOTM Awards
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($mostMotmAwards as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-amber-400' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl text-amber-400">{{ $player->motm_awards }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No MOTM awards recorded yet.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Appearances Section -->
                <section class="space-y-4">
                    <h2 class="font-display text-4xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span class="text-2xl">🏟</span> Appearances
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($mostAppearances as $index => $player)
                            <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-4">
                                    <span class="font-display text-2xl w-6 text-center text-gray-500">{{ $index + 1 }}</span>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-3xl text-purple-400">{{ $player->appearances }}</div>
                            </div>
                        @empty
                            <p class="p-8 text-center text-gray-500">No appearances recorded yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <!-- Discipline Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Yellow Cards -->
                <section class="space-y-4">
                    <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span>🟨</span> Yellow Cards Leaders
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($mostYellowCards as $player)
                            <div class="flex items-center justify-between p-3.5 border-b border-white/5 hover:bg-white/5 transition-colors">
                                <div>
                                    <div class="font-semibold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                                <div class="font-display text-2xl accent-gold">{{ $player->yellow_cards }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-gray-500">No yellow cards recorded yet.</p>
                        @endforelse
                    </div>
                </section>

                <!-- Red Cards -->
                <section class="space-y-4">
                    <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                        <span>🟥</span> Red Cards Leaders
                    </h2>
                    <div class="glass-card overflow-hidden">
                        @forelse($mostRedCards as $player)
                            <div class="flex items-center justify-between p-3.5 border-b border-white/5 hover:bg-white/5 transition-colors">
                                <div>
                                    <div class="font-semibold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                                <div class="font-display text-2xl text-red-500">{{ $player->red_cards }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-gray-500">No red cards recorded yet.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <!-- Tab Content 2: Prediction Leaderboard -->
        <div id="predictions-tab" class="tab-content hidden space-y-6">
            <div class="glass-card p-6 md:p-8 space-y-6">
                <div>
                    <h2 class="font-display text-4xl mb-2 flex items-center gap-3">
                        🏆 Global Fan Leaderboard
                    </h2>
                    <p class="text-gray-400 text-sm">
                        Predictions are scored in real time: <span class="text-white font-semibold">3 points</span> for an exact score prediction, and <span class="text-white font-semibold">1 point</span> for guessing the correct match outcome without matching the exact score.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 font-heading text-sm uppercase tracking-wider text-gray-400">
                                <th class="py-4 px-4 text-center w-16">Rank</th>
                                <th class="py-4 px-4">Predictor</th>
                                <th class="py-4 px-4 text-center">Total Predictions</th>
                                <th class="py-4 px-4 text-center accent-gold">Exact Scores (3pts)</th>
                                <th class="py-4 px-4 text-center text-blue-400">Correct Outcomes (1pt)</th>
                                <th class="py-4 px-4 text-right pr-6 font-bold text-white text-base">Total Points</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($predictionsLeaderboard as $index => $row)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-4 text-center font-display text-2xl">
                                        @if($index === 0)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30">1</span>
                                        @elseif($index === 1)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-300/20 text-slate-300 border border-slate-300/30">2</span>
                                        @elseif($index === 2)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700/20 text-amber-600 border border-amber-700/30">3</span>
                                        @else
                                            <span class="text-gray-500">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 font-bold text-white text-base">
                                        {{ $row['user_name'] }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-semibold text-gray-300">
                                        {{ $row['predictions_count'] }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-bold text-amber-300">
                                        {{ $row['exact_scores'] }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-semibold text-blue-300">
                                        {{ $row['correct_outcomes'] }}
                                    </td>
                                    <td class="py-4 px-4 text-right pr-6 font-display text-3xl accent-gold">
                                        {{ $row['points'] }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-8 text-center text-gray-500">
                                        No predictions made yet. Be the first to predict an upcoming match!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <script>
        function switchTab(tabId, btn) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Show current tab content
            document.getElementById(tabId).classList.remove('hidden');

            // Reset all tab button styles
            document.querySelectorAll('.tab-btn').forEach(button => {
                button.className = "tab-btn px-6 py-2.5 rounded-lg font-heading font-bold text-sm tracking-wider uppercase transition-all duration-200 text-gray-400 hover:text-white hover:bg-white/5";
            });
            // Highlight current button
            btn.className = "tab-btn px-6 py-2.5 rounded-lg font-heading font-bold text-sm tracking-wider uppercase transition-all duration-200 bg-gold text-black shadow-lg";
        }
    </script>
</body>
</html>
