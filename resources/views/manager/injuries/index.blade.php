@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Injury Tracking</h2>
        <button onclick="toggleModal('add-injury-modal')" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold text-sm">+ Record Injury</button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-white/10 bg-white/5">
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Player</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Injury Type</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Severity</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Started</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Expected Return</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($injuries as $injury)
                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4 font-bold">{{ $injury->player->name }}</td>
                        <td class="px-6 py-4">{{ $injury->type }}</td>
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-bold uppercase {{ $injury->severity === 'severe' ? 'text-red-500' : ($injury->severity === 'moderate' ? 'text-orange-400' : 'text-yellow-400') }}">
                                {{ $injury->severity }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $injury->started_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-400">{{ $injury->expected_return_at ? $injury->expected_return_at->format('M d, Y') : '-' }}</td>
                        <td class="px-6 py-4">
                            @if($injury->returned_at)
                                <span class="text-xs text-green-400 font-bold uppercase">Returned</span>
                            @else
                                <span class="text-xs text-red-400 font-bold uppercase">Out</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">No injury records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Injury Modal -->
<div id="add-injury-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">Record New Injury</h3>
        <form action="{{ route('manager.injuries.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player</label>
                <select name="player_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    @foreach($players as $player)
                        <option value="{{ $player->id }}">{{ $player->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Injury Type</label>
                <input type="text" name="type" required placeholder="e.g. Hamstring Tear" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Severity</label>
                    <select name="severity" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="minor">Minor</option>
                        <option value="moderate">Moderate</option>
                        <option value="severe">Severe</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Start Date</label>
                    <input type="date" name="started_at" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Expected Return</label>
                <input type="date" name="expected_return_at" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-red-600 text-white font-bold py-3 rounded-lg">Record Injury</button>
                <button type="button" onclick="toggleModal('add-injury-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
