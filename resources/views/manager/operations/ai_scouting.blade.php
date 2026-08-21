@extends('layouts.manager')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="glass-card p-6 md:p-8 relative overflow-hidden border-cyan-500/20 bg-gradient-to-r from-cyan-950/30 via-slate-900/60 to-black">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
            <div>
                <div class="flex items-center gap-2 text-cyan-400 font-mono text-xs tracking-widest uppercase mb-2">
                    <i data-lucide="cpu" class="w-4 h-4"></i> Apex AI Match Intelligence & Scouting Engine
                </div>
                <h1 class="text-3xl md:text-4xl font-black font-heading tracking-wider uppercase text-white">
                    Tactical Scouting Assistant
                </h1>
                <p class="text-gray-400 text-sm mt-1 max-w-2xl">
                    Deep opponent squad breakdown, key player threat analysis, structural weakness detection, and custom counter-formation strategy recommendations.
                </p>
            </div>

            <!-- Opponent Selection Dropdown -->
            <form method="GET" action="{{ route('manager.scouting.ai') }}" class="flex items-center gap-3 bg-white/5 p-2 rounded-xl border border-white/10">
                <label for="opponent_id" class="text-xs uppercase font-bold text-gray-400 pl-2">Opponent:</label>
                <select name="opponent_id" id="opponent_id" onchange="this.form.submit()" class="bg-black/80 text-cyan-300 font-bold border border-cyan-500/30 rounded-lg px-4 py-2 text-sm focus:outline-none focus:border-cyan-400">
                    @foreach($otherTeams as $team)
                        <option value="{{ $team->id }}" {{ $opponent && $opponent->id == $team->id ? 'selected' : '' }}>
                            {{ $team->team_name }} ({{ $team->division ?? 'Division 1' }})
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>

    @if($opponent && $analysis)
        <!-- Scouting Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Left Panel: Matchup Overview & Threat Gauge -->
            <div class="space-y-6">
                <!-- Opponent Card -->
                <div class="glass-card p-6 border-white/10 space-y-4">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-gray-400">Target Opponent</span>
                            <h2 class="text-2xl font-black font-heading text-cyan-400 uppercase tracking-wide">{{ $opponent->team_name }}</h2>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-black tracking-wider uppercase border
                            {{ $analysis['threat_level'] === 'HIGH' ? 'bg-red-500/20 text-red-400 border-red-500/30' : ($analysis['threat_level'] === 'LOW' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-amber-500/20 text-amber-400 border-amber-500/30') }}">
                            Threat Level: {{ $analysis['threat_level'] }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white/5 p-3 rounded-lg border border-white/5">
                            <div class="text-xs text-gray-400 uppercase font-bold">Squad Avg Rating</div>
                            <div class="text-2xl font-black text-cyan-300 font-heading">{{ $analysis['opp_avg_rating'] }}</div>
                        </div>
                        <div class="bg-white/5 p-3 rounded-lg border border-white/5">
                            <div class="text-xs text-gray-400 uppercase font-bold">Base Formation</div>
                            <div class="text-2xl font-black text-amber-400 font-heading">{{ $analysis['opp_formation'] }}</div>
                        </div>
                    </div>

                    <div class="bg-white/5 p-4 rounded-lg border border-white/5 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase">Recent Form (Last 5):</span>
                            <span class="font-mono font-bold text-white tracking-widest">{{ $analysis['form'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-400 font-bold uppercase">Your Team Avg:</span>
                            <span class="font-mono font-bold text-emerald-400">{{ $analysis['user_avg_rating'] }} OVR</span>
                        </div>
                    </div>
                </div>

                <!-- Key Star Player Threat -->
                <div class="glass-card p-6 border-amber-500/20 bg-amber-950/10 space-y-3">
                    <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                        <i data-lucide="zap" class="w-4 h-4"></i> Key Threat Callout
                    </div>
                    @if($analysis['star_player'])
                        <div class="space-y-1">
                            <h3 class="text-xl font-black font-heading text-white uppercase">{{ $analysis['star_player']->name }}</h3>
                            <div class="flex items-center gap-3 text-xs text-gray-300">
                                <span>Pos: <strong class="text-amber-400">{{ $analysis['star_player']->position }}</strong></span>
                                <span>•</span>
                                <span>Rating: <strong class="text-amber-400">{{ $analysis['star_player']->rating }} OVR</strong></span>
                                <span>•</span>
                                <span>Goals: <strong class="text-amber-400">{{ $analysis['star_player']->goals ?? 0 }}</strong></span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 leading-relaxed italic border-t border-amber-500/20 pt-2">
                            AI Alert: High individual impact player. Recommend double coverage or tactical pressing when entering attacking third.
                        </p>
                    @else
                        <p class="text-xs text-gray-400">No specific key star player identified for this squad.</p>
                    @endif
                </div>
            </div>

            <!-- Middle & Right Panel: AI Tactical Counter-Strategy -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tactical Analysis & Counter Plan -->
                <div class="glass-card p-6 border-cyan-500/20 space-y-6">
                    <div class="flex items-center justify-between border-b border-white/10 pb-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="shield-alert" class="w-6 h-6 text-cyan-400"></i>
                            <h2 class="text-xl font-black font-heading text-white uppercase tracking-wider">AI Counter-Formation Recommendation</h2>
                        </div>
                        <span class="text-xs text-cyan-400 font-mono">94.2% AI Confidence</span>
                    </div>

                    <!-- Formation Matchup Card -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-black/40 p-4 rounded-xl border border-red-500/20 space-y-2">
                            <div class="text-xs text-red-400 font-bold uppercase tracking-wider">Opponent System</div>
                            <div class="text-3xl font-black font-heading text-white">{{ $analysis['opp_formation'] }}</div>
                            <p class="text-xs text-gray-400">Standard tactical deployment. Tends to build play through wide flanks.</p>
                        </div>

                        <div class="bg-cyan-950/30 p-4 rounded-xl border border-cyan-500/30 space-y-2">
                            <div class="text-xs text-cyan-400 font-bold uppercase tracking-wider">Recommended Counter</div>
                            <div class="text-3xl font-black font-heading text-cyan-300">{{ $analysis['counter_formation'] }}</div>
                            <p class="text-xs text-cyan-200/80">Optimal shape to negate opposition passing lanes and exploit central gaps.</p>
                        </div>
                    </div>

                    <!-- AI Playstyle & Instructions -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 flex items-center gap-2">
                            <i data-lucide="brain-circuit" class="w-4 h-4 text-cyan-400"></i> Key Tactical Directives
                        </h3>
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10 space-y-3">
                            <p class="text-sm text-gray-200 leading-relaxed font-medium">
                                {{ $analysis['tactical_advice'] }}
                            </p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-2 pt-2 border-t border-white/10">
                                <div class="bg-black/30 p-2.5 rounded-lg border border-white/5 text-center">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">Defensive Line</div>
                                    <div class="text-xs font-bold text-cyan-400 mt-0.5">High Pressing Block</div>
                                </div>
                                <div class="bg-black/30 p-2.5 rounded-lg border border-white/5 text-center">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">Passing Style</div>
                                    <div class="text-xs font-bold text-cyan-400 mt-0.5">Direct Vertical Tempo</div>
                                </div>
                                <div class="bg-black/30 p-2.5 rounded-lg border border-white/5 text-center">
                                    <div class="text-[10px] text-gray-400 uppercase font-bold">Wing Coverage</div>
                                    <div class="text-xs font-bold text-cyan-400 mt-0.5">Overlapping Overloads</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Squad Comparison Table -->
                    <div class="space-y-3">
                        <h3 class="text-sm font-bold uppercase tracking-wider text-gray-300 flex items-center gap-2">
                            <i data-lucide="users" class="w-4 h-4 text-cyan-400"></i> Opponent Roster Roster Sample
                        </h3>
                        <div class="overflow-x-auto rounded-xl border border-white/10 bg-black/20">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-white/5 text-gray-400 uppercase tracking-wider border-b border-white/10">
                                    <tr>
                                        <th class="p-3">Player Name</th>
                                        <th class="p-3">Position</th>
                                        <th class="p-3 text-center">Rating</th>
                                        <th class="p-3 text-center">Goals</th>
                                        <th class="p-3 text-center">Assists</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-gray-300">
                                    @forelse($opponent->players->sortByDesc('rating')->take(6) as $p)
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="p-3 font-bold text-white">{{ $p->name }}</td>
                                            <td class="p-3 text-cyan-400">{{ $p->position }}</td>
                                            <td class="p-3 text-center font-mono font-bold text-amber-400">{{ $p->rating }}</td>
                                            <td class="p-3 text-center font-mono">{{ $p->goals ?? 0 }}</td>
                                            <td class="p-3 text-center font-mono">{{ $p->assists ?? 0 }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="p-4 text-center text-gray-500">No registered players in opponent roster.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    @else
        <div class="glass-card p-12 text-center text-gray-400 space-y-3">
            <i data-lucide="users" class="w-12 h-12 mx-auto text-gray-600"></i>
            <p>No opposing teams available for scouting analysis.</p>
        </div>
    @endif
</div>
@endsection
