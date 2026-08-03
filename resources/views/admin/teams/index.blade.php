@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">Teams</h2>
        <p class="text-gray-400 text-sm">Manage all league clubs</p>
    </div>
    <a href="{{ route('admin.teams.create') }}" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">Add New Club</a>
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
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Club</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Division</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Stadium</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($teams as $team)
            <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background-color: {{ $team->primary_color }}">
                            {{ strtoupper(substr($team->team_name, 0, 2)) }}
                        </div>
                        <span class="font-semibold">{{ $team->team_name }}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ ucfirst($team->division) }}</td>
                <td class="px-6 py-4 text-sm text-gray-400">{{ $team->home_stadium ?? 'N/A' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $team->registration_status === 'approved' ? 'bg-green-900/50 text-green-400' : ($team->registration_status === 'pending' ? 'bg-yellow-900/50 text-yellow-400' : 'bg-red-900/50 text-red-400') }}">
                        {{ $team->registration_status }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.teams.edit', $team->id) }}" class="text-accent-gold hover:underline text-sm font-bold">Edit</a>
                        <form action="{{ route('admin.teams.destroy', $team->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm font-bold">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $teams->links() }}
</div>
@endsection
