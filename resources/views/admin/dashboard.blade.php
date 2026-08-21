@extends('layouts.admin')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 p-6 rounded-2xl border border-border-dark shadow-xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-gold"></span>
                <span class="text-xs font-heading font-bold uppercase tracking-widest text-gold">Season 2024/25 Overview</span>
            </div>
            <h1 class="text-3xl font-display tracking-tight text-white">Administrator Control Dashboard</h1>
            <p class="text-xs text-muted mt-1">Real-time statistics, top scorers, and management shortcuts for CAPE COAST, UCC Premier Division.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.teams.create') }}" class="px-4 py-2 bg-gold hover:bg-gold3 text-black font-heading font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg flex items-center gap-2">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Add New Team
            </a>
            <a href="{{ route('admin.games.create') }}" class="px-4 py-2 bg-bg-dark4 hover:bg-border-dark text-white border border-border-dark font-heading font-bold text-xs uppercase tracking-wider rounded-xl transition-all flex items-center gap-2">
                <i data-lucide="calendar-plus" class="w-4 h-4 text-accent"></i> Schedule Match
            </a>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Teams Card -->
        <div class="glass-card p-6 relative overflow-hidden group hover:border-gold/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-gold/10 border border-gold/20 flex items-center justify-center text-gold">
                    <i data-lucide="shield" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-muted uppercase tracking-widest bg-bg-dark4 px-2 py-1 rounded-md">Total Teams</span>
            </div>
            <div class="text-4xl font-display text-white mb-1">{{ $stats['total_teams'] }}</div>
            <div class="flex items-center gap-1 text-xs text-muted">
                <span class="text-custom-green font-bold">● Active</span>
                <span>registered clubs</span>
            </div>
        </div>

        <!-- Players Card -->
        <div class="glass-card p-6 relative overflow-hidden group hover:border-custom-green/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-custom-green/10 border border-custom-green/20 flex items-center justify-center text-custom-green">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-muted uppercase tracking-widest bg-bg-dark4 px-2 py-1 rounded-md">Squad Roster</span>
            </div>
            <div class="text-4xl font-display text-white mb-1">{{ $stats['total_players'] }}</div>
            <div class="flex items-center gap-1 text-xs text-muted">
                <span class="text-custom-green font-bold">▲ Registered</span>
                <span>league athletes</span>
            </div>
        </div>

        <!-- Matches Card -->
        <div class="glass-card p-6 relative overflow-hidden group hover:border-accent/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-accent/10 border border-accent/20 flex items-center justify-center text-accent">
                    <i data-lucide="trophy" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-muted uppercase tracking-widest bg-bg-dark4 px-2 py-1 rounded-md">Matches</span>
            </div>
            <div class="text-4xl font-display text-white mb-1">{{ $stats['total_matches'] }}</div>
            <div class="flex items-center gap-1 text-xs text-muted">
                <span class="text-accent font-bold">● Scheduled</span>
                <span>& completed games</span>
            </div>
        </div>

        <!-- Goals Card -->
        <div class="glass-card p-6 relative overflow-hidden group hover:border-custom-red/40 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-xl bg-custom-red/10 border border-custom-red/20 flex items-center justify-center text-custom-red">
                    <i data-lucide="activity" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-muted uppercase tracking-widest bg-bg-dark4 px-2 py-1 rounded-md">Goals Scored</span>
            </div>
            <div class="text-4xl font-display text-white mb-1">{{ $stats['total_goals'] }}</div>
            <div class="flex items-center gap-1 text-xs text-muted">
                <span class="text-custom-red font-bold">⚽ Total</span>
                <span>netted this season</span>
            </div>
        </div>
    </div>

    <!-- Main Content Split: Top Scorers & Operations Side Panel -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Top Scorers Table (2 cols) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="flex items-center justify-between border-b border-border-dark pb-3">
                <div class="flex items-center gap-2">
                    <i data-lucide="flame" class="w-5 h-5 text-gold"></i>
                    <h2 class="text-xl font-heading font-bold uppercase tracking-wider text-white">League Golden Boot Race</h2>
                </div>
                <a href="{{ route('admin.teams.index') }}" class="text-xs text-gold hover:underline font-bold uppercase tracking-wider">
                    Manage Roster →
                </a>
            </div>

            <div class="glass-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-border-dark text-[10px] font-heading font-bold uppercase tracking-widest text-muted bg-bg-dark3/50">
                                <th class="py-3 px-4">Player</th>
                                <th class="py-3 px-4">Team</th>
                                <th class="py-3 px-4 text-center">Position</th>
                                <th class="py-3 px-4 text-right">Goals</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-dark/50 text-xs">
                            @forelse($top_scorers as $player)
                                <tr class="hover:bg-white/5 transition-colors">
                                    <td class="py-3.5 px-4 font-bold text-text-light">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gold to-gold2 flex items-center justify-center text-black font-extrabold text-xs">
                                                {{ strtoupper(substr($player->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-white">{{ $player->name }}</div>
                                                <div class="text-[10px] text-muted">{{ $player->nationality ?? 'Ghana' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-muted">
                                        <div class="flex items-center gap-2">
                                            <div class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-black text-white" style="background-color: {{ $player->team->primary_color ?? '#f0c040' }}">
                                                {{ strtoupper(substr($player->team->team_name ?? 'NA', 0, 2)) }}
                                            </div>
                                            <span>{{ $player->team->team_name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-center font-mono text-accent">
                                        {{ $player->position ?? 'ST' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-right font-display text-2xl text-gold">
                                        {{ $player->goals }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-8 text-center text-muted">
                                        No top scorers recorded yet for Season 2024/25.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Recent Activity (1 col) -->
        <div class="space-y-6">
            <!-- Quick Management Panel -->
            <div class="glass-card p-6 space-y-4">
                <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-white border-b border-border-dark pb-2 flex items-center gap-2">
                    <i data-lucide="zap" class="w-4 h-4 text-accent"></i> Quick Administrative Tools
                </h3>
                <div class="grid grid-cols-1 gap-2.5">
                    <a href="{{ route('admin.teams.create') }}" class="p-3 bg-bg-dark3 hover:bg-bg-dark4 border border-border-dark hover:border-gold/40 rounded-xl transition-all flex items-center justify-between group no-underline">
                        <div class="flex items-center gap-3">
                            <i data-lucide="shield-plus" class="w-4 h-4 text-gold"></i>
                            <span class="text-xs font-bold text-text-light uppercase tracking-wider">Register Team</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-muted group-hover:text-gold transition-colors"></i>
                    </a>

                    <a href="{{ route('admin.games.create') }}" class="p-3 bg-bg-dark3 hover:bg-bg-dark4 border border-border-dark hover:border-accent/40 rounded-xl transition-all flex items-center justify-between group no-underline">
                        <div class="flex items-center gap-3">
                            <i data-lucide="calendar-plus" class="w-4 h-4 text-accent"></i>
                            <span class="text-xs font-bold text-text-light uppercase tracking-wider">Schedule Fixture</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-muted group-hover:text-accent transition-colors"></i>
                    </a>

                    <a href="{{ route('admin.articles.create') }}" class="p-3 bg-bg-dark3 hover:bg-bg-dark4 border border-border-dark hover:border-custom-green/40 rounded-xl transition-all flex items-center justify-between group no-underline">
                        <div class="flex items-center gap-3">
                            <i data-lucide="newspaper" class="w-4 h-4 text-custom-green"></i>
                            <span class="text-xs font-bold text-text-light uppercase tracking-wider">Publish League News</span>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-muted group-hover:text-custom-green transition-colors"></i>
                    </a>
                </div>
            </div>

            <!-- Recent System Activity -->
            <div class="glass-card p-6 space-y-4">
                <h3 class="text-sm font-heading font-bold uppercase tracking-wider text-white border-b border-border-dark pb-2 flex items-center justify-between">
                    <span class="flex items-center gap-2"><i data-lucide="clock" class="w-4 h-4 text-gold"></i> System Feed</span>
                    <span class="text-[10px] text-muted lowercase">live log</span>
                </h3>

                <div class="space-y-3">
                    @forelse($recent_activities as $activity)
                        <div class="flex items-start gap-3 text-xs">
                            <div class="w-2 h-2 rounded-full mt-1.5 flex-shrink-0" style="background-color: {{ $activity->color }}"></div>
                            <div>
                                <p class="text-text-light font-medium">{!! $activity->message !!}</p>
                                <p class="text-[10px] text-muted mt-0.5">{{ $activity->time }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-muted text-center py-4">No recent activities logged.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
