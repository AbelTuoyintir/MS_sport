@extends('layouts.manager')

@section('title', 'AI Match Outcome & xG Predictor Engine')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="relative rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-800 p-6 md:p-8 overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    Apex Tactical Intelligence Engine
                </div>
                <h1 class="font-head text-3xl md:text-4xl font-extrabold text-white tracking-wide uppercase">AI Match Outcome & xG Predictor</h1>
                <p class="text-slate-400 text-sm max-w-2xl mt-1">Simulate head-to-head encounters, analyze Expected Goals (xG) metrics, tactical key battles, and win probabilities.</p>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Return to Dashboard
            </a>
        </div>
    </div>

    <!-- Selection Controls -->
    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl">
        <form action="{{ route('predictor') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Home Squad</label>
                <select name="home_team_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $homeTeam?->id == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} (OVR: {{ round($team->players->avg('rating') ?? 75) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="text-center font-head text-xl font-black text-cyan-400 py-2">
                VS
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Away Squad</label>
                <select name="away_team_id" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-cyan-500 transition">
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $awayTeam?->id == $team->id ? 'selected' : '' }}>
                            {{ $team->name }} (OVR: {{ round($team->players->avg('rating') ?? 75) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-5 flex justify-end mt-2">
                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-slate-950 font-bold text-sm hover:from-cyan-400 hover:to-blue-500 transition shadow-lg shadow-cyan-500/20">
                    Run AI Tactical Simulation
                </button>
            </div>
        </form>
    </div>

    @if($homeTeam && $awayTeam && $prediction)
    <!-- Match Simulation Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Prediction Banner & xG -->
        <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl space-y-6">
            <!-- Projected Result -->
            <div class="bg-gradient-to-b from-slate-800/80 to-slate-900 rounded-xl p-6 border border-slate-700/60 text-center relative overflow-hidden">
                <div class="text-xs font-bold uppercase tracking-widest text-cyan-400 mb-2">Simulated Match Projection</div>
                <div class="flex items-center justify-center gap-6 my-4">
                    <div class="text-right">
                        <div class="font-head text-2xl font-black text-white">{{ $homeTeam->name }}</div>
                        <div class="text-xs text-slate-400">Home Strength: {{ $prediction['home_att'] }} ATT</div>
                    </div>

                    <div class="bg-slate-950 border border-cyan-500/30 px-6 py-3 rounded-2xl">
                        <span class="font-head text-4xl font-black text-cyan-400 tracking-wider">{{ $prediction['projected_score'] }}</span>
                    </div>

                    <div class="text-left">
                        <div class="font-head text-2xl font-black text-white">{{ $awayTeam->name }}</div>
                        <div class="text-xs text-slate-400">Away Strength: {{ $prediction['away_att'] }} ATT</div>
                    </div>
                </div>

                <!-- Win Probability Bar -->
                <div class="mt-6 space-y-2">
                    <div class="flex justify-between text-xs font-bold">
                        <span class="text-cyan-400">{{ $homeTeam->name }} {{ $prediction['home_win_prob'] }}%</span>
                        <span class="text-slate-400">Draw {{ $prediction['draw_prob'] }}%</span>
                        <span class="text-indigo-400">{{ $awayTeam->name }} {{ $prediction['away_win_prob'] }}%</span>
                    </div>
                    <div class="h-3 w-full bg-slate-950 rounded-full overflow-hidden flex p-0.5 border border-slate-800">
                        <div style="width: {{ $prediction['home_win_prob'] }}%" class="h-full bg-cyan-500 rounded-l-full"></div>
                        <div style="width: {{ $prediction['draw_prob'] }}%" class="h-full bg-slate-600"></div>
                        <div style="width: {{ $prediction['away_win_prob'] }}%" class="h-full bg-indigo-500 rounded-r-full"></div>
                    </div>
                </div>
            </div>

            <!-- Expected Goals (xG) Detailed Breakdown -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Expected Goals (xG)</div>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-slate-300 font-medium">{{ $homeTeam->name }}</span>
                        <span class="font-head text-2xl font-extrabold text-cyan-400">{{ $prediction['home_xg'] }}</span>
                    </div>
                    <div class="w-full bg-slate-900 rounded-full h-2 mt-2 overflow-hidden">
                        <div class="bg-cyan-500 h-full" style="width: {{ min(100, ($prediction['home_xg'] / 3.5) * 100) }}%"></div>
                    </div>
                </div>

                <div class="bg-slate-800/50 p-4 rounded-xl border border-slate-700/50">
                    <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Expected Goals (xG)</div>
                    <div class="flex items-baseline justify-between mt-2">
                        <span class="text-slate-300 font-medium">{{ $awayTeam->name }}</span>
                        <span class="font-head text-2xl font-extrabold text-indigo-400">{{ $prediction['away_xg'] }}</span>
                    </div>
                    <div class="w-full bg-slate-900 rounded-full h-2 mt-2 overflow-hidden">
                        <div class="bg-indigo-500 h-full" style="width: {{ min(100, ($prediction['away_xg'] / 3.5) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Battles & Tactics -->
        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl space-y-4">
            <h3 class="font-head text-xl font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Key Tactical Battles
            </h3>

            <div class="space-y-4">
                @foreach($prediction['key_battles'] as $battle)
                <div class="bg-slate-800/40 border border-slate-700/50 p-4 rounded-xl space-y-2">
                    <div class="text-xs font-bold text-cyan-400 uppercase tracking-wide">{{ $battle['title'] }}</div>
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-200">
                        <span>{{ $battle['home_player'] }}</span>
                        <span class="text-slate-500">VS</span>
                        <span>{{ $battle['away_player'] }}</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed pt-1 border-t border-slate-800">{{ $battle['analysis'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
