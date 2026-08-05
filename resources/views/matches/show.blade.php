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

        <!-- Apex AI Matchday Live Simulator & Fan Hype Engine -->
        <div class="glass-card p-6 mb-8 relative overflow-hidden bg-gradient-to-b from-white/5 to-transparent border border-white/10 rounded-xl" id="apex-simulator-container">
            <div class="absolute top-0 right-0 p-4">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00e5ff] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-[#00e5ff]"></span>
                </span>
            </div>

            <div class="flex items-center gap-3 mb-4">
                <span class="text-3xl">🔮</span>
                <div>
                    <h3 class="font-display text-2xl tracking-wide text-white uppercase">APEX AI Matchday Live Simulator</h3>
                    <p class="text-xs text-gray-400">Experience a live performance-simulated play-by-play. Direct the match momentum via the Fan Hype Engine!</p>
                </div>
            </div>

            <!-- Start State -->
            <div id="sim-start-state" class="py-8 text-center flex flex-col items-center justify-center gap-4">
                <div class="font-heading text-lg font-bold text-[#f0c040] uppercase tracking-wider">Predictor Readiness: 100%</div>
                <button type="button" id="start-sim-btn" onclick="startSimulation()" class="bg-[#f0c040] hover:bg-yellow-500 transition-all text-black font-bold font-heading uppercase px-8 py-3 rounded-xl tracking-wider text-sm flex items-center gap-2 shadow-lg">
                    ⚡ Start Live AI Match Simulation
                </button>
            </div>

            <!-- Active Simulation State -->
            <div id="sim-active-state" class="hidden space-y-6">
                <!-- Scoreboard & Clock -->
                <div class="flex items-center justify-between bg-black/30 p-4 rounded-xl border border-white/5">
                    <div class="text-right flex-1">
                        <div class="font-bold text-lg text-white" id="sim-home-name">{{ $game->homeTeam->team_name }}</div>
                    </div>
                    <div class="flex flex-col items-center px-6">
                        <div class="font-display text-4xl text-white tracking-widest"><span id="sim-home-score">0</span> - <span id="sim-away-score">0</span></div>
                        <div class="font-heading text-xs font-bold text-[#00e5ff] bg-[#00e5ff]/10 px-3 py-0.5 rounded mt-1" id="sim-clock">1'</div>
                    </div>
                    <div class="text-left flex-1">
                        <div class="font-bold text-lg text-white" id="sim-away-name">{{ $game->awayTeam->team_name }}</div>
                    </div>
                </div>

                <!-- Live Visual Pitch Tracker -->
                <div>
                    <div class="text-[10px] text-gray-500 uppercase font-bold tracking-wider mb-2">Live Pitch Position Tracker</div>
                    <div class="relative w-full h-[120px] rounded-xl overflow-hidden border border-white/10 bg-emerald-950/40 flex items-center justify-between px-4" id="sim-pitch">
                        <!-- Pitch markings -->
                        <div class="absolute inset-y-0 left-1/2 w-[1px] bg-white/15"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 rounded-full border border-white/15"></div>
                        <div class="absolute inset-y-4 left-0 w-8 border-y border-r border-white/15"></div>
                        <div class="absolute inset-y-4 right-0 w-8 border-y border-l border-white/15"></div>

                        <!-- Team badges/labels on pitch -->
                        <div class="font-display text-white/5 text-4xl select-none absolute left-8">HOME</div>
                        <div class="font-display text-white/5 text-4xl select-none absolute right-8">AWAY</div>

                        <!-- Ball -->
                        <div id="sim-ball" class="absolute w-4 h-4 rounded-full bg-white shadow-[0_0_12px_#fff] border-2 border-black transition-all duration-300 left-1/2 top-[50px] flex items-center justify-center" style="left: 50%; top: 50px;">
                            <div class="w-1.5 h-1.5 bg-black rounded-full"></div>
                        </div>
                    </div>
                </div>

                <!-- Live Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Stat Bars -->
                    <div class="space-y-3 bg-white/5 p-4 rounded-xl border border-white/5">
                        <div class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-white/5 pb-1 mb-2">Simulated Statistics</div>

                        <!-- Possession -->
                        <div class="space-y-1">
                            <div class="flex justify-between text-[11px] font-semibold">
                                <span>Possession</span>
                                <div><span id="sim-home-possession">50</span>% - <span id="sim-away-possession">50</span>%</div>
                            </div>
                            <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden flex">
                                <div id="sim-home-poss-bar" class="h-full bg-[#f0c040] transition-all duration-300" style="width: 50%"></div>
                                <div id="sim-away-poss-bar" class="h-full bg-[#00e5ff] transition-all duration-300" style="width: 50%"></div>
                            </div>
                        </div>

                        <!-- Shots -->
                        <div class="flex justify-between items-center text-xs">
                            <div class="font-bold text-[#f0c040]" id="sim-home-shots">0</div>
                            <span class="text-gray-500 uppercase text-[10px]">Shots (On Target)</span>
                            <div class="font-bold text-[#00e5ff]" id="sim-away-shots">0</div>
                        </div>

                        <!-- Saves -->
                        <div class="flex justify-between items-center text-xs">
                            <div class="font-bold text-[#f0c040]" id="sim-home-saves">0</div>
                            <span class="text-gray-500 uppercase text-[10px]">Goalkeeper Saves</span>
                            <div class="font-bold text-[#00e5ff]" id="sim-away-saves">0</div>
                        </div>

                        <!-- Yellow/Red Cards -->
                        <div class="flex justify-between items-center text-xs">
                            <div>
                                <span id="sim-home-yellows" class="text-yellow-400 font-bold">0</span>🟨
                                <span id="sim-home-reds" class="text-red-500 font-bold ml-1">0</span>🟥
                            </div>
                            <span class="text-gray-500 uppercase text-[10px]">Bookings</span>
                            <div>
                                <span id="sim-away-yellows" class="text-yellow-400 font-bold">0</span>🟨
                                <span id="sim-away-reds" class="text-red-500 font-bold ml-1">0</span>🟥
                            </div>
                        </div>
                    </div>

                    <!-- Fan Cheering Hype Engine -->
                    <div class="bg-gradient-to-br from-white/5 to-transparent p-4 rounded-xl border border-white/5 flex flex-col justify-between">
                        <div>
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-widest border-b border-white/5 pb-1 mb-2">Fan Hype Meter (Cheer Engine)</div>
                            <p class="text-[10px] text-gray-400 leading-relaxed mb-3">Cheer for your team to boost their virtual hype level. Higher hype increases the team's chance to control possession and score!</p>
                        </div>

                        <div class="space-y-3">
                            <!-- Hype Gauge -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[10px] font-bold text-gray-500 uppercase">
                                    <span>Home Hype: <span id="sim-home-hype-lbl">50</span>%</span>
                                    <span>Away Hype: <span id="sim-away-hype-lbl">50</span>%</span>
                                </div>
                                <div class="h-2 w-full bg-white/10 rounded-full overflow-hidden flex">
                                    <div id="sim-home-hype-bar" class="h-full bg-gradient-to-r from-amber-500 to-[#f0c040] transition-all duration-300" style="width: 50%"></div>
                                    <div id="sim-away-hype-bar" class="h-full bg-gradient-to-r from-cyan-500 to-[#00e5ff] transition-all duration-300" style="width: 50%"></div>
                                </div>
                            </div>

                            <!-- Cheering Buttons -->
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" id="cheer-home-btn" onclick="cheerTeam('home')" class="relative overflow-visible bg-[#f0c040]/10 hover:bg-[#f0c040]/20 border border-[#f0c040]/30 hover:border-[#f0c040]/50 transition-all text-[#f0c040] font-bold text-xs py-2 px-3 rounded-lg flex items-center justify-center gap-1.5">
                                    🔥 Cheer Home
                                    <span id="cheer-home-particles" class="absolute pointer-events-none text-xs font-bold opacity-0 transition-all duration-500 -top-4 text-[#f0c040]">+1 Hype!</span>
                                </button>
                                <button type="button" id="cheer-away-btn" onclick="cheerTeam('away')" class="relative overflow-visible bg-[#00e5ff]/10 hover:bg-[#00e5ff]/20 border border-[#00e5ff]/30 hover:border-[#00e5ff]/50 transition-all text-[#00e5ff] font-bold text-xs py-2 px-3 rounded-lg flex items-center justify-center gap-1.5">
                                    🔥 Cheer Away
                                    <span id="cheer-away-particles" class="absolute pointer-events-none text-xs font-bold opacity-0 transition-all duration-500 -top-4 text-[#00e5ff]">+1 Hype!</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commentary Console -->
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Live Commentary Log</span>
                        <div class="flex items-center gap-2">
                            <button type="button" id="sim-pause-btn" onclick="toggleSimPause()" class="text-xs bg-white/10 hover:bg-white/20 transition-all px-2.5 py-1 rounded font-bold">Pause</button>
                            <button type="button" id="sim-speed-btn" onclick="toggleSimSpeed()" class="text-xs bg-[#00e5ff]/15 text-[#00e5ff] hover:bg-[#00e5ff]/25 transition-all px-2.5 py-1 rounded font-bold">1x Speed</button>
                        </div>
                    </div>
                    <div id="sim-commentary-box" class="bg-black/40 border border-white/5 rounded-xl p-4 h-48 overflow-y-auto text-xs font-mono space-y-2 scrollbar-thin scrollbar-thumb-white/10 scroll-smooth">
                        <div class="text-[#00e5ff] font-bold">[SYSTEM] Match Simulator calibrated. Awaiting kickoff...</div>
                    </div>
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
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Lineups & Fan Performance Ratings</h3>

                    @if(session('success'))
                        <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 p-4 rounded-xl mb-4 text-xs font-semibold">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="bg-rose-500/10 border border-rose-500/20 text-rose-400 p-4 rounded-xl mb-4 text-xs font-semibold">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @php
                            $homePlayers = $game->squads->where('team_id', $game->home_team_id);
                            if ($homePlayers->isEmpty()) {
                                $homePlayers = $game->homeTeam->players->map(function($p, $idx) {
                                    return (object)[
                                        'jersey_number' => $p->number ?? ($idx + 1),
                                        'player' => $p,
                                        'role' => $p->position
                                    ];
                                });
                            }

                            $awayPlayers = $game->squads->where('team_id', $game->away_team_id);
                            if ($awayPlayers->isEmpty()) {
                                $awayPlayers = $game->awayTeam->players->map(function($p, $idx) {
                                    return (object)[
                                        'jersey_number' => $p->number ?? ($idx + 1),
                                        'player' => $p,
                                        'role' => $p->position
                                    ];
                                });
                            }
                        @endphp
                        <div>
                            <h4 class="font-heading font-bold uppercase mb-4 accent-gold">{{ $game->homeTeam->team_name }}</h4>
                            <div class="space-y-2">
                                @foreach($homePlayers as $squad)
                                    @php
                                        $avgRating = $squad->player->averageRatingForMatch($game->id);
                                    @endphp
                                    <div class="glass-card p-3 flex flex-col gap-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-bold text-gray-500 w-4">{{ $squad->jersey_number }}</span>
                                                <span class="font-semibold">{{ $squad->player->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($avgRating > 0)
                                                    <span class="text-xs font-bold bg-[#f0c040]/10 text-[#f0c040] px-2 py-0.5 rounded flex items-center gap-1">⭐ {{ $avgRating }}</span>
                                                @endif
                                                <span class="text-[10px] text-gray-500 uppercase bg-white/5 px-2 py-0.5 rounded">{{ $squad->role }}</span>
                                            </div>
                                        </div>
                                        @if($game->status === 'finished')
                                            <form action="{{ route('player-ratings.store') }}" method="POST" class="flex items-center gap-2 mt-1 pt-2 border-t border-white/5">
                                                @csrf
                                                <input type="hidden" name="player_id" value="{{ $squad->player->id }}">
                                                <input type="hidden" name="game_id" value="{{ $game->id }}">
                                                <label class="text-[9px] text-gray-400 uppercase tracking-wider">Rate Player:</label>
                                                <select name="rating" required class="bg-black/40 border border-white/10 rounded px-1.5 py-0.5 text-[10px] text-white outline-none">
                                                    @for($r = 10; $r >= 1; $r--)
                                                        <option value="{{ $r }}">{{ $r }}</option>
                                                    @endfor
                                                </select>
                                                <button type="submit" class="bg-[#f0c040] hover:bg-yellow-500 transition-colors text-black font-bold text-[9px] px-2 py-0.5 rounded font-heading uppercase tracking-wider">Submit</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h4 class="font-heading font-bold uppercase mb-4 accent-gold">{{ $game->awayTeam->team_name }}</h4>
                            <div class="space-y-2">
                                @foreach($awayPlayers as $squad)
                                    @php
                                        $avgRating = $squad->player->averageRatingForMatch($game->id);
                                    @endphp
                                    <div class="glass-card p-3 flex flex-col gap-2">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xs font-bold text-gray-500 w-4">{{ $squad->jersey_number }}</span>
                                                <span class="font-semibold">{{ $squad->player->name }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                @if($avgRating > 0)
                                                    <span class="text-xs font-bold bg-[#f0c040]/10 text-[#f0c040] px-2 py-0.5 rounded flex items-center gap-1">⭐ {{ $avgRating }}</span>
                                                @endif
                                                <span class="text-[10px] text-gray-500 uppercase bg-white/5 px-2 py-0.5 rounded">{{ $squad->role }}</span>
                                            </div>
                                        </div>
                                        @if($game->status === 'finished')
                                            <form action="{{ route('player-ratings.store') }}" method="POST" class="flex items-center gap-2 mt-1 pt-2 border-t border-white/5">
                                                @csrf
                                                <input type="hidden" name="player_id" value="{{ $squad->player->id }}">
                                                <input type="hidden" name="game_id" value="{{ $game->id }}">
                                                <label class="text-[9px] text-gray-400 uppercase tracking-wider">Rate Player:</label>
                                                <select name="rating" required class="bg-black/40 border border-white/10 rounded px-1.5 py-0.5 text-[10px] text-white outline-none">
                                                    @for($r = 10; $r >= 1; $r--)
                                                        <option value="{{ $r }}">{{ $r }}</option>
                                                    @endfor
                                                </select>
                                                <button type="submit" class="bg-[#f0c040] hover:bg-yellow-500 transition-colors text-black font-bold text-[9px] px-2 py-0.5 rounded font-heading uppercase tracking-wider">Submit</button>
                                            </form>
                                        @endif
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

                @if($game->potm_id && $game->potm)
                <section>
                    <h3 class="font-display text-3xl mb-4 border-b border-white/10 pb-2">Player of the Match</h3>
                    <div class="glass-card p-6 flex items-center gap-4 bg-gradient-to-r from-yellow-500/10 to-transparent border border-yellow-500/20">
                        <div class="w-12 h-12 rounded-full bg-[#f0c040]/20 flex items-center justify-center text-accent-gold">
                            <span class="text-xl">🏆</span>
                        </div>
                        <div>
                            <a href="{{ route('players.show', $game->potm_id) }}" class="font-bold text-lg hover:text-accent-gold transition-colors block">{{ $game->potm->name }}</a>
                            <div class="text-xs text-gray-400 uppercase tracking-widest">{{ $game->potm->team->team_name ?? 'N/A' }}</div>
                        </div>
                    </div>
                </section>
                @endif

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

    <script>
        // Injected variables
        const homePlayersList = @json($game->homeTeam->players ?? []);
        const awayPlayersList = @json($game->awayTeam->players ?? []);
        const homeTeamRatingVal = {{ $game->homeTeam->players->avg('rating') ?? 75 }};
        const awayTeamRatingVal = {{ $game->awayTeam->players->avg('rating') ?? 75 }};

        let minute = 1;
        let homeScore = 0;
        let awayScore = 0;
        let homeShots = 0;
        let awayShots = 0;
        let homeSaves = 0;
        let awaySaves = 0;
        let homeYellows = 0;
        let awayYellows = 0;
        let homeReds = 0;
        let awayReds = 0;

        let homeCheerCount = 0;
        let awayCheerCount = 0;
        let homeHype = 50;
        let awayHype = 50;

        let simInterval = null;
        let simSpeed = 400; // 400ms = 1 game minute
        let isPaused = false;
        let hasStarted = false;

        // Helper to get random player
        function getRandomPlayer(list, fallbackList) {
            if (list && list.length > 0) {
                return list[Math.floor(Math.random() * list.length)];
            }
            if (fallbackList && fallbackList.length > 0) {
                return fallbackList[Math.floor(Math.random() * fallbackList.length)];
            }
            return { name: "Player" };
        }

        // Filter player lists
        const homeGK = homePlayersList.filter(p => p.position === 'Goalkeeper' || p.position === 'GK');
        const awayGK = awayPlayersList.filter(p => p.position === 'Goalkeeper' || p.position === 'GK');
        const homeDF = homePlayersList.filter(p => p.position === 'Defender' || p.position === 'DF');
        const awayDF = awayPlayersList.filter(p => p.position === 'Defender' || p.position === 'DF');
        const homeMF = homePlayersList.filter(p => p.position === 'Midfielder' || p.position === 'MF');
        const awayMF = awayPlayersList.filter(p => p.position === 'Midfielder' || p.position === 'MF');
        const homeFW = homePlayersList.filter(p => p.position === 'Forward' || p.position === 'FW');
        const awayFW = awayPlayersList.filter(p => p.position === 'Forward' || p.position === 'FW');

        function startSimulation() {
            if (hasStarted) return;
            hasStarted = true;

            document.getElementById('sim-start-state').classList.add('hidden');
            document.getElementById('sim-active-state').classList.remove('hidden');

            logCommentary("[KICKOFF] The referee blows the whistle! The match has officially started.", "text-emerald-400 font-bold");

            simInterval = setInterval(tickSimulation, simSpeed);
        }

        function toggleSimPause() {
            if (!hasStarted || minute >= 90) return;
            isPaused = !isPaused;
            const btn = document.getElementById('sim-pause-btn');
            if (isPaused) {
                clearInterval(simInterval);
                btn.textContent = "Resume";
                btn.className = "text-xs bg-[#f0c040]/20 text-[#f0c040] hover:bg-[#f0c040]/30 transition-all px-2.5 py-1 rounded font-bold";
                logCommentary("[SYSTEM] Match simulation paused.", "text-yellow-500 font-bold");
            } else {
                simInterval = setInterval(tickSimulation, simSpeed);
                btn.textContent = "Pause";
                btn.className = "text-xs bg-white/10 hover:bg-white/20 transition-all px-2.5 py-1 rounded font-bold";
                logCommentary("[SYSTEM] Match simulation resumed.", "text-emerald-500 font-bold");
            }
        }

        function toggleSimSpeed() {
            if (!hasStarted || minute >= 90) return;
            const btn = document.getElementById('sim-speed-btn');
            if (simSpeed === 400) {
                simSpeed = 100; // 4x speed
                btn.textContent = "4x Speed";
                btn.className = "text-xs bg-amber-500/15 text-amber-500 hover:bg-amber-500/25 transition-all px-2.5 py-1 rounded font-bold";
            } else {
                simSpeed = 400; // 1x speed
                btn.textContent = "1x Speed";
                btn.className = "text-xs bg-[#00e5ff]/15 text-[#00e5ff] hover:bg-[#00e5ff]/25 transition-all px-2.5 py-1 rounded font-bold";
            }

            if (!isPaused) {
                clearInterval(simInterval);
                simInterval = setInterval(tickSimulation, simSpeed);
            }
        }

        function logCommentary(text, extraClasses = "") {
            const box = document.getElementById('sim-commentary-box');
            if (!box) return;
            const entry = document.createElement('div');
            entry.className = `py-1 border-b border-white/5 ${extraClasses}`;
            entry.innerHTML = `<span class="text-gray-500 font-bold">[${minute}']</span> ${text}`;
            box.appendChild(entry);
            box.scrollTop = box.scrollHeight;
        }

        function cheerTeam(team) {
            if (team === 'home') {
                homeCheerCount++;
                const part = document.getElementById('cheer-home-particles');
                if (part) {
                    part.style.opacity = '1';
                    part.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        part.style.opacity = '0';
                        part.style.transform = 'translateY(0)';
                    }, 600);
                }
            } else {
                awayCheerCount++;
                const part = document.getElementById('cheer-away-particles');
                if (part) {
                    part.style.opacity = '1';
                    part.style.transform = 'translateY(-20px)';
                    setTimeout(() => {
                        part.style.opacity = '0';
                        part.style.transform = 'translateY(0)';
                    }, 600);
                }
            }

            updateHype();
        }

        function updateHype() {
            const totalCheers = homeCheerCount + awayCheerCount;
            if (totalCheers === 0) return;

            // Dynamic base hype starts at 50/50
            homeHype = Math.round(50 + (homeCheerCount - awayCheerCount) * 5);
            homeHype = Math.max(10, Math.min(90, homeHype));
            awayHype = 100 - homeHype;

            const homeLbl = document.getElementById('sim-home-hype-lbl');
            const awayLbl = document.getElementById('sim-away-hype-lbl');
            const homeBar = document.getElementById('sim-home-hype-bar');
            const awayBar = document.getElementById('sim-away-hype-bar');

            if (homeLbl) homeLbl.textContent = homeHype;
            if (awayLbl) awayLbl.textContent = awayHype;
            if (homeBar) homeBar.style.width = `${homeHype}%`;
            if (awayBar) awayBar.style.width = `${awayHype}%`;
        }

        function tickSimulation() {
            if (isPaused) return;

            if (minute >= 90) {
                clearInterval(simInterval);
                logCommentary("[FULL TIME] The referee blows the final whistle! Match has ended.", "text-amber-400 font-bold font-heading text-sm");
                // End screen pulsing glow
                const container = document.getElementById('apex-simulator-container');
                if (container) {
                    container.classList.add('border-green-500/40');
                    container.style.animation = 'pulse-gold 2s infinite';
                }
                return;
            }

            minute++;
            const clock = document.getElementById('sim-clock');
            if (clock) clock.textContent = `${minute}'`;

            // Calculate possession chance dynamically
            // Combining rating weighting and fan cheering hype
            const homeWeight = homeTeamRatingVal + (homeHype - 50);
            const awayWeight = awayTeamRatingVal + (awayHype - 50);
            const totalWeight = homeWeight + awayWeight;
            const homePossessionProb = Math.round((homeWeight / totalWeight) * 100);

            // Fluctuating dynamic possession percentages
            const currentHomePoss = Math.max(30, Math.min(70, Math.round(homePossessionProb + (Math.random() * 10 - 5))));
            const currentAwayPoss = 100 - currentHomePoss;

            const homePossText = document.getElementById('sim-home-possession');
            const awayPossText = document.getElementById('sim-away-possession');
            const homePossBar = document.getElementById('sim-home-poss-bar');
            const awayPossBar = document.getElementById('sim-away-poss-bar');

            if (homePossText) homePossText.textContent = currentHomePoss;
            if (awayPossText) awayPossText.textContent = currentAwayPoss;
            if (homePossBar) homePossBar.style.width = `${currentHomePoss}%`;
            if (awayPossBar) awayPossBar.style.width = `${currentAwayPoss}%`;

            // Decide possessing team
            const attackingTeam = Math.random() * 100 < currentHomePoss ? 'home' : 'away';

            // Update Ball Position on pitch
            const ball = document.getElementById('sim-ball');
            if (ball) {
                if (attackingTeam === 'home') {
                    const positions = ['25%', '35%', '45%', '65%', '85%'];
                    const randomPos = positions[Math.floor(Math.random() * positions.length)];
                    ball.style.left = randomPos;
                } else {
                    const positions = ['75%', '65%', '55%', '35%', '15%'];
                    const randomPos = positions[Math.floor(Math.random() * positions.length)];
                    ball.style.left = randomPos;
                }
            }

            // Trigger events occasionally
            const eventChance = Math.random() * 100;

            if (eventChance < 15) {
                // A Shot is taken!
                if (attackingTeam === 'home') {
                    homeShots++;
                    const hs = document.getElementById('sim-home-shots');
                    if (hs) hs.textContent = homeShots;

                    const shooter = getRandomPlayer(homeFW, homePlayersList);
                    const onTarget = Math.random() * 100 < 40;

                    if (onTarget) {
                        const isGoal = Math.random() * 100 < 30;
                        if (isGoal) {
                            homeScore++;
                            const hsc = document.getElementById('sim-home-score');
                            if (hsc) hsc.textContent = homeScore;
                            logCommentary(`⚽ GOAL!!! ${shooter.name} unleashes a ferocious shot into the top corner! Stunning finish!`, "text-green-400 font-bold");
                            triggerGoalVisual();
                        } else {
                            awaySaves++;
                            const asv = document.getElementById('sim-away-saves');
                            if (asv) asv.textContent = awaySaves;
                            const gk = getRandomPlayer(awayGK, awayPlayersList);
                            logCommentary(`🧤 Shot on Target by ${shooter.name}! But ${gk.name} pulls off a spectacular diving save!`, "text-cyan-400");
                        }
                    } else {
                        logCommentary(`💨 Close! ${shooter.name} tries a volley from long range, but it flies just wide of the post.`);
                    }
                } else {
                    awayShots++;
                    const as = document.getElementById('sim-away-shots');
                    if (as) as.textContent = awayShots;

                    const shooter = getRandomPlayer(awayFW, awayPlayersList);
                    const onTarget = Math.random() * 100 < 40;

                    if (onTarget) {
                        const isGoal = Math.random() * 100 < 30;
                        if (isGoal) {
                            awayScore++;
                            const asc = document.getElementById('sim-away-score');
                            if (asc) asc.textContent = awayScore;
                            logCommentary(`⚽ GOAL!!! ${shooter.name} beats the defender and taps it coolly past the keeper!`, "text-green-400 font-bold");
                            triggerGoalVisual();
                        } else {
                            homeSaves++;
                            const hsv = document.getElementById('sim-home-saves');
                            if (hsv) hsv.textContent = homeSaves;
                            const gk = getRandomPlayer(homeGK, homePlayersList);
                            logCommentary(`🧤 Brilliant header by ${shooter.name}! Saved by a reflexive stretch from ${gk.name}!`, "text-cyan-400");
                        }
                    } else {
                        logCommentary(`💨 Deflected! ${shooter.name} shoots from outside the box, but it is blocked by a solid defensive line.`);
                    }
                }
            } else if (eventChance < 18) {
                // Booking or foul
                const bookingType = Math.random() * 100;
                if (attackingTeam === 'home') {
                    const def = getRandomPlayer(awayDF, awayPlayersList);
                    if (bookingType < 70) {
                        awayYellows++;
                        const ay = document.getElementById('sim-away-yellows');
                        if (ay) ay.textContent = awayYellows;
                        logCommentary(`🟨 Yellow Card! ${def.name} gets a caution for a cynical pull-back on a counter-attack.`, "text-yellow-400");
                    } else if (bookingType < 85) {
                        awayReds++;
                        const ar = document.getElementById('sim-away-reds');
                        if (ar) ar.textContent = awayReds;
                        logCommentary(`🟥 RED CARD! ${def.name} is sent off for a reckless sliding tackle! Massive drama!`, "text-red-500 font-bold");
                    } else {
                        logCommentary(`⚠️ Foul committed by ${def.name}. Free kick awarded in a dangerous area.`);
                    }
                } else {
                    const def = getRandomPlayer(homeDF, homePlayersList);
                    if (bookingType < 70) {
                        homeYellows++;
                        const hy = document.getElementById('sim-home-yellows');
                        if (hy) hy.textContent = homeYellows;
                        logCommentary(`🟨 Yellow Card! ${def.name} is booked for an aggressive slide tackle.`, "text-yellow-400");
                    } else if (bookingType < 85) {
                        homeReds++;
                        const hr = document.getElementById('sim-home-reds');
                        if (hr) hr.textContent = homeReds;
                        logCommentary(`🟥 RED CARD! ${def.name} receives a straight red for a last-man challenge!`, "text-red-500 font-bold");
                    } else {
                        logCommentary(`⚠️ Referee stops play. Technical foul by ${def.name} in the center circle.`);
                    }
                }
            } else {
                // Regular play/passing commentary
                if (minute % 8 === 0) {
                    if (attackingTeam === 'home') {
                        const mid = getRandomPlayer(homeMF, homePlayersList);
                        logCommentary(`${mid.name} sprays a beautiful diagonal ball out wide to split the defense.`);
                    } else {
                        const mid = getRandomPlayer(awayMF, awayPlayersList);
                        logCommentary(`${mid.name} controls the ball elegantly, calming the tempo and looking for options.`);
                    }
                }
            }
        }

        function triggerGoalVisual() {
            const container = document.getElementById('apex-simulator-container');
            if (container) {
                container.style.boxShadow = "0 0 50px rgba(34,197,94,0.6)";
                container.style.borderColor = "rgba(34,197,94,0.8)";
                setTimeout(() => {
                    container.style.boxShadow = "";
                    container.style.borderColor = "";
                }, 1500);
            }
        }
    </script>
</body>
</html>
