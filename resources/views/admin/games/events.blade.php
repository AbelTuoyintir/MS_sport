@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Match Events: {{ $game->homeTeam->team_name }} vs {{ $game->awayTeam->team_name }}</h2>
        <a href="{{ route('admin.games.index') }}" class="text-accent-gold hover:underline">← Back to Fixtures</a>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-1">
            <div class="glass-card p-6">
                <h3 class="text-lg font-bold mb-6">Add Event</h3>
                <form action="{{ route('admin.games.events.store', $game->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Team</label>
                        <select name="team_id" id="team_select" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                            <option value="{{ $game->home_team_id }}">{{ $game->homeTeam->team_name }} (Home)</option>
                            <option value="{{ $game->away_team_id }}">{{ $game->awayTeam->team_name }} (Away)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player</label>
                        <select name="player_id" id="player_select" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                            <!-- Populated via JS based on team -->
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Type</label>
                            <select name="type" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                                <option value="goal">Goal</option>
                                <option value="assist">Assist</option>
                                <option value="yellow_card">Yellow Card</option>
                                <option value="red_card">Red Card</option>
                                <option value="own_goal">Own Goal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Minute</label>
                            <input type="text" name="minute" required placeholder="e.g. 23'" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mt-4">Record Event</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="glass-card overflow-hidden">
                <div class="px-6 py-4 border-b border-white/10 bg-white/5">
                    <h3 class="font-bold">Match Timeline</h3>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-xs text-gray-500 uppercase font-bold border-b border-white/10">
                            <th class="px-6 py-3">Minute</th>
                            <th class="px-6 py-3">Event</th>
                            <th class="px-6 py-3">Player</th>
                            <th class="px-6 py-3">Team</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($game->events->sortBy('minute') as $event)
                            <tr class="border-b border-white/5">
                                <td class="px-6 py-4 font-black text-accent-gold">{{ $event->minute }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold uppercase">{{ str_replace('_', ' ', $event->type) }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $event->player->name }}</td>
                                <td class="px-6 py-4 text-xs text-gray-400">{{ $event->team->team_name }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-8 text-center text-gray-500">No events recorded.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const homePlayers = @json($game->homeTeam->players);
const awayPlayers = @json($game->awayTeam->players);
const homeTeamId = {{ $game->home_team_id }};

function updatePlayers() {
    const teamId = document.getElementById('team_select').value;
    const players = teamId == homeTeamId ? homePlayers : awayPlayers;
    const playerSelect = document.getElementById('player_select');

    playerSelect.innerHTML = '';
    players.forEach(p => {
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.name;
        playerSelect.appendChild(opt);
    });
}

document.getElementById('team_select').addEventListener('change', updatePlayers);
updatePlayers();
</script>
@endsection
