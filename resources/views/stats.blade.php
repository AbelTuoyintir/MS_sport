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
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="accent-gold hover:underline">← Back to Home</a>
            <h1 class="font-display text-5xl md:text-6xl tracking-tight">League Statistics</h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
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
                                <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">{{ $index + 1 }}</span>
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
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
            <!-- Yellow Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2">🟨 Yellow Cards</h2>
                <div class="glass-card overflow-hidden">
                    @foreach($mostYellowCards as $player)
                        <div class="flex items-center justify-between p-3 border-b border-white/5 text-sm">
                            <div class="font-semibold">{{ $player->name }}</div>
                            <div class="font-display text-xl accent-gold">{{ $player->yellow_cards }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Red Cards -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2">🟥 Red Cards</h2>
                <div class="glass-card overflow-hidden">
                    @foreach($mostRedCards as $player)
                        <div class="flex items-center justify-between p-3 border-b border-white/5 text-sm">
                            <div class="font-semibold">{{ $player->name }}</div>
                            <div class="font-display text-xl text-red-500">{{ $player->red_cards }}</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Appearances -->
            <section class="space-y-4">
                <h2 class="font-display text-3xl border-b border-white/10 pb-2">🏟 Appearances</h2>
                <div class="glass-card overflow-hidden">
                    @foreach($mostAppearances as $player)
                        <div class="flex items-center justify-between p-3 border-b border-white/5 text-sm">
                            <div class="font-semibold">{{ $player->name }}</div>
                            <div class="font-display text-xl text-green-400">{{ $player->appearances }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>
</body>
</html>
