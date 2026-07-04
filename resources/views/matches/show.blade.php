<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>{{ $game->homeTeam->team_name }} vs {{ $game->awayTeam->team_name }} — MP League</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet"/>
<style>
  body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
  .font-display { font-family: 'Bebas Neue', sans-serif; }
  .font-heading { font-family: 'Barlow Condensed', sans-serif; }
  .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
  .team-badge { border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #fff; }
</style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-5xl mx-auto">
        <a href="{{ route('home') }}" class="text-blue-400 hover:underline mb-8 inline-block">← Back to Home</a>

        <!-- Scoreboard -->
        <div class="glass-card p-6 md:p-10 mb-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex flex-col items-center gap-4 flex-1">
                    <div class="w-24 h-24 team-badge text-3xl" style="background-color: {{ $game->homeTeam->primary_color }}">
                        {{ strtoupper(substr($game->homeTeam->team_name, 0, 2)) }}
                    </div>
                    <h2 class="font-display text-4xl text-center">{{ $game->homeTeam->team_name }}</h2>
                </div>

                <div class="flex flex-col items-center gap-2">
                    <div class="text-6xl md:text-8xl font-display tracking-tighter">
                        @if($game->status === 'upcoming')
                            VS
                        @else
                            {{ $game->home_score }} – {{ $game->away_score }}
                        @endif
                    </div>
                    <div class="px-4 py-1 bg-red-500/20 text-red-500 rounded-full text-xs font-bold uppercase tracking-widest animate-pulse">
                        {{ $game->status }} {{ $game->live_minute ? "· " . $game->live_minute : "" }}
                    </div>
                    <div class="text-gray-500 text-sm mt-2">
                        {{ $game->kickoff->format('M d, Y · H:i') }} GMT
                    </div>
                </div>

                <div class="flex flex-col items-center gap-4 flex-1">
                    <div class="w-24 h-24 team-badge text-3xl" style="background-color: {{ $game->awayTeam->primary_color }}">
                        {{ strtoupper(substr($game->awayTeam->team_name, 0, 2)) }}
                    </div>
                    <h2 class="font-display text-4xl text-center">{{ $game->awayTeam->team_name }}</h2>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Events Timeline -->
            <div class="lg:col-span-2 space-y-8">
                <div class="glass-card p-6">
                    <h3 class="font-display text-2xl mb-6 border-b border-white/10 pb-2">Match Events</h3>
                    @if($game->events->isEmpty())
                        <p class="text-gray-500 italic">No events recorded yet.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($game->events->sortBy('minute') as $event)
                                <div class="flex items-center gap-4">
                                    <div class="font-heading font-bold text-blue-400 w-12">{{ $event->minute }}'</div>
                                    <div class="flex-1 flex items-center gap-3">
                                        @if($event->type === 'goal') <span class="text-xl">⚽</span>
                                        @elseif($event->type === 'yellow_card') <span class="text-xl">🟨</span>
                                        @elseif($event->type === 'red_card') <span class="text-xl">🟥</span>
                                        @elseif($event->type === 'assist') <span class="text-xl">👟</span>
                                        @endif
                                        <div>
                                            <div class="font-bold">{{ $event->player->name }}</div>
                                            <div class="text-xs text-gray-500 uppercase">{{ str_replace('_', ' ', $event->type) }}</div>
                                        </div>
                                    </div>
                                    <div class="w-3 h-3 rounded-full" style="background-color: {{ $event->team->primary_color }}"></div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Home Lineup -->
                    <div class="glass-card p-6">
                        <h3 class="font-display text-2xl mb-4 border-b border-white/10 pb-2">{{ $game->homeTeam->team_name }} Lineup</h3>
                        <div class="space-y-2">
                            @foreach($game->homeTeam->players as $player)
                                <div class="flex justify-between items-center py-2 border-b border-white/5">
                                    <span class="text-sm font-bold">{{ $player->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $player->position }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Away Lineup -->
                    <div class="glass-card p-6">
                        <h3 class="font-display text-2xl mb-4 border-b border-white/10 pb-2">{{ $game->awayTeam->team_name }} Lineup</h3>
                        <div class="space-y-2">
                            @foreach($game->awayTeam->players as $player)
                                <div class="flex justify-between items-center py-2 border-b border-white/5">
                                    <span class="text-sm font-bold">{{ $player->name }}</span>
                                    <span class="text-xs text-gray-500">{{ $player->position }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats & Venue -->
            <div class="space-y-8">
                <div class="glass-card p-6">
                    <h3 class="font-display text-2xl mb-4 border-b border-white/10 pb-2">Match Info</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Venue</div>
                            <div class="text-lg font-bold">{{ $game->venue ?? 'TBD' }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500 uppercase font-bold">Matchweek</div>
                            <div class="text-lg font-bold">{{ $game->matchweek }}</div>
                        </div>
                    </div>
                </div>

                @if($game->status !== 'upcoming')
                <div class="glass-card p-6">
                    <h3 class="font-display text-2xl mb-4 border-b border-white/10 pb-2">Team Stats</h3>
                    <!-- Placeholder for stats as they might not be in DB yet -->
                    <div class="space-y-4">
                        @php
                            $homeGoals = $game->events->where('team_id', $game->home_team_id)->where('type', 'goal')->count();
                            $awayGoals = $game->events->where('team_id', $game->away_team_id)->where('type', 'goal')->count();
                            $homeYellows = $game->events->where('team_id', $game->home_team_id)->where('type', 'yellow_card')->count();
                            $awayYellows = $game->events->where('team_id', $game->away_team_id)->where('type', 'yellow_card')->count();
                        @endphp
                        <div>
                            <div class="flex justify-between text-xs font-bold uppercase mb-1">
                                <span>{{ $homeGoals }}</span>
                                <span>Goals</span>
                                <span>{{ $awayGoals }}</span>
                            </div>
                            <div class="flex h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ ($homeGoals + $awayGoals) > 0 ? ($homeGoals / ($homeGoals + $awayGoals)) * 100 : 50 }}%"></div>
                                <div class="h-full bg-red-500" style="width: {{ ($homeGoals + $awayGoals) > 0 ? ($awayGoals / ($homeGoals + $awayGoals)) * 100 : 50 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold uppercase mb-1">
                                <span>{{ $homeYellows }}</span>
                                <span>Yellow Cards</span>
                                <span>{{ $awayYellows }}</span>
                            </div>
                            <div class="flex h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full bg-yellow-500" style="width: {{ ($homeYellows + $awayYellows) > 0 ? ($homeYellows / ($homeYellows + $awayYellows)) * 100 : 50 }}%"></div>
                                <div class="h-full bg-yellow-700" style="width: {{ ($homeYellows + $awayYellows) > 0 ? ($awayYellows / ($homeYellows + $awayYellows)) * 100 : 50 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
