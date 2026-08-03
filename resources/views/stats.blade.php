<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Statistics & Predictions — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
        .accent-teal { color: #00e5ff; }
        .border-accent { border-color: #f0c040; }
        .text-accent { color: #f0c040; }
        .tab-btn { transition: all 0.2s ease-in-out; }
    </style>
</head>
<body class="p-4 md:p-8 min-h-screen bg-gradient-to-b from-[#0a0f18] to-[#04060a]">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('home') }}" class="accent-gold hover:underline font-heading text-sm font-semibold tracking-wider uppercase">← Back to Home</a>
                <h1 class="font-display text-5xl md:text-6xl tracking-tight mt-2 text-white">League Statistics</h1>
                <p class="text-sm text-gray-400 mt-1">Official premier statistics, player leaderboards, and fan prediction challenge standings.</p>
            </div>
            <div class="glass-card px-4 py-2 flex items-center gap-3">
                <span class="w-2.5 h-2.5 rounded-full bg-green-500 animate-pulse"></span>
                <span class="font-heading font-bold text-xs uppercase tracking-widest text-gray-300">Live Database Synced</span>
            </div>
        </div>

        <!-- Interactive Tailwind Tabs -->
        <div class="flex border-b border-white/10 mb-8 overflow-x-auto gap-2 sm:gap-4 pb-1">
            <button onclick="switchTab('scoring')" id="tab-scoring" class="tab-btn whitespace-nowrap px-4 py-3 font-display text-lg tracking-wider border-b-2 border-accent text-accent outline-none">
                ⚽ Scoring & Assists
            </button>
            <button onclick="switchTab('goalkeeping')" id="tab-goalkeeping" class="tab-btn whitespace-nowrap px-4 py-3 font-display text-lg tracking-wider border-b-2 border-transparent text-gray-400 hover:text-white outline-none">
                🧤 Goalkeeping & Discipline
            </button>
            <button onclick="switchTab('performance')" id="tab-performance" class="tab-btn whitespace-nowrap px-4 py-3 font-display text-lg tracking-wider border-b-2 border-transparent text-gray-400 hover:text-white outline-none">
                ⭐ Player Performance
            </button>
            <button onclick="switchTab('predictions')" id="tab-predictions" class="tab-btn whitespace-nowrap px-4 py-3 font-display text-lg tracking-wider border-b-2 border-transparent text-gray-400 hover:text-white outline-none">
                🏆 Prediction Leaderboard
            </button>
        </div>

        <!-- TAB CONTENT: SCORING & ASSISTS -->
        <div id="content-scoring" class="tab-content grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Goals Section -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    Top Scorers
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topScorers as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
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
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    Top Assists
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topAssists as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
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
        </div>

        <!-- TAB CONTENT: GOALKEEPING & DISCIPLINE -->
        <div id="content-goalkeeping" class="tab-content hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Clean Sheets Section -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    🧤 Clean Sheets
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($cleanSheets as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-emerald-400">{{ $player->clean_sheets }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No clean sheets recorded.</p>
                    @endforelse
                </div>
            </section>

            <!-- Yellow Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    🟨 Yellow Cards
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostYellowCards as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center text-gray-500">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-yellow-500">{{ $player->yellow_cards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No yellow cards recorded.</p>
                    @endforelse
                </div>
            </section>

            <!-- Red Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    🟥 Red Cards
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostRedCards as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center text-gray-500">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-red-500">{{ $player->red_cards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No red cards recorded.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- TAB CONTENT: PLAYER PERFORMANCE -->
        <div id="content-performance" class="tab-content hidden grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Top Rated Players -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    ⭐ Top Rated Players
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topRated as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-cyan-400">{{ number_format($player->rating, 1) }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No ratings available.</p>
                    @endforelse
                </div>
            </section>

            <!-- MOTM Awards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    🏆 MOTM Awards
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($motmAwards as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-pink-400">{{ $player->motm_awards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No MOTM awards recorded yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- Appearances -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    🏟 Appearances
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostAppearances as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center text-gray-500">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-white">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-purple-400">{{ $player->appearances }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500">No appearances recorded yet.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- TAB CONTENT: PREDICTION LEADERBOARD -->
        <div id="content-predictions" class="tab-content hidden max-w-4xl mx-auto space-y-6">
            <div class="glass-card p-6 bg-[#00e5ff]/5 border-[#00e5ff]/20">
                <h2 class="font-display text-2xl text-white flex items-center gap-2">
                    🎮 Prediction Challenge Rules
                </h2>
                <p class="text-sm text-gray-300 mt-2 leading-relaxed">
                    Earn points by guessing results of the upcoming matches!
                </p>
                <ul class="list-disc list-inside text-xs text-gray-400 mt-2 space-y-1 pl-2">
                    <li><strong class="accent-gold">3 points</strong> for predicting the <span class="text-white font-semibold">exact score</span> (e.g. predicted 2-1, actual 2-1).</li>
                    <li><strong class="accent-teal">1 point</strong> for predicting the <span class="text-white font-semibold">correct outcome</span> (win/loss/draw) without the exact score.</li>
                </ul>
            </div>

            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2 flex items-center gap-3">
                    Global Fan Leaderboard
                </h2>
                <div class="glass-card overflow-hidden">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 bg-white/5 text-[10px] font-heading font-bold uppercase tracking-widest text-gray-400">
                                <th class="py-3.5 px-6 text-center w-16">Rank</th>
                                <th class="py-3.5 px-4">User</th>
                                <th class="py-3.5 px-4 text-center">Predictions</th>
                                <th class="py-3.5 px-6 text-right">Total Points</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leaderboard as $index => $row)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 px-6 text-center font-display text-2xl {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="py-4 px-4 font-bold text-white text-base">
                                        {{ $row->user_name }}
                                    </td>
                                    <td class="py-4 px-4 text-center font-semibold text-gray-400">
                                        {{ $row->predictions_count }}
                                    </td>
                                    <td class="py-4 px-6 text-right font-display text-3xl text-emerald-400">
                                        {{ $row->points }} <span class="text-xs uppercase text-gray-500 font-heading tracking-wide">pts</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-gray-500 font-medium">
                                        No predictions graded yet. Predict an upcoming match to start climbing the leaderboard!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <!-- JavaScript to handle tab switching dynamically -->
    <script>
        function switchTab(tabId) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
            // Show active content
            document.getElementById('content-' + tabId).classList.remove('hidden');

            // Reset all tabs styles
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('border-accent', 'text-accent');
                btn.classList.add('border-transparent', 'text-gray-400');
            });
            // Set active tab style
            const activeBtn = document.getElementById('tab-' + tabId);
            activeBtn.classList.remove('border-transparent', 'text-gray-400');
            activeBtn.classList.add('border-accent', 'text-accent');
        }
    </script>
</body>
</html>
