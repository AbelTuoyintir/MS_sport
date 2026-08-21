@extends('layouts.manager')

@section('title', 'Custom Tactical Lineup Builder')

@section('content')
<div class="space-y-6">
    <!-- Top Header -->
    <div class="relative rounded-2xl bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 border border-slate-800 p-6 md:p-8 overflow-hidden shadow-2xl">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-semibold mb-3">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Tactical Pitch Studio
                </div>
                <h1 class="font-head text-3xl md:text-4xl font-extrabold text-white tracking-wide uppercase">Custom Tactical Lineup Builder</h1>
                <p class="text-slate-400 text-sm max-w-2xl mt-1">Design bespoke starting XIs, switch formations dynamically, and optimize squad chemistry on an interactive visual pitch.</p>
            </div>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-bold border border-slate-700 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Dashboard
            </a>
        </div>
    </div>

    <!-- Controls Bar -->
    <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl">
        <form action="{{ route('lineup-builder') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Select Team Roster</label>
                <select name="team_id" onchange="this.form.submit()" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 transition">
                    @foreach($teams as $team)
                        <option value="{{ $team->id }}" {{ $selectedTeam?->id == $team->id ? 'selected' : '' }}>
                            {{ $team->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tactical Formation</label>
                <select name="formation" onchange="this.form.submit()" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:border-emerald-500 transition">
                    @foreach(['4-3-3', '4-2-3-1', '3-5-2', '4-4-2', '5-3-2'] as $f)
                        <option value="{{ $f }}" {{ $formation == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2 flex items-center justify-around bg-slate-950/60 rounded-xl p-3 border border-slate-800">
                <div class="text-center">
                    <div class="text-xs text-slate-400 font-semibold uppercase">Squad OVR</div>
                    <div class="font-head text-2xl font-black text-emerald-400">{{ $avgRating }}</div>
                </div>
                <div class="h-8 w-px bg-slate-800"></div>
                <div class="text-center">
                    <div class="text-xs text-slate-400 font-semibold uppercase">Tactical Chemistry</div>
                    <div class="font-head text-2xl font-black text-cyan-400">{{ $chemistry }}%</div>
                </div>
            </div>
        </form>
    </div>

    <!-- Pitch Layout & Squad List -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Interactive Pitch Graphic -->
        <div class="lg:col-span-2 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-head text-xl font-extrabold text-white uppercase tracking-wider">Formation: {{ $formation }}</h3>
                <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">Tactical Pitch Graphic</span>
            </div>

            <!-- Pitch SVG Container -->
            <div class="relative w-full aspect-[4/3] bg-gradient-to-b from-emerald-950 via-emerald-900 to-emerald-950 rounded-xl border-2 border-emerald-500/30 overflow-hidden shadow-2xl flex items-center justify-center">
                <!-- Field Markings -->
                <div class="absolute inset-4 border-2 border-white/20 rounded-lg pointer-events-none"></div>
                <div class="absolute inset-x-4 top-1/2 -translate-y-1/2 h-0.5 bg-white/20 pointer-events-none"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-28 h-28 border-2 border-white/20 rounded-full pointer-events-none"></div>
                <div class="absolute top-4 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20 pointer-events-none"></div>
                <div class="absolute bottom-4 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20 pointer-events-none"></div>

                <!-- Interactive Player Nodes -->
                <div class="absolute inset-0 p-8 grid grid-rows-4 gap-2 text-center">
                    <!-- Forwards Row -->
                    <div class="flex justify-around items-center">
                        @foreach($players->where('position', 'FW')->take(3) as $p)
                        <div class="flex flex-col items-center group cursor-pointer">
                            <div class="w-10 h-10 rounded-full bg-emerald-500 border-2 border-white shadow-lg shadow-emerald-500/50 flex items-center justify-center font-head text-slate-950 font-black text-sm group-hover:scale-110 transition">
                                {{ $p->rating ?? 80 }}
                            </div>
                            <span class="mt-1 px-2 py-0.5 rounded bg-slate-950/80 text-[10px] font-bold text-white tracking-wider truncate max-w-[80px] border border-slate-800">{{ $p->name }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Midfielders Row -->
                    <div class="flex justify-around items-center">
                        @foreach($players->where('position', 'MF')->take(3) as $p)
                        <div class="flex flex-col items-center group cursor-pointer">
                            <div class="w-10 h-10 rounded-full bg-cyan-500 border-2 border-white shadow-lg shadow-cyan-500/50 flex items-center justify-center font-head text-slate-950 font-black text-sm group-hover:scale-110 transition">
                                {{ $p->rating ?? 80 }}
                            </div>
                            <span class="mt-1 px-2 py-0.5 rounded bg-slate-950/80 text-[10px] font-bold text-white tracking-wider truncate max-w-[80px] border border-slate-800">{{ $p->name }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Defenders Row -->
                    <div class="flex justify-around items-center">
                        @foreach($players->where('position', 'DF')->take(4) as $p)
                        <div class="flex flex-col items-center group cursor-pointer">
                            <div class="w-10 h-10 rounded-full bg-blue-500 border-2 border-white shadow-lg shadow-blue-500/50 flex items-center justify-center font-head text-slate-950 font-black text-sm group-hover:scale-110 transition">
                                {{ $p->rating ?? 80 }}
                            </div>
                            <span class="mt-1 px-2 py-0.5 rounded bg-slate-950/80 text-[10px] font-bold text-white tracking-wider truncate max-w-[80px] border border-slate-800">{{ $p->name }}</span>
                        </div>
                        @endforeach
                    </div>

                    <!-- Goalkeeper Row -->
                    <div class="flex justify-center items-center">
                        @php $gk = $players->where('position', 'GK')->first() ?? $players->first(); @endphp
                        @if($gk)
                        <div class="flex flex-col items-center group cursor-pointer">
                            <div class="w-10 h-10 rounded-full bg-amber-500 border-2 border-white shadow-lg shadow-amber-500/50 flex items-center justify-center font-head text-slate-950 font-black text-sm group-hover:scale-110 transition">
                                {{ $gk->rating ?? 82 }}
                            </div>
                            <span class="mt-1 px-2 py-0.5 rounded bg-slate-950/80 text-[10px] font-bold text-white tracking-wider truncate max-w-[80px] border border-slate-800">{{ $gk->name }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Available Players Sidebar -->
        <div class="bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 p-6 shadow-xl space-y-4">
            <h3 class="font-head text-xl font-bold text-white uppercase tracking-wider flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Squad Roster ({{ $players->count() }})
            </h3>

            <div class="space-y-2 max-h-[460px] overflow-y-auto pr-2 custom-scrollbar">
                @foreach($players as $player)
                <div class="flex items-center justify-between bg-slate-800/40 hover:bg-slate-800/80 border border-slate-700/50 p-3 rounded-xl transition cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="px-2 py-1 rounded bg-slate-900 text-xs font-bold text-emerald-400 border border-slate-700">{{ $player->position }}</span>
                        <div>
                            <div class="text-xs font-bold text-white">{{ $player->name }}</div>
                            <div class="text-[10px] text-slate-400">Age: {{ $player->age ?? 24 }}</div>
                        </div>
                    </div>
                    <span class="font-head text-lg font-black text-cyan-400">{{ $player->rating ?? 75 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
