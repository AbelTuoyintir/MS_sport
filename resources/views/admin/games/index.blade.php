@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 p-6 rounded-2xl border border-border-dark shadow-xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-accent"></span>
                <span class="text-xs font-heading font-bold uppercase tracking-widest text-accent">Fixture Schedule</span>
            </div>
            <h1 class="text-3xl font-display tracking-tight text-white">League Matches Management</h1>
            <p class="text-xs text-muted mt-1">Schedule games, update scores, track live status, and manage match events.</p>
        </div>
        <a href="{{ route('admin.games.create') }}" class="px-5 py-2.5 bg-accent hover:bg-cyan-300 text-black font-heading font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
            <i data-lucide="calendar-plus" class="w-4 h-4"></i> Schedule New Game
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green text-xs font-semibold rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-custom-green flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Games Table Card -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-dark text-[10px] font-heading font-bold uppercase tracking-widest text-muted bg-bg-dark3/60">
                        <th class="py-3.5 px-6">Matchweek</th>
                        <th class="py-3.5 px-6">Kickoff Time</th>
                        <th class="py-3.5 px-6">Matchup</th>
                        <th class="py-3.5 px-6 text-center">Score</th>
                        <th class="py-3.5 px-6 text-center">Status</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark/50 text-xs">
                    @forelse($games as $game)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 font-mono font-bold text-gold">
                                MW {{ $game->matchweek }}
                            </td>
                            <td class="py-4 px-6 text-muted">
                                {{ optional($game->kickoff)->format('M d, Y · H:i') ?? 'TBD' }}
                            </td>
                            <td class="py-4 px-6 font-bold text-text-light">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1.5 min-w-[120px] justify-end">
                                        <span class="text-white text-xs">{{ $game->homeTeam->team_name ?? 'Home' }}</span>
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center text-[8px] font-black text-white" style="background-color: {{ $game->homeTeam->primary_color ?? '#f0c040' }}">
                                            {{ strtoupper(substr($game->homeTeam->team_name ?? 'H', 0, 2)) }}
                                        </div>
                                    </div>
                                    <span class="text-muted font-heading text-xs uppercase px-1">VS</span>
                                    <div class="flex items-center gap-1.5 min-w-[120px]">
                                        <div class="w-5 h-5 rounded-full flex items-center justify-center text-[8px] font-black text-white" style="background-color: {{ $game->awayTeam->primary_color ?? '#00e5ff' }}">
                                            {{ strtoupper(substr($game->awayTeam->team_name ?? 'A', 0, 2)) }}
                                        </div>
                                        <span class="text-white text-xs">{{ $game->awayTeam->team_name ?? 'Away' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center font-display text-xl text-white">
                                {{ $game->status === 'upcoming' ? 'v' : "{$game->home_score} – {$game->away_score}" }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $st = strtolower($game->status ?? 'upcoming');
                                    $badge = $st === 'live' ? 'bg-custom-red/20 text-custom-red border-custom-red/40 animate-pulse' : ($st === 'finished' ? 'bg-bg-dark4 text-muted border-border-dark' : 'bg-accent/15 text-accent border-accent/30');
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider border {{ $badge }}">
                                    {{ $game->status }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.games.events', $game->id) }}" class="px-2.5 py-1.5 bg-bg-dark3 hover:bg-accent hover:text-black border border-border-dark text-accent text-[11px] font-heading font-bold uppercase tracking-wider rounded-lg transition-all flex items-center gap-1" title="Match Events">
                                        <i data-lucide="zap" class="w-3.5 h-3.5"></i> Events
                                    </a>
                                    <a href="{{ route('admin.games.edit', $game->id) }}" class="p-2 bg-bg-dark3 hover:bg-gold hover:text-black border border-border-dark text-muted rounded-lg transition-all" title="Edit Game">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this game?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-bg-dark3 hover:bg-custom-red/20 hover:text-custom-red border border-border-dark text-muted rounded-lg transition-all" title="Delete Game">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-muted">
                                No fixtures scheduled yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($games, 'links'))
        <div class="mt-6">
            {{ $games->links() }}
        </div>
    @endif
</div>
@endsection
