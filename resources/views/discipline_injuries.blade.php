@extends('layouts.manager')

@section('title', 'League Injury & Discipline Hub')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="relative rounded-2xl bg-gradient-to-r from-slate-900 via-rose-950 to-slate-900 border border-slate-800 p-6 md:p-8 overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-rose-400 animate-pulse"></span>
                    Medical & Disciplinary League Intelligence
                </div>
                <h1 class="font-head text-3xl md:text-4xl font-extrabold text-white tracking-wide uppercase">Injury & Discipline Hub</h1>
                <p class="text-slate-400 text-sm max-w-2xl mt-1">Track active squad injuries, expected recovery timelines, yellow/red card suspensions, and Fair Play team standings.</p>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Main Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Medical Room / Active Injuries -->
        <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <h3 class="font-head text-xl font-extrabold text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Medical Room ({{ $injuries->count() }} Reported Cases)
                </h3>
                <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-3 py-1 rounded-full border border-rose-500/20">Live Medical Feed</span>
            </div>

            @if($injuries->isEmpty())
                <div class="text-center py-12 text-slate-400 text-sm">
                    No active injuries currently logged across the league. All squad players are fit!
                </div>
            @else
                <div class="space-y-3">
                    @foreach($injuries as $injury)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-slate-800/40 hover:bg-slate-800/80 border border-slate-700/50 p-4 rounded-xl gap-4 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center font-head text-rose-400 font-bold text-lg flex-shrink-0">
                                🚑
                            </div>
                            <div>
                                <div class="text-sm font-bold text-white">{{ $injury->player?->name ?? 'Player' }}</div>
                                <div class="text-xs text-slate-400">{{ $injury->player?->team?->name ?? 'Team' }} · <span class="text-rose-400 font-semibold">{{ $injury->type }}</span></div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-xs">
                            <div class="text-right">
                                <div class="text-slate-400 font-medium">Expected Return</div>
                                <div class="font-bold text-emerald-400">{{ $injury->expected_return ? \Carbon\Carbon::parse($injury->expected_return)->format('M d, Y') : 'TBD' }}</div>
                            </div>
                            <span class="px-3 py-1 rounded-full bg-slate-900 border border-slate-700 font-bold text-rose-400 uppercase text-[10px]">
                                {{ $injury->status ?? 'Out' }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Discipline & Card Rankings -->
        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl space-y-4">
            <h3 class="font-head text-xl font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                Fair Play Standings
            </h3>

            <div class="space-y-3">
                @foreach($teamsWithCards as $team)
                <div class="flex items-center justify-between bg-slate-800/40 p-3 rounded-xl border border-slate-700/50">
                    <div>
                        <div class="text-xs font-bold text-white">{{ $team->name }}</div>
                        <div class="text-[10px] text-slate-400">Fair Play Index: <span class="text-emerald-400 font-bold">{{ $team->fair_play_score }}</span></div>
                    </div>
                    <div class="flex items-center gap-2 text-xs font-bold">
                        <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-400 border border-amber-500/20">🟨 {{ $team->total_yellows }}</span>
                        <span class="px-2 py-0.5 rounded bg-rose-500/10 text-rose-400 border border-rose-500/20">🟥 {{ $team->total_reds }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
