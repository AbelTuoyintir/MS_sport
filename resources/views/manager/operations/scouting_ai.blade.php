@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-accent-gold text-xs font-bold uppercase tracking-widest mb-1">
                <span>🤖 APEX LEAGUE AI ENGINE</span>
                <span class="w-1.5 h-1.5 rounded-full bg-[#00e5ff] animate-ping"></span>
            </div>
            <h2 class="text-3xl font-heading font-black tracking-wide uppercase text-white">Tactical Scout Intelligence</h2>
            <p class="text-gray-400 text-sm">Empower your squad with advanced, real-time tactical analyses and counter-strategy generation against any league opponent.</p>
        </div>
        <a href="{{ route('manager.scouting.index') }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wider bg-white/5 border border-white/10 text-gray-300 hover:text-white hover:bg-white/10 rounded-lg flex items-center gap-1.5 self-start md:self-auto">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i> Back to Scouting Reports
        </a>
    </div>

    <!-- Main Content Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        <!-- Sidebar Input Form -->
        <div class="lg:col-span-4 space-y-6">
            <div class="glass-card p-6 border border-white/10">
                <div class="flex items-center gap-2 border-b border-white/5 pb-4 mb-4">
                    <i data-lucide="sliders" class="w-5 h-5 text-accent-gold"></i>
                    <h3 class="text-lg font-heading font-bold uppercase tracking-wide">Analysis Parameters</h3>
                </div>

                <form action="{{ route('manager.scouting.ai.generate') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Opponent Dropdown -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Target Opponent</label>
                        <select name="opponent_team_id" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                            <option value="">-- Select Opponent --</option>
                            @foreach($opponents as $opp)
                                <option value="{{ $opp->id }}" {{ (isset($report) && $report['opponent_name'] === $opp->team_name) ? 'selected' : '' }}>
                                    {{ $opp->team_name }} (OVR {{ round($opp->players->avg('rating') ?: 75) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Opponent Formation -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Opponent Formation</label>
                        <select name="opponent_formation" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                            <option value="4-3-3" {{ (isset($report) && $report['opponent_formation'] === '4-3-3') ? 'selected' : '' }}>4-3-3 (Attacking Wide)</option>
                            <option value="4-4-2" {{ (isset($report) && $report['opponent_formation'] === '4-4-2') ? 'selected' : '' }}>4-4-2 (Balanced/Direct)</option>
                            <option value="3-5-2" {{ (isset($report) && $report['opponent_formation'] === '3-5-2') ? 'selected' : '' }}>3-5-2 (Central Overload)</option>
                            <option value="4-2-3-1" {{ (isset($report) && $report['opponent_formation'] === '4-2-3-1') ? 'selected' : '' }}>4-2-3-1 (Modern Creative)</option>
                        </select>
                    </div>

                    <!-- Playstyle Style -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Opponent Play Style</label>
                        <select name="tactic_style" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                            <option value="Tiki-Taka" {{ (isset($report) && $report['tactic_style'] === 'Tiki-Taka') ? 'selected' : '' }}>Tiki-Taka (Short Passing/Possession)</option>
                            <option value="Gengenpress" {{ (isset($report) && $report['tactic_style'] === 'Gengenpress') ? 'selected' : '' }}>Gegenpress (Intense High Press)</option>
                            <option value="Direct Counter" {{ (isset($report) && $report['tactic_style'] === 'Direct Counter') ? 'selected' : '' }}>Direct Counter (Fast Breaks)</option>
                            <option value="Park the Bus" {{ (isset($report) && $report['tactic_style'] === 'Park the Bus') ? 'selected' : '' }}>Park the Bus (Low Block Defense)</option>
                        </select>
                    </div>

                    <!-- Target Intensity -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Counter Intensity Plan</label>
                        <select name="intensity" required class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                            <option value="Balanced" {{ (isset($report) && $report['intensity'] === 'Balanced') ? 'selected' : '' }}>Balanced (Standard Risks)</option>
                            <option value="Conservative" {{ (isset($report) && $report['intensity'] === 'Conservative') ? 'selected' : '' }}>Conservative (Safety First)</option>
                            <option value="Overload" {{ (isset($report) && $report['intensity'] === 'Overload') ? 'selected' : '' }}>Overload (High Risks / Attack)</option>
                        </select>
                    </div>

                    <!-- Generate Button -->
                    <button type="submit" class="w-full bg-accent-gold text-bg-dark hover:bg-yellow-500 font-heading font-black text-sm uppercase py-3 px-4 rounded-xl transition-all shadow-lg flex items-center justify-center gap-2 mt-2">
                        <i data-lucide="zap" class="w-4 h-4 text-bg-dark fill-bg-dark"></i> Generate Tactical Report
                    </button>
                </form>
            </div>
        </div>

        <!-- Intelligence Output Section -->
        <div class="lg:col-span-8">
            @if(isset($report))
                <div class="space-y-6">

                    <!-- Top Summary Card -->
                    <div class="glass-card p-6 border-l-4 border-accent-gold bg-gradient-to-r from-yellow-500/5 to-transparent">
                        <div class="flex items-center gap-3 mb-3 text-accent-gold font-bold uppercase tracking-wider text-xs">
                            <i data-lucide="eye" class="w-4 h-4"></i> Executive Scouting Summary
                        </div>
                        <p class="text-sm text-gray-200 leading-relaxed">{{ $report['scouting_summary'] }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Tactical Setup Counter -->
                        <div class="glass-card p-6 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
                                    <i data-lucide="compass" class="w-4 h-4 text-[#00e5ff]"></i>
                                    <h4 class="font-heading font-bold text-md uppercase text-white tracking-wide">Recommended Lineup Setup</h4>
                                </div>

                                <div class="space-y-4">
                                    <!-- Formation -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-400 font-semibold">Counter Formation</span>
                                        <span class="px-2.5 py-1 bg-emerald-500/15 text-emerald-400 font-black text-xs rounded-lg uppercase tracking-wide border border-emerald-500/20">
                                            {{ $report['counter_formation'] }}
                                        </span>
                                    </div>

                                    <!-- Passing Focus -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-400 font-semibold">Suggested Passing Focus</span>
                                        <span class="text-sm font-bold text-white">{{ $report['passing_focus'] }}</span>
                                    </div>

                                    <!-- Defensive Line -->
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-400 font-semibold">Defensive Line Depth</span>
                                        <span class="text-sm font-bold text-white">{{ $report['defensive_line'] }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between text-xs text-gray-500">
                                <span>Adjust starting XI formation on the <a href="{{ route('manager.tactics.index') }}" class="text-accent-gold hover:underline">Tactics Page</a>.</span>
                            </div>
                        </div>

                        <!-- Opponent Star Watch -->
                        <div class="glass-card p-6 bg-gradient-to-br from-red-500/5 to-transparent border border-red-500/10">
                            <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
                                <i data-lucide="shield-alert" class="w-4 h-4 text-rose-500"></i>
                                <h4 class="font-heading font-bold text-md uppercase text-white tracking-wide">Star Opponent Threat</h4>
                            </div>

                            @if($report['star_player'])
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center font-bold text-rose-400 border border-white/10">
                                        {{ $report['star_player']->position }}
                                    </div>
                                    <div>
                                        <div class="font-black text-white text-md">{{ $report['star_player']->name }}</div>
                                        <div class="text-xs text-gray-500">OVR: <span class="font-bold text-rose-400">{{ $report['star_player']->rating }}</span> · {{ $report['star_player']->nationality }}</div>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-300 leading-relaxed bg-black/20 p-3 rounded-lg border border-white/5">
                                    {{ $report['star_watch'] }}
                                </p>
                            @else
                                <p class="text-xs text-gray-400 italic">No standout players found on target squad. Maintain standard tactical assignments.</p>
                            @endif
                        </div>

                    </div>

                    <!-- Win Confidence / Probability Bar -->
                    <div class="glass-card p-6">
                        <div class="flex items-center gap-2 border-b border-white/5 pb-3 mb-4">
                            <i data-lucide="trending-up" class="w-4 h-4 text-[#00e5ff]"></i>
                            <h4 class="font-heading font-bold text-md uppercase text-white tracking-wide">Win Confidence Engine Projections</h4>
                        </div>

                        <!-- Bar display -->
                        <div class="h-4 w-full bg-white/10 rounded-full overflow-hidden flex mb-4 border border-white/5">
                            <div class="h-full bg-emerald-500 flex items-center justify-center text-[10px] font-black text-black transition-all duration-300" style="width: {{ $report['probabilities']['win'] }}%">
                                @if($report['probabilities']['win'] > 15) {{ $report['probabilities']['win'] }}% WIN @endif
                            </div>
                            <div class="h-full bg-yellow-500 flex items-center justify-center text-[10px] font-black text-black transition-all duration-300" style="width: {{ $report['probabilities']['draw'] }}%">
                                @if($report['probabilities']['draw'] > 15) {{ $report['probabilities']['draw'] }}% DRAW @endif
                            </div>
                            <div class="h-full bg-rose-500 flex items-center justify-center text-[10px] font-black text-black transition-all duration-300" style="width: {{ $report['probabilities']['loss'] }}%">
                                @if($report['probabilities']['loss'] > 15) {{ $report['probabilities']['loss'] }}% LOSS @endif
                            </div>
                        </div>

                        <!-- Matchup Ratings Comparative -->
                        <div class="grid grid-cols-2 gap-4 text-center mt-6 pt-4 border-t border-white/5">
                            <div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">{{ $myTeam->team_name }} (Your Team)</div>
                                <div class="text-2xl font-display text-emerald-400">{{ $report['my_rating'] }} <span class="text-xs text-gray-400 font-sans uppercase font-bold">OVR</span></div>
                            </div>
                            <div>
                                <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest mb-1">{{ $report['opponent_name'] }} (Opponent)</div>
                                <div class="text-2xl font-display text-rose-400">{{ $report['opp_rating'] }} <span class="text-xs text-gray-400 font-sans uppercase font-bold">OVR</span></div>
                            </div>
                        </div>
                    </div>

                </div>
            @else
                <!-- Pre-State: Awaiting Parameter submission -->
                <div class="glass-card p-12 text-center flex flex-col items-center justify-center min-h-[350px] border border-white/10">
                    <div class="w-16 h-16 rounded-full bg-white/5 flex items-center justify-center text-accent-gold mb-4 border border-white/5">
                        <i data-lucide="scan-eye" class="w-8 h-8 text-accent-gold"></i>
                    </div>
                    <h3 class="text-2xl font-heading font-bold text-white uppercase tracking-wider mb-2">Awaiting Parameters</h3>
                    <p class="text-gray-400 max-w-sm text-sm">Select a target opponent, their current formation, and strategic style from the left pane to run advanced tactical counter-simulations.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
