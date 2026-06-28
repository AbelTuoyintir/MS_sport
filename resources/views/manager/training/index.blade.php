@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Training Management</h2>
        <button onclick="toggleModal('add-training-modal')" class="bg-accent-gold text-bg-dark px-4 py-2 rounded-lg font-bold text-sm">+ Schedule Session</button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($sessions as $session)
            <div class="glass-card p-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="text-xs text-gray-500 font-bold uppercase mb-1">{{ $session->scheduled_at->format('M d, Y') }}</div>
                        <div class="text-xl font-bold text-accent-gold">{{ $session->scheduled_at->format('H:i') }}</div>
                    </div>
                    <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $session->status === 'completed' ? 'bg-green-900/50 text-green-400' : 'bg-blue-900/50 text-blue-400' }}">
                        {{ $session->status }}
                    </span>
                </div>
                <div class="mb-4">
                    <div class="text-xs text-gray-400 font-bold uppercase">Focus</div>
                    <div class="font-bold">{{ $session->focus }}</div>
                </div>
                <div class="mb-4 text-sm text-gray-400">
                    <i data-lucide="map-pin" class="w-3 h-3 inline mr-1"></i> {{ $session->location ?? 'Main Ground' }}
                </div>
                <p class="text-xs text-gray-500 italic">{{ $session->plan }}</p>
            </div>
        @empty
            <p class="text-gray-500 col-span-full text-center py-12">No training sessions scheduled.</p>
        @endforelse
    </div>
</div>

<!-- Add Training Modal -->
<div id="add-training-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">Schedule Training</h3>
        <form action="{{ route('manager.training.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Date & Time</label>
                    <input type="datetime-local" name="scheduled_at" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Focus</label>
                    <select name="focus" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="Tactical">Tactical</option>
                        <option value="Physical">Physical</option>
                        <option value="Technical">Technical</option>
                        <option value="Recovery">Recovery</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Location</label>
                <input type="text" name="location" placeholder="e.g. Field 1" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Session Plan</label>
                <textarea name="plan" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none"></textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Schedule</button>
                <button type="button" onclick="toggleModal('add-training-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endsection
