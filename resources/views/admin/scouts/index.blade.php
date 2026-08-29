@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-heading font-black tracking-wide uppercase text-text-light flex items-center gap-3">
                <div class="w-10 h-10 bg-gold/10 text-gold rounded-xl flex items-center justify-center border border-gold/20">
                    <i data-lucide="user-search" class="w-5 h-5"></i>
                </div>
                Scouting Agent Registry
            </h1>
            <p class="text-xs text-muted mt-1">Register and manage certified scouting agents available for hire by club managers.</p>
        </div>
        <button onclick="document.getElementById('add-scout-modal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-gold to-gold2 text-black font-heading font-bold text-sm uppercase tracking-wider hover:brightness-110 transition-all flex items-center gap-2 shadow-lg shadow-gold/10 self-start sm:self-auto">
            <i data-lucide="plus-circle" class="w-4 h-4"></i>
            Register New Agent
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green rounded-xl text-xs font-bold flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Agent Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($scouts as $scout)
            <div class="glass-card p-5 relative overflow-hidden group">
                <div class="flex items-start justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-bg-dark3 border border-border-dark flex items-center justify-center text-xl">
                            {{ $scout->nationality }}
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-base text-text-light group-hover:text-gold transition-colors">{{ $scout->name }}</h3>
                            <span class="text-[10px] font-mono text-accent uppercase tracking-wider block">{{ $scout->specialization }}</span>
                        </div>
                    </div>
                    <span class="px-2.5 py-1 rounded-full text-[10px] font-heading font-bold uppercase tracking-wider {{ $scout->status === 'available' ? 'bg-custom-green/20 text-custom-green border border-custom-green/30' : 'bg-gold/20 text-gold border border-gold/30' }}">
                        {{ $scout->status === 'available' ? 'Available' : 'Hired' }}
                    </span>
                </div>

                <div class="space-y-2 py-3 border-y border-border-dark text-xs">
                    <div class="flex justify-between items-center text-muted">
                        <span>Scouting Rating</span>
                        <span class="font-bold text-gold font-mono">{{ $scout->experience_rating }} / 99</span>
                    </div>
                    <div class="flex justify-between items-center text-muted">
                        <span>Weekly Retainer Fee</span>
                        <span class="font-bold text-text-light font-mono">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center text-muted">
                        <span>Assigned Club</span>
                        <span class="font-bold text-accent">{{ $scout->team ? $scout->team->team_name : 'Unassigned' }}</span>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <form action="{{ route('admin.scouts.destroy', $scout->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unregister this scout agent?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-1.5 rounded-lg bg-custom-red/10 border border-custom-red/20 text-custom-red hover:bg-custom-red/20 text-xs font-heading font-bold uppercase tracking-wider transition-colors flex items-center gap-1.5">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Remove Agent
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-muted glass-card">
                <i data-lucide="user-x" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                <p class="text-xs">No scouting agents registered yet in the system.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal -->
<div id="add-scout-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 bg-bg-dark2 border border-border-dark relative">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-heading font-bold uppercase text-text-light flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-gold"></i> Register Scout Agent
            </h3>
            <button onclick="document.getElementById('add-scout-modal').classList.add('hidden')" class="text-muted hover:text-text-light">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.scouts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-heading font-bold uppercase text-muted mb-1">Agent Name</label>
                <input type="text" name="name" required placeholder="e.g. Kwame Mensah" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-xs text-text-light focus:border-gold outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-heading font-bold uppercase text-muted mb-1">Specialization</label>
                <select name="specialization" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-xs text-text-light focus:border-gold outline-none">
                    <option value="Youth Prodigy Scout">Youth Prodigy Scout</option>
                    <option value="Tactical Specialist">Tactical Specialist</option>
                    <option value="Global Talent Finder">Global Talent Finder</option>
                    <option value="Defense Specialist">Defense Specialist</option>
                    <option value="Forward Striker Specialist">Forward Striker Specialist</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-heading font-bold uppercase text-muted mb-1">Rating (50-99)</label>
                    <input type="number" name="experience_rating" min="50" max="99" value="82" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-xs text-text-light focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-heading font-bold uppercase text-muted mb-1">Weekly Fee (GH₵)</label>
                    <input type="number" name="weekly_fee" value="2500" min="100" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-xs text-text-light focus:border-gold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-heading font-bold uppercase text-muted mb-1">Nationality Flag Emoji</label>
                <input type="text" name="nationality" value="🇬🇭" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-xs text-text-light focus:border-gold outline-none">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 py-3 rounded-xl bg-gold text-black font-heading font-bold text-xs uppercase tracking-wider hover:brightness-110 transition-all">
                    Register Agent
                </button>
                <button type="button" onclick="document.getElementById('add-scout-modal').classList.add('hidden')" class="flex-1 py-3 rounded-xl bg-bg-dark3 border border-border-dark text-muted hover:text-text-light font-heading font-bold text-xs uppercase tracking-wider">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
