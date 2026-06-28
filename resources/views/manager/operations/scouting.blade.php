@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Scouting Reports</h2>
        <button onclick="toggleModal('add-scout-modal')" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">+ Add Report</button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($reports as $report)
            <div class="glass-card p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-xs text-accent-gold font-bold uppercase mb-1">{{ $report->position }} · {{ $report->age }} yrs</div>
                        <h3 class="text-xl font-bold">{{ $report->player_name }}</h3>
                        <div class="text-xs text-gray-500 font-bold uppercase">{{ $report->current_club ?? 'Free Agent' }}</div>
                    </div>
                    <div class="flex gap-0.5">
                        @for($i=1; $i<=5; $i++)
                            <i data-lucide="star" class="w-3 h-3 {{ $i <= $report->rating ? 'text-accent-gold fill-accent-gold' : 'text-gray-700' }}"></i>
                        @endfor
                    </div>
                </div>

                <div class="space-y-3 mb-6">
                    <div>
                        <div class="text-[10px] text-green-500 font-bold uppercase">Strengths</div>
                        <p class="text-xs text-gray-300">{{ $report->strengths }}</p>
                    </div>
                    <div>
                        <div class="text-[10px] text-red-500 font-bold uppercase">Weaknesses</div>
                        <p class="text-xs text-gray-300">{{ $report->weaknesses }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-white/5">
                    <span class="text-[10px] font-bold uppercase px-2 py-1 rounded bg-blue-900/30 text-blue-400">{{ $report->status }}</span>
                    <button class="text-xs font-bold text-gray-400 hover:text-white uppercase">View Summary</button>
                </div>
            </div>
        @empty
            <p class="text-gray-500 col-span-full text-center py-12">No scouting reports found.</p>
        @endforelse
    </div>
</div>
<!-- Add Scout Modal -->
<div id="add-scout-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">New Scout Report</h3>
        <form action="{{ route('manager.scouting.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player Name</label>
                <input type="text" name="player_name" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Position</label>
                    <input type="text" name="position" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Age</label>
                    <input type="number" name="age" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rating (1-5)</label>
                    <select name="rating" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Status</label>
                    <select name="status" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="shortlisted">Shortlisted</option>
                        <option value="trial">Trial</option>
                        <option value="monitoring">Monitoring</option>
                        <option value="ignored">Ignored</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Save Report</button>
                <button type="button" onclick="toggleModal('add-scout-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
