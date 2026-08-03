<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Statistics & Leaderboard — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
        .border-gold { border-color: #f0c040; }

        /* Custom tab styles */
        .tab-btn.active {
            color: #f0c040;
            border-bottom: 2px solid #f0c040;
            background: rgba(240, 192, 64, 0.05);
        }
    </style>
</head>
<body class="p-4 md:p-8 bg-[#06090e] text-[#e8edf4] min-h-screen">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('home') }}" class="accent-gold hover:underline text-sm font-semibold flex items-center gap-1.5 mb-2">
                    <span>←</span> Back to Home
                </a>
                <h1 class="font-display text-5xl md:text-6xl tracking-tight leading-none uppercase">
                    League <span class="accent-gold">Statistics</span>
                </h1>
                <p class="text-sm text-gray-400 mt-1">Official player statistics and fan prediction leaderboard for Season 2024/25.</p>
            </div>

            <div class="flex items-center gap-2 self-start md:self-auto bg-white/5 border border-white/10 rounded-lg p-1">
                <span class="text-xs text-gray-400 px-3 py-1 font-heading uppercase tracking-wider">Cape Coast, UCC</span>
            </div>
        </div>

        <!-- Interactive Tabs navigation -->
        <div class="glass-card mb-8 overflow-hidden">
            <div class="flex flex-wrap border-b border-white/10" role="tablist">
                <button onclick="switchTab('scorers')" id="tab-btn-scorers" class="tab-btn active flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    ⚽ Goals
                </button>
                <button onclick="switchTab('assists')" id="tab-btn-assists" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    👟 Assists
                </button>
                <button onclick="switchTab('rated')" id="tab-btn-rated" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    ⭐ Ratings
                </button>
                <button onclick="switchTab('defense')" id="tab-btn-defense" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    🧤 Clean Sheets
                </button>
                <button onclick="switchTab('honours')" id="tab-btn-honours" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    🏆 MOTM
                </button>
                <button onclick="switchTab('discipline')" id="tab-btn-discipline" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent">
                    🟨 Discipline
                </button>
                <button onclick="switchTab('predictions')" id="tab-btn-predictions" class="tab-btn flex-1 py-4 px-6 font-heading font-bold text-sm tracking-wider uppercase text-gray-400 transition-all duration-150 outline-none hover:text-white border-b-2 border-transparent bg-gold/5">
                    🔮 Predictions
                </button>
            </div>
        </div>

        <!-- Tab Panes -->
        <div class="space-y-8">

            <!-- GOALS TAB -->
            <div id="tab-pane-scorers" class="tab-pane">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">⚽</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Top Scorers</h2>
                        <p class="text-xs text-gray-400">Players leading the goalscoring race across the division.</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    @forelse($topScorers as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold font-black' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white" style="background-color: {{ $player->team->primary_color ?? '#f0c040' }}">
                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('players.show', $player->id) }}" class="font-bold hover:underline text-white block">{{ $player->name }}</a>
                                    <a href="{{ route('teams.show', $player->team_id) }}" class="text-[10px] text-gray-500 hover:underline uppercase font-bold tracking-wider">{{ $player->team->team_name }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Appearances</div>
                                    <div class="text-xs text-gray-300 font-semibold">{{ $player->appearances }} matches</div>
                                </div>
                                <div class="font-display text-3xl accent-gold w-12 text-right">{{ $player->goals }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 italic">No goals recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- ASSISTS TAB -->
            <div id="tab-pane-assists" class="tab-pane hidden">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">👟</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Top Assists</h2>
                        <p class="text-xs text-gray-400">Playmakers with the highest goal creations in the league.</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    @forelse($topAssists as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold font-black' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white" style="background-color: {{ $player->team->primary_color ?? '#00e5ff' }}">
                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('players.show', $player->id) }}" class="font-bold hover:underline text-white block">{{ $player->name }}</a>
                                    <a href="{{ route('teams.show', $player->team_id) }}" class="text-[10px] text-gray-500 hover:underline uppercase font-bold tracking-wider">{{ $player->team->team_name }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Appearances</div>
                                    <div class="text-xs text-gray-300 font-semibold">{{ $player->appearances }} matches</div>
                                </div>
                                <div class="font-display text-3xl text-blue-400 w-12 text-right">{{ $player->assists }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 italic">No assists recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- RATINGS TAB -->
            <div id="tab-pane-rated" class="tab-pane hidden">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">⭐</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Top Rated Players</h2>
                        <p class="text-xs text-gray-400">Players with the highest average performance rating this season.</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    @forelse($topRated as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold font-black' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white" style="background-color: {{ $player->team->primary_color ?? '#ffffff' }}">
                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('players.show', $player->id) }}" class="font-bold hover:underline text-white block">{{ $player->name }}</a>
                                    <a href="{{ route('teams.show', $player->team_id) }}" class="text-[10px] text-gray-500 hover:underline uppercase font-bold tracking-wider">{{ $player->team->team_name }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-white/5 text-gray-400">{{ $player->position }}</span>
                                </div>
                                <div class="font-display text-3xl text-green-400 w-16 text-right">{{ $player->rating }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 italic">No players meet rating criteria.</p>
                    @endforelse
                </div>
            </div>

            <!-- CLEAN SHEETS TAB -->
            <div id="tab-pane-defense" class="tab-pane hidden">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">🧤</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Clean Sheets</h2>
                        <p class="text-xs text-gray-400">Goalkeepers and defenders leading defensive shutouts.</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    @forelse($mostCleanSheets as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold font-black' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white" style="background-color: {{ $player->team->primary_color ?? '#00ced1' }}">
                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('players.show', $player->id) }}" class="font-bold hover:underline text-white block">{{ $player->name }}</a>
                                    <a href="{{ route('teams.show', $player->team_id) }}" class="text-[10px] text-gray-500 hover:underline uppercase font-bold tracking-wider">{{ $player->team->team_name }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-white/5 text-gray-400">{{ $player->position }}</span>
                                </div>
                                <div class="font-display text-3xl text-teal-400 w-12 text-right">{{ $player->clean_sheets }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 italic">No clean sheets recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- HONOURS (MOTM) TAB -->
            <div id="tab-pane-honours" class="tab-pane hidden">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">🏆</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Man of the Match Awards</h2>
                        <p class="text-xs text-gray-400">Players with the most outstanding individual match performance awards.</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    @forelse($mostMotmAwards as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold font-black' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-sm text-white" style="background-color: {{ $player->team->primary_color ?? '#ff69b4' }}">
                                    {{ strtoupper(substr($player->name, 0, 2)) }}
                                </div>
                                <div>
                                    <a href="{{ route('players.show', $player->id) }}" class="font-bold hover:underline text-white block">{{ $player->name }}</a>
                                    <a href="{{ route('teams.show', $player->team_id) }}" class="text-[10px] text-gray-500 hover:underline uppercase font-bold tracking-wider">{{ $player->team->team_name }}</a>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <div class="text-right hidden sm:block">
                                    <span class="text-xs font-bold px-2 py-0.5 rounded bg-white/5 text-gray-400">{{ $player->position }}</span>
                                </div>
                                <div class="font-display text-3xl text-purple-400 w-12 text-right">{{ $player->motm_awards }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 italic">No MOTM awards recorded yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- DISCIPLINE & APPEARANCES TAB -->
            <div id="tab-pane-discipline" class="tab-pane hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Appearances -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                            <span class="text-xl">🏟</span>
                            <h2 class="font-display text-2xl uppercase">Appearances</h2>
                        </div>
                        <div class="glass-card overflow-hidden">
                            @foreach($mostAppearances as $player)
                                <div class="flex items-center justify-between p-3 border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-sm hover:underline text-white block">{{ $player->name }}</a>
                                        <div class="text-[9px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                    <div class="font-display text-xl text-green-400">{{ $player->appearances }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Yellow Cards -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                            <span class="text-xl">🟨</span>
                            <h2 class="font-display text-2xl uppercase">Yellow Cards</h2>
                        </div>
                        <div class="glass-card overflow-hidden">
                            @foreach($mostYellowCards as $player)
                                <div class="flex items-center justify-between p-3 border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-sm hover:underline text-white block">{{ $player->name }}</a>
                                        <div class="text-[9px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                    <div class="font-display text-xl accent-gold">{{ $player->yellow_cards }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Red Cards -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 border-b border-white/10 pb-2">
                            <span class="text-xl">🟥</span>
                            <h2 class="font-display text-2xl uppercase">Red Cards</h2>
                        </div>
                        <div class="glass-card overflow-hidden">
                            @foreach($mostRedCards as $player)
                                <div class="flex items-center justify-between p-3 border-b border-white/5 last:border-0 hover:bg-white/5 transition-colors">
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-sm hover:underline text-white block">{{ $player->name }}</a>
                                        <div class="text-[9px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                    <div class="font-display text-xl text-red-500">{{ $player->red_cards }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- PREDICTIONS LEADERBOARD TAB -->
            <div id="tab-pane-predictions" class="tab-pane hidden">
                <div class="flex items-center gap-3 mb-6">
                    <span class="text-3xl">🔮</span>
                    <div>
                        <h2 class="font-display text-3xl leading-none uppercase">Prediction Leaderboard</h2>
                        <p class="text-xs text-gray-400">Global fan leaderboard based on score prediction accuracy (Exact outcome = 3 pts, correct outcome result = 1 pt).</p>
                    </div>
                </div>

                <div class="glass-card overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/5 text-[10px] sm:text-xs font-bold uppercase text-gray-400">
                                    <th class="p-4 text-center w-16">Rank</th>
                                    <th class="p-4">Fan Name</th>
                                    <th class="p-4 text-center">Predictions Made</th>
                                    <th class="p-4 text-center">Exact Scores (3pts)</th>
                                    <th class="p-4 text-center">Correct Outcomes (1pt)</th>
                                    <th class="p-4 text-right pr-6">Total Points</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($predictionsLeaderboard as $index => $row)
                                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors text-sm">
                                        <td class="p-4 text-center font-display text-xl {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">
                                            {{ $index + 1 }}
                                        </td>
                                        <td class="p-4 font-bold text-white">
                                            {{ $row['user_name'] }}
                                        </td>
                                        <td class="p-4 text-center text-gray-300 font-semibold">
                                            {{ $row['total_predictions'] }}
                                        </td>
                                        <td class="p-4 text-center text-green-400 font-semibold">
                                            {{ $row['exact_matches'] }}
                                        </td>
                                        <td class="p-4 text-center text-gold font-semibold">
                                            {{ $row['correct_outcomes'] }}
                                        </td>
                                        <td class="p-4 text-right pr-6 font-display text-2xl text-accent">
                                            {{ $row['total_points'] }} pts
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-gray-500 italic">No predictions calculated yet. Predict match results from the homepage to appear here!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all tab panes
            document.querySelectorAll('.tab-pane').forEach(el => {
                el.classList.add('hidden');
            });

            // Show active pane
            const activePane = document.getElementById('tab-pane-' + tabId);
            if (activePane) {
                activePane.classList.remove('hidden');
            }

            // Remove active classes from all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Add active class to clicked button
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.add('active');
            }
        }
    </script>
</body>
</html>
