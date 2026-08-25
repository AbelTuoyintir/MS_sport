<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fantasy Premier Hub — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-dark: #06090e;
            --accent-gold: #f0c040;
            --glass-white: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }
        body {
            font-family: 'Barlow', sans-serif;
            background-color: var(--bg-dark);
            color: #e8edf4;
        }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card {
            background: var(--glass-white);
            backdrop-filter: blur(12px);
            border: 1px solid var(--glass-border);
            border-radius: 1rem;
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navigation -->
    <nav class="border-b border-white/10 bg-black/40 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-amber-400 rounded flex items-center justify-center">
                        <i data-lucide="trophy" class="w-5 h-5 text-black"></i>
                    </div>
                    <span class="font-black font-heading text-xl uppercase tracking-wider text-white">MP League</span>
                </a>
                <div class="flex items-center gap-4">
                    <a href="{{ route('home') }}" class="text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white">Home</a>
                    <a href="{{ route('stats') }}" class="text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white">Stats</a>
                    <a href="{{ route('rankings') }}" class="text-sm font-bold uppercase tracking-wider text-gray-400 hover:text-white">Rankings</a>
                    <a href="{{ route('fantasy.index') }}" class="text-sm font-bold uppercase tracking-wider text-amber-400">Fantasy Hub</a>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Banner Header -->
        <div class="glass-card p-6 md:p-8 mb-8 relative overflow-hidden bg-gradient-to-r from-amber-500/10 via-purple-500/10 to-emerald-500/10 border border-amber-500/20">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative z-10">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase bg-amber-400/20 text-amber-400 border border-amber-400/30">Official Fantasy League</span>
                        <span class="text-xs text-gray-400 font-semibold uppercase tracking-wider">Budget: £100.0M</span>
                    </div>
                    <h1 class="font-heading text-4xl md:text-5xl font-black uppercase text-white tracking-wide">Fantasy League Hub</h1>
                    <p class="text-gray-400 text-sm max-w-xl mt-1">Build your dream squad, select your captain, track live points from real player performances, and compete on the global leaderboard.</p>
                </div>
                <div class="flex items-center gap-6 bg-black/40 px-6 py-4 rounded-xl border border-white/10">
                    <div class="text-center">
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider">Your Points</div>
                        <div class="font-heading text-3xl font-black text-amber-400">{{ $fantasyTeam ? $fantasyTeam->total_points : 0 }}</div>
                    </div>
                    <div class="h-8 w-px bg-white/10"></div>
                    <div class="text-center">
                        <div class="text-xs text-gray-400 font-bold uppercase tracking-wider">Budget Left</div>
                        <div class="font-heading text-3xl font-black text-emerald-400">£{{ number_format($fantasyTeam ? $fantasyTeam->budget_remaining : 100.0, 1) }}M</div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-sm font-bold flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/30 text-red-400 text-sm font-bold">
                @foreach($errors->all() as $err)
                    <div>⚠️ {{ $err }}</div>
                @endforeach
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left 2 Cols: Pitch View & Squad Selection -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Pitch Card -->
                <div class="glass-card p-6 bg-emerald-950/20 border-emerald-500/20 relative min-h-[480px] flex flex-col justify-between overflow-hidden">
                    <div class="flex items-center justify-between mb-4 z-10">
                        <h2 class="font-heading text-xl font-bold uppercase text-white flex items-center gap-2">
                            <i data-lucide="shield" class="w-5 h-5 text-emerald-400"></i> Active Squad Lineup
                        </h2>
                        <span class="text-xs text-emerald-400 font-bold uppercase tracking-wider bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                            {{ $fantasyTeam ? $fantasyTeam->name : 'No Squad Created' }}
                        </span>
                    </div>

                    <!-- Soccer Pitch Background Graphics -->
                    <div class="absolute inset-0 z-0 opacity-15 pointer-events-none p-4">
                        <div class="w-full h-full border-2 border-white rounded-xl relative">
                            <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-white -translate-y-1/2"></div>
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-28 h-28 border-2 border-white rounded-full"></div>
                        </div>
                    </div>

                    <!-- Dynamic Pitch Player Displays -->
                    <div class="relative z-10 space-y-8 py-4">
                        @if($fantasyTeam && $fantasyTeam->fantasyPlayers->count() > 0)
                            <div class="flex justify-around">
                                @foreach($fantasyTeam->fantasyPlayers as $fp)
                                    <div class="flex flex-col items-center group">
                                        <div class="w-12 h-12 rounded-full border-2 {{ $fp->is_captain ? 'border-amber-400 bg-amber-400/20' : 'border-emerald-400 bg-emerald-400/20' }} flex items-center justify-center font-bold text-white shadow-lg group-hover:scale-110 transition-transform">
                                            {{ substr($fp->player->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-bold text-white bg-black/70 px-2 py-0.5 rounded mt-1">
                                            {{ $fp->player->name }} {{ $fp->is_captain ? ' (C)' : '' }}
                                        </span>
                                        <span class="text-[10px] text-amber-400 font-bold uppercase">{{ $fp->player->team->team_name ?? 'League' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-16 text-gray-400">
                                <i data-lucide="users" class="w-12 h-12 mx-auto mb-3 text-gray-500"></i>
                                <p class="text-sm font-semibold">You haven't built a squad yet!</p>
                                <p class="text-xs text-gray-500 mt-1">Use the squad creation form below to pick your fantasy players within the £100M budget.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Squad Selection / Creation Form -->
                <div class="glass-card p-6">
                    <h2 class="font-heading text-xl font-bold uppercase text-white mb-4 flex items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5 text-amber-400"></i> Create / Manage Fantasy Squad
                    </h2>

                    <form action="{{ route('fantasy.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Squad Name</label>
                            <input type="text" name="name" value="{{ $fantasyTeam ? $fantasyTeam->name : 'Apex XI' }}" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-amber-400 outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Select 5 Players (Max £100M total budget)</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-72 overflow-y-auto p-2 bg-black/30 rounded-xl border border-white/5">
                                @foreach($players as $p)
                                    <label class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5 hover:border-amber-400/50 cursor-pointer transition-colors">
                                        <div class="flex items-center gap-3">
                                            <input type="checkbox" name="player_ids[]" value="{{ $p->id }}"
                                                {{ ($fantasyTeam && $fantasyTeam->fantasyPlayers->pluck('player_id')->contains($p->id)) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded text-amber-400 focus:ring-0 bg-black border-white/20">
                                            <div>
                                                <div class="text-xs font-bold text-white">{{ $p->name }}</div>
                                                <div class="text-[10px] text-gray-400">{{ $p->position ?? 'ST' }} · {{ $p->team->team_name ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs font-bold text-emerald-400">£{{ $p->fantasy_cost }}M</div>
                                            <div class="text-[9px] text-amber-400 font-bold">{{ $p->fantasy_points }} pts</div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Select Captain (2x Points multiplier)</label>
                            <select name="captain_id" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-amber-400 outline-none">
                                @foreach($players as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->team->team_name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="w-full py-3 bg-amber-400 hover:bg-amber-300 text-black font-heading text-lg font-black uppercase tracking-wider rounded-xl transition-all shadow-lg shadow-amber-400/10">
                            Save Fantasy Squad
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Col: Leaderboard & Player Rankings -->
            <div class="space-y-8">
                <!-- Leaderboard Card -->
                <div class="glass-card p-6">
                    <h2 class="font-heading text-xl font-bold uppercase text-white mb-4 flex items-center justify-between">
                        <span class="flex items-center gap-2"><i data-lucide="award" class="w-5 h-5 text-amber-400"></i> Global Leaderboard</span>
                    </h2>
                    <div class="space-y-3">
                        @forelse($leaderboard as $index => $team)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                                <div class="flex items-center gap-3">
                                    <span class="font-heading font-black text-lg {{ $index === 0 ? 'text-amber-400' : ($index === 1 ? 'text-gray-300' : ($index === 2 ? 'text-amber-700' : 'text-gray-500')) }}">
                                        #{{ $index + 1 }}
                                    </span>
                                    <div>
                                        <div class="text-xs font-bold text-white">{{ $team->name }}</div>
                                        <div class="text-[10px] text-gray-400">{{ $team->user->name ?? 'Manager' }}</div>
                                    </div>
                                </div>
                                <div class="font-heading font-black text-lg text-amber-400">
                                    {{ $team->total_points }} <span class="text-xs font-normal text-gray-400">pts</span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-xs text-gray-500">No managers on the leaderboard yet.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Top Fantasy Player Performers -->
                <div class="glass-card p-6">
                    <h2 class="font-heading text-xl font-bold uppercase text-white mb-4 flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-5 h-5 text-amber-400"></i> Top Point Scorers
                    </h2>
                    <div class="space-y-3">
                        @foreach($players->take(5) as $p)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/5">
                                <div>
                                    <div class="text-xs font-bold text-white">{{ $p->name }}</div>
                                    <div class="text-[10px] text-gray-400">{{ $p->team->team_name ?? 'N/A' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-heading font-black text-base text-amber-400">{{ $p->fantasy_points }} pts</div>
                                    <div class="text-[10px] text-emerald-400 font-bold">£{{ $p->fantasy_cost }}M</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
