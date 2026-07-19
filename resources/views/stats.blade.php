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
        .bg-gold { background-color: #f0c040; }
    </style>
</head>
<body class="p-4 md:p-8 min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8 border-b border-white/10 pb-6">
            <div>
                <a href="{{ route('home') }}" class="accent-gold hover:underline text-sm md:text-base">← Back to Home</a>
                <h1 class="font-display text-5xl md:text-7xl tracking-tight mt-2">League Statistics</h1>
                <p class="text-sm text-gray-400 mt-1">Official player performance leaders for Season 2024/25</p>
            </div>
            <!-- Tab Controls -->
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 rounded-xl p-1">
                <button onclick="switchTab('attacking')" id="btn-attacking" class="px-4 py-2 text-sm font-semibold rounded-lg bg-gold text-black transition-all">Attacking</button>
                <button onclick="switchTab('defending')" id="btn-defending" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-400 hover:text-white transition-all">Goalkeeping & Defending</button>
                <button onclick="switchTab('discipline')" id="btn-discipline" class="px-4 py-2 text-sm font-semibold rounded-lg text-gray-400 hover:text-white transition-all">Discipline & Appearances</button>
            </div>
        </div>

        <!-- Attacking Tab Content -->
        <div id="tab-attacking" class="tab-content grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Goals Section -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">⚽</span> Top Scorers
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topScorers as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-sm">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $player->goals }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No goals recorded yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- Assists Section -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">👟</span> Top Assists
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topAssists as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-sm">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-blue-400">{{ $player->assists }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No assists recorded yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- MOTM Section -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">⭐</span> Man of the Match
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostMOTM as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-sm">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-purple-400">{{ $player->motm_awards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No MOTM awards recorded.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Defending Tab Content -->
        <div id="tab-defending" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Clean Sheets -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">🧤</span> Clean Sheets (Golden Glove)
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($goldenGlove as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-sm">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-green-400">{{ $player->clean_sheets }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No clean sheets recorded yet.</p>
                    @endforelse
                </div>
            </section>

            <!-- Top Rated Players -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">📈</span> Top Rated Players
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($topRated as $index => $player)
                        <div class="flex items-center justify-between p-4 {{ !$loop->last ? 'border-b border-white/5' : '' }} hover:bg-white/5 transition-colors">
                            <div class="flex items-center gap-4">
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
                                <div>
                                    <div class="font-bold text-sm">{{ $player->name }}</div>
                                    <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                                </div>
                            </div>
                            <div class="font-display text-2xl text-yellow-500">{{ $player->rating }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No rated players.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Discipline & Appearances Tab Content -->
        <div id="tab-discipline" class="tab-content hidden grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Yellow Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">🟨</span> Yellow Cards
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostYellowCards as $index => $player)
                        <div class="flex items-center justify-between p-4 border-b border-white/5 hover:bg-white/5 transition-colors text-sm">
                            <div>
                                <div class="font-semibold">{{ $player->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                            </div>
                            <div class="font-display text-2xl accent-gold">{{ $player->yellow_cards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No yellow cards recorded.</p>
                    @endforelse
                </div>
            </section>

            <!-- Red Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">🟥</span> Red Cards
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostRedCards as $index => $player)
                        <div class="flex items-center justify-between p-4 border-b border-white/5 hover:bg-white/5 transition-colors text-sm">
                            <div>
                                <div class="font-semibold">{{ $player->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                            </div>
                            <div class="font-display text-2xl text-red-500">{{ $player->red_cards }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No red cards recorded.</p>
                    @endforelse
                </div>
            </section>

            <!-- Appearances -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl pb-1 flex items-center gap-3">
                    <span class="text-xl">🏟</span> Appearances
                </h2>
                <div class="glass-card overflow-hidden">
                    @forelse($mostAppearances as $index => $player)
                        <div class="flex items-center justify-between p-4 border-b border-white/5 hover:bg-white/5 transition-colors text-sm">
                            <div>
                                <div class="font-semibold">{{ $player->name }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->team->team_name }}</div>
                            </div>
                            <div class="font-display text-2xl text-green-400">{{ $player->appearances }}</div>
                        </div>
                    @empty
                        <p class="p-8 text-center text-gray-500 text-sm">No appearances recorded.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(function(el) {
                el.classList.add('hidden');
            });

            // Reset tab buttons styling
            document.querySelectorAll('button[id^="btn-"]').forEach(function(el) {
                el.classList.remove('bg-gold', 'text-black');
                el.classList.add('text-gray-400', 'hover:text-white');
            });

            // Show active tab content
            document.getElementById('tab-' + tabId).classList.remove('hidden');

            // Style active tab button
            var activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.add('bg-gold', 'text-black');
            activeBtn.classList.remove('text-gray-400', 'hover:text-white');
        }
    </script>
</body>
</html>
