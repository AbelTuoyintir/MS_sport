@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Edit Game</h2>

    <div class="glass-card p-6">
        <form action="{{ route('admin.games.update', $game->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Home Team</label>
                    <select name="home_team_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->home_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Away Team</label>
                    <select name="away_team_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        @foreach($teams as $team)
                            <option value="{{ $team->id }}" {{ $game->away_team_id == $team->id ? 'selected' : '' }}>{{ $team->team_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Home Score</label>
                    <input type="number" name="home_score" value="{{ $game->home_score }}" required min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Away Score</label>
                    <input type="number" name="away_score" value="{{ $game->away_score }}" required min="0" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Kickoff</label>
                    <input type="datetime-local" name="kickoff" value="{{ $game->kickoff->format('Y-m-d\TH:i') }}" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Matchweek</label>
                    <input type="number" name="matchweek" value="{{ $game->matchweek }}" required min="1" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                    <select name="status" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="upcoming" {{ $game->status == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="live" {{ $game->status == 'live' ? 'selected' : '' }}>Live</option>
                        <option value="finished" {{ $game->status == 'finished' ? 'selected' : '' }}>Finished</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Venue</label>
                    <input type="text" name="venue" value="{{ $game->venue }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Live Minute</label>
                    <input type="text" name="live_minute" value="{{ $game->live_minute }}" placeholder="e.g. 45', HT, 90+2'" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Update Match</button>
                <a href="{{ route('admin.games.index') }}" class="flex-1 bg-white/5 text-center font-bold py-3 rounded-lg border border-white/10">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
