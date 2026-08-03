@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.teams.index') }}" class="text-accent-gold hover:underline text-sm">← Back to Teams</a>
        <h2 class="text-3xl font-bold mt-2">Edit Club: {{ $team->team_name }}</h2>
    </div>

    <div class="glass-card p-8">
        <form action="{{ route('admin.teams.update', $team->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Team Name</label>
                    <input type="text" name="team_name" value="{{ old('team_name', $team->team_name) }}" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Division</label>
                    <select name="division" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="premier" {{ $team->division === 'premier' ? 'selected' : '' }}>Premier Division</option>
                        <option value="division1" {{ $team->division === 'division1' ? 'selected' : '' }}>Division 1</option>
                        <option value="division2" {{ $team->division === 'division2' ? 'selected' : '' }}>Division 2</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Primary Color</label>
                    <input type="color" name="primary_color" value="{{ old('primary_color', $team->primary_color) }}" class="w-full h-10 bg-white/5 border border-white/10 rounded-lg px-1 py-1 outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Secondary Color</label>
                    <input type="color" name="secondary_color" value="{{ old('secondary_color', $team->secondary_color) }}" class="w-full h-10 bg-white/5 border border-white/10 rounded-lg px-1 py-1 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Squad Size</label>
                    <input type="number" name="team_size" value="{{ old('team_size', $team->team_size) }}" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Registration Status</label>
                    <select name="registration_status" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="pending" {{ $team->registration_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $team->registration_status === 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ $team->registration_status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Home Stadium</label>
                    <input type="text" name="home_stadium" value="{{ old('home_stadium', $team->home_stadium) }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $team->city) }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <button type="submit" class="w-full bg-accent-gold text-bg-dark font-bold py-3 rounded-lg mt-4 uppercase tracking-widest">Update Team</button>
        </form>
    </div>
</div>
@endsection
