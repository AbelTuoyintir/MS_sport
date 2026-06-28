@extends('layouts.manager')
@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="glass-card p-6">
            <h2 class="text-2xl font-bold mb-4">Team: {{ auth()->user()->team->team_name ?? 'N/A' }}</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white/5 p-4 rounded-xl">
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-wider">Division</p>
                    <p class="text-xl font-bold">{{ ucfirst(auth()->user()->team->division ?? 'N/A') }}</p>
                </div>
                <div class="bg-white/5 p-4 rounded-xl">
                    <p class="text-gray-400 text-xs uppercase font-bold tracking-wider">Registration Status</p>
                    <p class="text-xl font-bold text-yellow-500">{{ ucfirst(auth()->user()->team->registration_status ?? 'N/A') }}</p>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold">My Squad</h3>
                <button onclick="toggleModal('add-player-modal')" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">+ Add Player</button>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @forelse(auth()->user()->team->players ?? [] as $player)
                    <div class="bg-white/5 p-4 rounded-xl flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-gray-700 flex items-center justify-center font-bold">{{ $player->rating }}</div>
                            <div>
                                <p class="font-bold">{{ $player->name }}</p>
                                <p class="text-gray-400 text-xs">{{ $player->position }} @if($player->number) #{{ $player->number }} @endif</p>
                            </div>
                        </div>
                        <form action="{{ route('manager.players.destroy', $player->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-400 p-2">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-gray-500 text-center col-span-2 py-8">No players registered yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">Quick Stats</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Total Goals</span>
                    <span class="font-bold">{{ number_format(optional(auth()->user()->team)->players?->avg('rating') ?? 0, 1) }}</span>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Avg Rating</span>
                    <span class="font-bold">{{ number_format(optional(auth()->user()->team)->players?->avg('rating') ?? 0, 1) }}</span>
                </div>
            </div>
        </div>

        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">Upcoming Fixtures</h3>
            <div class="space-y-4">
                @forelse($upcoming_games as $game)
                    <div class="bg-white/5 p-3 rounded-lg border border-white/5">
                        <div class="text-[10px] text-gray-500 font-bold uppercase mb-1">Matchweek {{ $game->matchweek }}</div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="{{ $game->home_team_id == auth()->user()->team_id ? 'text-accent-gold font-bold' : '' }}">{{ $game->homeTeam->team_name }}</span>
                            <span class="text-gray-600">vs</span>
                            <span class="{{ $game->away_team_id == auth()->user()->team_id ? 'text-accent-gold font-bold' : '' }}">{{ $game->awayTeam->team_name }}</span>
                        </div>
                        <div class="text-[10px] text-gray-400 mt-2">{{ $game->kickoff->format('M d, H:i') }}</div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No upcoming games scheduled.</p>
                @endforelse
            </div>
        </div>

        <div class="glass-card p-6">
            <h3 class="text-lg font-bold mb-4">Recent Results</h3>
            <div class="space-y-4">
                @forelse($recent_results as $result)
                    <div class="bg-white/5 p-3 rounded-lg border border-white/5">
                        <div class="flex justify-between items-center text-sm mb-1">
                            <span class="{{ $result->home_team_id == auth()->user()->team_id ? 'font-bold' : '' }}">{{ $result->homeTeam->team_name }}</span>
                            <span class="font-black text-accent-gold px-2">{{ $result->home_score }} - {{ $result->away_score }}</span>
                            <span class="{{ $result->away_team_id == auth()->user()->team_id ? 'font-bold' : '' }}">{{ $result->awayTeam->team_name }}</span>
                        </div>
                        <div class="text-[10px] text-gray-500 text-center uppercase">{{ $result->kickoff->format('M d, Y') }}</div>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No recent results.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Player Modal -->
<div id="add-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold">Add New Player</h3>
            <button onclick="toggleModal('add-player-modal')" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>
        <form action="{{ route('manager.players.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player Name</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Position</label>
                    <select name="position" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="GK">GK</option>
                        <option value="DEF">DEF</option>
                        <option value="MID">MID</option>
                        <option value="FWD">FWD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Squad Number</label>
                    <input type="number" name="number" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <button type="submit" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mt-4">Save Player</button>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}
</script>
@endsection
