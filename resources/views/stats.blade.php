<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Statistics — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-7xl mx-auto">
        <a href="{{ route('home') }}" class="accent-gold hover:underline mb-8 inline-block">← Back to Home</a>

        <header class="mb-12">
            <h1 class="font-display text-5xl md:text-7xl mb-2">League Statistics</h1>
            <p class="text-gray-500 uppercase font-heading tracking-widest">Season 2024/25 — Comprehensive Performance Data</p>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Top Scorers -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">⚽</span> Top Scorers
                </h2>
                <div class="space-y-3">
                    @foreach($top_scorers as $index => $player)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $player->goals }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Top Assists -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">👟</span> Top Assists
                </h2>
                <div class="space-y-3">
                    @foreach($top_assists as $index => $player)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $player->assists }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Appearances -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">🏃</span> Most Appearances
                </h2>
                <div class="space-y-3">
                    @foreach($most_appearances as $index => $player)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $player->appearances }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Discipline: Yellows -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="text-yellow-400">🟨</span> Most Yellow Cards
                </h2>
                <div class="space-y-3">
                    @foreach($most_yellows as $index => $player)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-yellow-400">{{ $player->yellow_cards }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Discipline: Reds -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="text-red-500">🟥</span> Most Red Cards
                </h2>
                <div class="space-y-3">
                    @foreach($most_reds as $index => $player)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-red-500">{{ $player->red_cards }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Team Stats -->
            <section class="glass-card p-6">
                <h2 class="font-display text-3xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">🛡️</span> Team Attack
                </h2>
                <div class="space-y-3">
                    @foreach($team_stats as $index => $team)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="font-bold text-gray-600 w-4">{{ $index + 1 }}</span>
                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $team->color }}"></div>
                                    <div class="font-bold">{{ $team->name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $team->goals }} Goals</div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</body>
</html>
