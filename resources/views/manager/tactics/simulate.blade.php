@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-black font-heading uppercase tracking-wider text-white">Tactical Match Simulator</h2>
            <p class="text-gray-400 text-sm">Test your tactics and starting XI in a simulated friendly match against other teams.</p>
        </div>
        <a href="{{ route('manager.tactics.index') }}" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg hover:bg-white/10 transition-all font-bold text-sm text-gray-300">
            &larr; Back to Tactics
        </a>
    </div>

    @if(session('error'))
        <div class="p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Setup Simulation Form -->
        <div class="lg:col-span-1">
            <div class="glass-card p-6 space-y-6">
                <h3 class="text-xl font-bold border-b border-white/10 pb-3 flex items-center gap-2">
                    <span class="text-accent-gold">1.</span> Match Settings
                </h3>

                <form action="{{ route('manager.tactics.simulate.run') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-gray-400 text-xs uppercase font-bold tracking-wider mb-2">Select Opponent</label>
                        <select name="opponent_team_id" required class="w-full bg-bg-dark border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:border-accent-gold">
                            <option value="" disabled selected>-- Choose Team --</option>
                            @foreach($opponents as $opp)
                                <option value="{{ $opp->id }}" {{ (isset($simulationResult) && $simulationResult['opponent_team']->id == $opp->id) ? 'selected' : '' }}>
                                    {{ $opp->team_name }} ({{ $opp->division }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-xs uppercase font-bold tracking-wider mb-3">Tactical Strategy</label>
                        <div class="space-y-2">
                            <label class="block cursor-pointer">
                                <input type="radio" name="strategy" value="attacking" class="peer hidden" {{ (isset($simulationResult) && $simulationResult['strategy'] === 'attacking') ? 'checked' : '' }}>
                                <div class="p-3 border border-white/5 rounded-xl bg-white/5 peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 hover:bg-white/10 transition-all">
                                    <p class="font-bold text-sm text-white">Attacking</p>
                                    <p class="text-[11px] text-gray-400">Boosts shot frequency and goal probability, higher risk of conceding.</p>
                                </div>
                            </label>

                            <label class="block cursor-pointer">
                                <input type="radio" name="strategy" value="balanced" class="peer hidden" {{ (!isset($simulationResult) || $simulationResult['strategy'] === 'balanced') ? 'checked' : 'true' }} {{ (isset($simulationResult) && $simulationResult['strategy'] === 'balanced') ? 'checked' : '' }}>
                                <div class="p-3 border border-white/5 rounded-xl bg-white/5 peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 hover:bg-white/10 transition-all">
                                    <p class="font-bold text-sm text-white">Balanced</p>
                                    <p class="text-[11px] text-gray-400">Standard distribution. Focuses on core squad ratings and shape.</p>
                                </div>
                            </label>

                            <label class="block cursor-pointer">
                                <input type="radio" name="strategy" value="defensive" class="peer hidden" {{ (isset($simulationResult) && $simulationResult['strategy'] === 'defensive') ? 'checked' : '' }}>
                                <div class="p-3 border border-white/5 rounded-xl bg-white/5 peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 hover:bg-white/10 transition-all">
                                    <p class="font-bold text-sm text-white">Defensive</p>
                                    <p class="text-[11px] text-gray-400">Reduces opponent chances, solidifies backline, lower chance of scoring.</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-accent-gold text-bg-dark font-black uppercase tracking-widest py-4 rounded-xl hover:bg-[#fff0a0] transition-all flex items-center justify-center gap-2">
                        <span>Simulate Match</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Simulation Result & Match Screen -->
        <div class="lg:col-span-2">
            @if(isset($simulationResult))
                <div class="space-y-6">
                    <!-- Scoreboard -->
                    <div class="glass-card p-8 bg-gradient-to-r from-green-950/40 via-bg-dark to-slate-900/40 relative overflow-hidden">
                        <div class="absolute inset-0 bg-radial-gradient opacity-10 pointer-events-none"></div>
                        <div class="flex items-center justify-between relative z-10">
                            <!-- Home Team -->
                            <div class="flex-1 text-center">
                                <div class="w-16 h-16 rounded-full bg-accent-gold/10 border border-accent-gold/20 flex items-center justify-center mx-auto mb-3 font-heading text-xl font-black text-accent-gold">
                                    {{ substr($simulationResult['my_team']->team_name, 0, 2) }}
                                </div>
                                <h4 class="font-heading text-lg font-black uppercase tracking-wider text-white">{{ $simulationResult['my_team']->team_name }}</h4>
                                <p class="text-xs text-gray-400">HOME (You)</p>
                            </div>

                            <!-- Score -->
                            <div class="text-center px-6">
                                <div class="flex items-center gap-4">
                                    <span class="text-5xl font-black font-heading text-white">{{ $simulationResult['home_score'] }}</span>
                                    <span class="text-gray-500 text-xl font-bold">:</span>
                                    <span class="text-5xl font-black font-heading text-white">{{ $simulationResult['away_score'] }}</span>
                                </div>
                                <span class="inline-block mt-2 px-3 py-1 bg-white/10 rounded-full text-[10px] uppercase font-bold tracking-widest text-accent-gold">
                                    {{ $simulationResult['strategy'] }} Setup
                                </span>
                            </div>

                            <!-- Away Team -->
                            <div class="flex-1 text-center">
                                <div class="w-16 h-16 rounded-full bg-white/10 border border-white/20 flex items-center justify-center mx-auto mb-3 font-heading text-xl font-black text-gray-300">
                                    {{ substr($simulationResult['opponent_team']->team_name, 0, 2) }}
                                </div>
                                <h4 class="font-heading text-lg font-black uppercase tracking-wider text-white">{{ $simulationResult['opponent_team']->team_name }}</h4>
                                <p class="text-xs text-gray-400">AWAY</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Comparison -->
                    <div class="glass-card p-6">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2 border-b border-white/10 pb-3">
                            <span class="text-accent-gold">2.</span> Match Statistics
                        </h3>

                        <div class="space-y-5">
                            <!-- Stat Row helper -->
                            @php
                                $statsToRender = [
                                    'possession' => ['title' => 'Possession %', 'unit' => '%'],
                                    'shots' => ['title' => 'Total Shots', 'unit' => ''],
                                    'shots_on_target' => ['title' => 'Shots on Target', 'unit' => ''],
                                    'fouls' => ['title' => 'Fouls Committed', 'unit' => ''],
                                    'saves' => ['title' => 'Goalkeeper Saves', 'unit' => ''],
                                    'cards' => ['title' => 'Cards Issued', 'unit' => ''],
                                ];
                            @endphp

                            @foreach($statsToRender as $key => $meta)
                                @php
                                    $valHome = $simulationResult['stats'][$key][0];
                                    $valAway = $simulationResult['stats'][$key][1];
                                    $sum = max(1, $valHome + $valAway);
                                    $pctHome = ($valHome / $sum) * 100;
                                @endphp
                                <div class="space-y-1">
                                    <div class="flex justify-between text-xs font-bold text-gray-300">
                                        <span>{{ $valHome }}{{ $meta['unit'] }}</span>
                                        <span class="text-gray-500 uppercase tracking-wider font-semibold">{{ $meta['title'] }}</span>
                                        <span>{{ $valAway }}{{ $meta['unit'] }}</span>
                                    </div>
                                    <div class="h-2 bg-white/5 rounded-full overflow-hidden flex">
                                        <div class="bg-accent-gold h-full transition-all" style="width: {{ $pctHome }}%"></div>
                                        <div class="bg-gray-600 h-full transition-all flex-1"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Live Play-by-Play Commentary Log -->
                    <div class="glass-card p-6">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2 border-b border-white/10 pb-3">
                            <span class="text-accent-gold">3.</span> Live-Text Commentary Play-by-Play
                        </h3>

                        <div class="space-y-4">
                            @foreach($simulationResult['events'] as $evt)
                                <div class="flex gap-4 items-start p-3 rounded-xl hover:bg-white/5 transition-all">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-heading text-sm font-black border
                                        @if($evt['type'] === 'goal') bg-green-500/10 border-green-500/20 text-green-400
                                        @elseif($evt['type'] === 'card') bg-yellow-500/10 border-yellow-500/20 text-yellow-400
                                        @elseif($evt['type'] === 'info') bg-blue-500/10 border-blue-500/20 text-blue-400
                                        @else bg-white/10 border-white/20 text-gray-300
                                        @endif">
                                        {{ $evt['minute'] }}'
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h5 class="font-bold text-sm text-white">{{ $evt['title'] }}</h5>
                                            @if($evt['type'] === 'goal')
                                                <span class="px-1.5 py-0.5 bg-green-500/20 text-green-400 text-[9px] uppercase font-bold rounded">Goal</span>
                                            @elseif($evt['type'] === 'card')
                                                <span class="px-1.5 py-0.5 bg-yellow-500/20 text-yellow-400 text-[9px] uppercase font-bold rounded">Card</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400 mt-1">{{ $evt['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="glass-card p-12 text-center flex flex-col items-center justify-center h-full min-h-[400px]">
                    <div class="w-16 h-16 bg-white/5 rounded-full flex items-center justify-center border border-white/10 mb-6">
                        <i data-lucide="play-circle" class="w-8 h-8 text-accent-gold animate-pulse"></i>
                    </div>
                    <h3 class="text-2xl font-bold font-heading uppercase tracking-wider text-white">Simulator Standby</h3>
                    <p class="text-gray-400 text-sm max-w-md mt-2">
                        Configure match settings on the left sidebar and launch the simulation to preview play-by-plays, performance commentary, and advanced match stats.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
