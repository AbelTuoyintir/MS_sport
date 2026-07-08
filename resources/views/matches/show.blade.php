<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->homeTeam->team_name }} vs {{ $game->awayTeam->team_name }} — MP League</title>
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
        <a href="{{ route('home') }}" class="accent-gold hover:underline mb-8 inline-block">← Back to Home</a>

        <div class="glass-card p-6 md:p-12 mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex flex-col items-center gap-4 flex-1">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full flex items-center justify-center text-3xl md:text-4xl font-black text-white" style="background-color: {{ $game->homeTeam->primary_color }}">
                        {{ strtoupper(substr($game->homeTeam->team_name, 0, 2)) }}
                    </div>
                    <h2 class="font-display text-3xl md:text-4xl text-center">{{ $game->homeTeam->team_name }}</h2>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="font-heading text-sm font-bold uppercase tracking-widest text-gray-500">Matchweek {{ $game->matchweek }}</div>
                    <div class="font-display text-6xl md:text-8xl tracking-tighter">
                        @if($game->status === 'upcoming')
                            VS
                        @else
                            {{ $game->home_score }} — {{ $game->away_score }}
                        @endif
                    </div>
                    <div class="px-4 py-1 rounded bg-white/10 font-bold uppercase text-xs tracking-widest">
                        {{ $game->status }} {{ $game->status === 'live' ? $game->live_minute . "'" : '' }}
                    </div>
                    <div class="text-gray-500 text-sm mt-2">{{ $game->kickoff->format('M d, Y · H:i') }}</div>
                    <div class="text-gray-500 text-xs">{{ $game->venue }}</div>
                </div>

                <div class="flex flex-col items-center gap-4 flex-1">
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-full flex items-center justify-center text-3xl md:text-4xl font-black text-white" style="background-color: {{ $game->awayTeam->primary_color }}">
                        {{ strtoupper(substr($game->awayTeam->team_name, 0, 2)) }}
                    </div>
                    <h2 class="font-display text-3xl md:text-4xl text-center">{{ $game->awayTeam->team_name }}</h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Events & Stats -->
            <div class="lg:col-span-2 space-y-8">
                <section>
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Match Timeline</h3>
                    <div class="glass-card p-6 space-y-4">
                        @forelse($game->events->sortBy('minute') as $event)
                            <div class="flex items-center gap-4 {{ $event->team_id === $game->home_team_id ? '' : 'flex-row-reverse text-right' }}">
                                <div class="font-heading font-bold accent-gold w-8 text-center">{{ $event->minute }}'</div>
                                <div class="flex-1 flex items-center gap-3 {{ $event->team_id === $game->home_team_id ? '' : 'flex-row-reverse' }}">
                                    <div class="text-xl">
                                        @if($event->type === 'goal') ⚽
                                        @elseif($event->type === 'yellow_card') 🟨
                                        @elseif($event->type === 'red_card') 🟥
                                        @elseif($event->type === 'assist') 👟
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold">{{ $event->player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-widest">{{ str_replace('_', ' ', $event->type) }}</div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4">No events recorded.</div>
                        @endforelse
                    </div>
                </section>

                <section>
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Lineups</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h4 class="font-heading font-bold uppercase mb-4 accent-gold">{{ $game->homeTeam->team_name }}</h4>
                            <div class="space-y-2">
                                @foreach($game->squads->where('team_id', $game->home_team_id) as $squad)
                                    <div class="glass-card p-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-bold text-gray-500 w-4">{{ $squad->jersey_number }}</span>
                                            <span class="font-semibold">{{ $squad->player->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-500 uppercase">{{ $squad->role }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h4 class="font-heading font-bold uppercase mb-4 accent-gold">{{ $game->awayTeam->team_name }}</h4>
                            <div class="space-y-2">
                                @foreach($game->squads->where('team_id', $game->away_team_id) as $squad)
                                    <div class="glass-card p-3 flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs font-bold text-gray-500 w-4">{{ $squad->jersey_number }}</span>
                                            <span class="font-semibold">{{ $squad->player->name }}</span>
                                        </div>
                                        <span class="text-[10px] text-gray-500 uppercase">{{ $squad->role }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <section>
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Match Info</h3>
                    <div class="glass-card p-6 space-y-4">
                        <div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Venue</div>
                            <div class="font-semibold">{{ $game->venue ?? 'TBD' }}</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Referee</div>
                            <div class="font-semibold">TBD</div>
                        </div>
                        <div>
                            <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">Status</div>
                            <div class="inline-block px-2 py-0.5 rounded bg-accent-gold/20 accent-gold text-[10px] font-bold uppercase">
                                {{ $game->status }}
                            </div>
                        </div>
                    </div>
                </section>

                <section>
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Head to Head</h3>
                    <div class="glass-card p-6 space-y-6">
                        <div class="flex justify-between items-center text-center">
                            <div class="flex-1">
                                <div class="text-3xl font-display accent-gold">{{ $h2h_stats['home_wins'] }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">Wins</div>
                            </div>
                            <div class="flex-1">
                                <div class="text-3xl font-display text-gray-400">{{ $h2h_stats['draws'] }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">Draws</div>
                            </div>
                            <div class="flex-1">
                                <div class="text-3xl font-display accent-gold">{{ $h2h_stats['away_wins'] }}</div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold">Wins</div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-500 uppercase tracking-widest border-b border-white/5 pb-1">Previous Meetings</h4>
                            @forelse($h2h as $match)
                                <div class="flex justify-between items-center text-sm bg-white/5 p-2 rounded">
                                    <span class="text-gray-400 text-xs">{{ $match->kickoff->format('M Y') }}</span>
                                    <div class="flex-1 flex justify-center items-center gap-2">
                                        <span class="{{ $match->home_team_id == $game->home_team_id ? 'font-bold' : '' }}">{{ $match->homeTeam->team_name }}</span>
                                        <span class="bg-white/10 px-2 rounded font-black">{{ $match->home_score }} - {{ $match->away_score }}</span>
                                        <span class="{{ $match->away_team_id == $game->home_team_id ? 'font-bold' : '' }}">{{ $match->awayTeam->team_name }}</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-gray-600 italic text-xs">No previous meetings recorded.</p>
                            @endforelse
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
