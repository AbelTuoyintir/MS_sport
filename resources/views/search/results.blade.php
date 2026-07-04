<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results — MP League</title>
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
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('home') }}" class="accent-gold hover:underline mb-8 inline-block">← Back to Home</a>

        <header class="mb-12">
            <h1 class="font-display text-5xl md:text-6xl mb-2">Search Results</h1>
            <p class="text-gray-500 uppercase font-heading tracking-widest">Showing results for: <span class="text-white">"{{ $query }}"</span></p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Teams Section -->
            <section>
                <h2 class="font-display text-4xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">🛡️</span> Teams
                    <span class="text-sm bg-white/10 px-2 py-0.5 rounded-full text-gray-400 font-sans">{{ $teams->count() }}</span>
                </h2>
                <div class="space-y-4">
                    @forelse($teams as $team)
                        <a href="{{ route('teams.show', $team->id) }}" class="glass-card p-6 flex items-center justify-between hover:bg-white/10 transition-colors group">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center text-xl font-black text-white" style="background-color: {{ $team->primary_color }}">
                                    {{ strtoupper(substr($team->team_name, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="font-bold text-xl group-hover:accent-gold transition-colors">{{ $team->team_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $team->city ?? 'Cape Coast' }}</div>
                                </div>
                            </div>
                            <span class="text-gray-600 group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    @empty
                        <div class="glass-card p-8 text-center text-gray-500 italic">No teams found.</div>
                    @endforelse
                </div>
            </section>

            <!-- Players Section -->
            <section>
                <h2 class="font-display text-4xl mb-6 flex items-center gap-3">
                    <span class="accent-gold">👤</span> Players
                    <span class="text-sm bg-white/10 px-2 py-0.5 rounded-full text-gray-400 font-sans">{{ $players->count() }}</span>
                </h2>
                <div class="space-y-4">
                    @forelse($players as $player)
                        <a href="{{ route('players.show', $player->id) }}" class="glass-card p-6 flex items-center justify-between hover:bg-white/10 transition-colors group">
                            <div class="flex items-center gap-6">
                                <div class="w-16 h-16 rounded-xl bg-accent-gold/20 flex items-center justify-center text-2xl accent-gold font-black">
                                    {{ $player->number ?? '?' }}
                                </div>
                                <div>
                                    <div class="font-bold text-xl group-hover:accent-gold transition-colors">{{ $player->name }}</div>
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <span class="font-bold accent-gold">{{ $player->position }}</span>
                                        <span>•</span>
                                        <span>{{ $player->team->team_name }}</span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-gray-600 group-hover:translate-x-1 transition-transform">→</span>
                        </a>
                    @empty
                        <div class="glass-card p-8 text-center text-gray-500 italic">No players found.</div>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
</body>
</html>
