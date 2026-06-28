@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Games / Fixtures</h2>
    <a href="{{ route('admin.games.create') }}" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">Schedule New Game</a>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
        {{ session('success') }}
    </div>
@endif

<div class="glass-card overflow-hidden">
    <table class="w-full text-left">
        <thead>
            <tr class="border-b border-white/10 bg-white/5">
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Matchweek</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Kickoff</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Match</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Score</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($games as $game)
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                <td class="px-6 py-4">{{ $game->matchweek }}</td>
                <td class="px-6 py-4 text-sm">{{ $game->kickoff->format('M d, Y H:i') }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <span>{{ $game->homeTeam->team_name }}</span>
                        <span class="text-gray-500 text-xs">vs</span>
                        <span>{{ $game->awayTeam->team_name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 font-bold">{{ $game->home_score }} - {{ $game->away_score }}</td>
                <td class="px-6 py-4 text-xs font-bold uppercase">
                    <span class="px-2 py-1 rounded {{ $game->status === 'live' ? 'bg-red-900/50 text-red-400' : ($game->status === 'finished' ? 'bg-gray-700 text-gray-300' : 'bg-blue-900/50 text-blue-400') }}">
                        {{ $game->status }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.games.edit', $game->id) }}" class="text-accent-gold hover:underline text-sm">Edit</a>
                        <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $games->links() }}
</div>
@endsection
