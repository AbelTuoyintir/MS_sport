@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 p-6 rounded-2xl border border-border-dark shadow-xl">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-gold"></span>
                <span class="text-xs font-heading font-bold uppercase tracking-widest text-gold">Club Directory</span>
            </div>
            <h1 class="text-3xl font-display tracking-tight text-white">Registered Teams Management</h1>
            <p class="text-xs text-muted mt-1">Manage, approve, edit, and inspect active clubs in the CAPE COAST, UCC Premier League.</p>
        </div>
        <a href="{{ route('admin.teams.create') }}" class="px-5 py-2.5 bg-gold hover:bg-gold3 text-black font-heading font-bold text-xs uppercase tracking-wider rounded-xl transition-all shadow-lg flex items-center justify-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Add New Club
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green text-xs font-semibold rounded-xl flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-custom-green flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <!-- Teams Table Card -->
    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-dark text-[10px] font-heading font-bold uppercase tracking-widest text-muted bg-bg-dark3/60">
                        <th class="py-3.5 px-6">Club Name</th>
                        <th class="py-3.5 px-6">Division</th>
                        <th class="py-3.5 px-6">Home Stadium</th>
                        <th class="py-3.5 px-6 text-center">Registration</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark/50 text-xs">
                    @forelse($teams as $team)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 font-bold text-text-light">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-extrabold text-xs shadow-md" style="background-color: {{ $team->primary_color ?? '#f0c040' }}">
                                        {{ strtoupper(substr($team->team_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white text-sm">{{ $team->team_name }}</div>
                                        <div class="text-[10px] text-muted">{{ $team->email ?? 'team@mpleague.com' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-muted font-medium uppercase text-[11px]">
                                {{ ucfirst($team->division ?? 'Premier') }}
                            </td>
                            <td class="py-4 px-6 text-muted">
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-accent"></i>
                                    <span>{{ $team->home_stadium ?? 'Central Sports Ground' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @php
                                    $status = strtolower($team->registration_status ?? 'approved');
                                    $badgeStyle = $status === 'approved' ? 'bg-custom-green/15 text-custom-green border-custom-green/30' : ($status === 'pending' ? 'bg-gold/15 text-gold border-gold/30' : 'bg-custom-red/15 text-custom-red border-custom-red/30');
                                @endphp
                                <span class="px-2.5 py-1 rounded-md text-[10px] font-heading font-bold uppercase tracking-wider border {{ $badgeStyle }}">
                                    {{ $team->registration_status ?? 'approved' }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.teams.edit', $team->id) }}" class="p-2 bg-bg-dark3 hover:bg-gold hover:text-black border border-border-dark text-muted rounded-lg transition-all" title="Edit Club">
                                        <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $team->team_name }}?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 bg-bg-dark3 hover:bg-custom-red/20 hover:text-custom-red border border-border-dark text-muted rounded-lg transition-all" title="Delete Club">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center text-muted">
                                No teams registered in the database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($teams, 'links'))
        <div class="mt-6">
            {{ $teams->links() }}
        </div>
    @endif
</div>
@endsection
