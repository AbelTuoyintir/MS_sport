<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $team->team_name }} — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <a href="{{ route('home') }}" class="text-accent-gold hover:underline mb-8 inline-block">← Back to Home</a>

        <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center gap-8">
            <div class="w-32 h-32 rounded-full flex items-center justify-center text-4xl font-black text-white" style="background-color: {{ $team->primary_color }}">
                {{ strtoupper(substr($team->team_name, 0, 2)) }}
            </div>
            <div class="text-center md:text-left">
                <h1 class="font-display text-6xl mb-2">{{ $team->team_name }}</h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-4">
                    <span class="bg-white/10 px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider">{{ $team->division }}</span>
                    <span class="text-gray-400">Squad Size: {{ $team->players->count() }} / {{ $team->team_size }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <h2 class="font-display text-4xl mb-4">The Squad</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($team->players as $player)
                        <a href="{{ route('players.show', $player->id) }}" class="glass-card p-4 flex items-center justify-between hover:bg-white/10 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center font-bold text-sm">{{ $player->number ?? '-' }}</div>
                                <div>
                                    <div class="font-bold">{{ $player->name }}</div>
                                    <div class="text-xs text-gray-400 uppercase font-bold">{{ $player->position }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-accent-gold font-bold">{{ $player->goals }} Goals</div>
                                <div class="text-[10px] text-gray-500 uppercase">{{ $player->assists }} Assists</div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h2 class="font-display text-4xl mb-4">Recent Form</h2>
                <div class="space-y-4">
                    @forelse($recent_games as $game)
                        <div class="glass-card p-4">
                            <div class="text-[10px] text-gray-500 font-bold uppercase mb-2">Matchweek {{ $game->matchweek }}</div>
                            <div class="flex justify-between items-center">
                                <div class="flex-1 text-right pr-2 {{ $game->home_team_id == $team->id ? 'font-bold' : 'text-gray-400' }}">{{ $game->homeTeam->team_name }}</div>
                                <div class="bg-white/10 px-3 py-1 rounded font-black text-accent-gold">{{ $game->home_score }} - {{ $game->away_score }}</div>
                                <div class="flex-1 text-left pl-2 {{ $game->away_team_id == $team->id ? 'font-bold' : 'text-gray-400' }}">{{ $game->awayTeam->team_name }}</div>
                            </div>
                            <div class="text-center text-[10px] text-gray-600 mt-2">{{ $game->kickoff->format('M d, Y') }}</div>
                        </div>
                    @empty
                        <p class="text-gray-500">No matches recorded.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
