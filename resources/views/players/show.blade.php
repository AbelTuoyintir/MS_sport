<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $player->name }} — MP League</title>
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
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('teams.show', $player->team_id) }}" class="text-accent-gold hover:underline mb-8 inline-block">← Back to {{ $player->team->team_name }}</a>

        <div class="glass-card p-8 mb-8 flex flex-col md:flex-row items-center gap-8 text-center md:text-left">
            <div class="w-40 h-40 rounded-xl bg-gradient-to-br from-accent-gold to-yellow-600 flex items-center justify-center text-6xl font-black text-black">
                {{ strtoupper(substr($player->name, 0, 1)) }}{{ $player->number ?? '' }}
            </div>
            <div>
                <div class="text-accent-gold font-bold uppercase tracking-widest mb-2">{{ $player->position }} — #{{ $player->number ?? '?' }}</div>
                <h1 class="font-display text-6xl mb-2">{{ $player->name }}</h1>
                 <div class="flex items-center justify-center md:justify-start gap-4 mb-4">
                    <div class="w-6 h-6 rounded-full" style="background-color: {{ $player->team->primary_color }}"></div>
                    <span class="font-bold">{{ $player->team->team_name }}</span>
                 </div>
                 <div class="flex justify-center md:justify-start">
                     <a href="{{ route('players.compare', ['player1_id' => $player->id]) }}" class="bg-gradient-to-r from-amber-500 to-[#f0c040] hover:from-amber-600 hover:to-yellow-500 text-black font-extrabold px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider shadow-lg transition-all duration-200">
                         ⚡ Compare Player
                     </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
            <div class="glass-card p-6 text-center">
                <div class="text-4xl font-black text-accent-gold mb-1">{{ $player->goals }}</div>
                <div class="text-xs text-gray-500 uppercase font-bold">Goals</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="text-4xl font-black text-accent-gold mb-1">{{ $player->assists }}</div>
                <div class="text-xs text-gray-500 uppercase font-bold">Assists</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="text-4xl font-black text-accent-gold mb-1">{{ $player->yellow_cards }}</div>
                <div class="text-xs text-gray-500 uppercase font-bold">Yellows</div>
            </div>
            <div class="glass-card p-6 text-center">
                <div class="text-4xl font-black text-red-500 mb-1">{{ $player->red_cards }}</div>
                <div class="text-xs text-gray-500 uppercase font-bold">Reds</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
            <div class="glass-card p-8">
                <h2 class="font-display text-3xl mb-6">Player Details</h2>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Nationality</div>
                        <div class="text-xl font-bold">{{ $player->nationality ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Age</div>
                        <div class="text-xl font-bold">{{ $player->age ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Appearances</div>
                        <div class="text-xl font-bold">{{ $player->appearances }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-500 uppercase font-bold mb-1">Status</div>
                        <div class="text-xl font-bold text-green-400">{{ ucfirst($player->status) }}</div>
                    </div>
                </div>
            </div>

            <div class="glass-card p-8">
                <h2 class="font-display text-3xl mb-6">Recent Involvement</h2>
                <div class="space-y-4">
                    @forelse($recent_events as $event)
                        <div class="flex items-center justify-between py-2 border-b border-white/5 last:border-0">
                            <div class="flex items-center gap-3">
                                <span class="text-xl">
                                    @if($event->type === 'goal') ⚽
                                    @elseif($event->type === 'yellow_card') 🟨
                                    @elseif($event->type === 'red_card') 🟥
                                    @elseif($event->type === 'assist') 👟
                                    @endif
                                </span>
                                <div>
                                    <div class="text-xs font-bold uppercase text-gray-400">{{ str_replace('_', ' ', $event->type) }}</div>
                                    <div class="text-sm">vs {{ $event->game->home_team_id === $player->team_id ? $event->game->awayTeam->team_name : $event->game->homeTeam->team_name }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs font-bold">{{ $event->minute }}'</div>
                                <div class="text-[10px] text-gray-600">{{ $event->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-gray-500 py-8 italic">No recent match events.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</body>
</html>
