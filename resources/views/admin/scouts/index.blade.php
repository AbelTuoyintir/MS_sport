@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-card p-6">
        <div>
            <h1 class="text-2xl font-black font-heading tracking-wider text-text-light uppercase flex items-center gap-2">
                <i data-lucide="user-search" class="w-7 h-7 text-gold"></i>
                Scouting Agent Registry
            </h1>
            <p class="text-xs text-muted mt-1">Register and manage certified league scouting agents available for hire by team managers.</p>
        </div>
        <button onclick="document.getElementById('add-scout-modal').classList.remove('hidden')" class="px-4 py-2.5 bg-gradient-to-r from-gold to-gold2 text-black font-heading font-black uppercase text-xs tracking-wider rounded-xl shadow-lg shadow-gold/20 hover:scale-105 transition-all flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Register New Scout
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Agents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($agents as $agent)
            <div class="glass-card p-6 relative overflow-hidden group hover:border-gold/40 transition-all">
                <div class="flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold/20 to-accent/20 border border-gold/30 flex items-center justify-center text-gold font-bold">
                            <i data-lucide="award" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-extrabold text-lg text-text-light uppercase tracking-wider">{{ $agent->name }}</h3>
                            <span class="text-[10px] font-bold uppercase tracking-widest text-accent bg-accent/10 px-2 py-0.5 rounded">
                                {{ $agent->specialization }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-border-dark grid grid-cols-2 gap-3 text-xs">
                    <div>
                        <span class="text-muted block text-[10px] uppercase font-bold tracking-wider">Experience Rating</span>
                        <div class="flex items-center gap-1 text-gold mt-0.5">
                            @for($i=1; $i<=5; $i++)
                                <i data-lucide="star" class="w-3.5 h-3.5 {{ $i <= $agent->experience_rating ? 'fill-gold text-gold' : 'text-muted' }}"></i>
                            @endfor
                            <span class="text-xs text-text-light font-mono font-bold ml-1">({{ $agent->experience_rating }}/5)</span>
                        </div>
                    </div>
                    <div>
                        <span class="text-muted block text-[10px] uppercase font-bold tracking-wider">Weekly Retainer</span>
                        <span class="text-custom-green font-mono font-bold text-sm">GH₵ {{ number_format($agent->weekly_fee, 2) }}</span>
                    </div>
                    <div>
                        <span class="text-muted block text-[10px] uppercase font-bold tracking-wider">Assigned Teams</span>
                        <span class="text-text-light font-bold">{{ $agent->teams_count }} Teams</span>
                    </div>
                    <div>
                        <span class="text-muted block text-[10px] uppercase font-bold tracking-wider">Status</span>
                        <span class="text-custom-green font-bold text-[10px] uppercase bg-custom-green/10 px-2 py-0.5 rounded">Certified</span>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-border-dark flex justify-end">
                    <form action="{{ route('admin.scouts.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('Remove this scouting agent?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-custom-red hover:underline flex items-center gap-1 font-bold">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Deregister
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full glass-card p-12 text-center text-muted">
                <i data-lucide="user-x" class="w-12 h-12 mx-auto mb-3 text-border-dark2"></i>
                <p class="font-heading uppercase tracking-wider text-base">No scouting agents registered yet.</p>
                <p class="text-xs text-muted mt-1">Register certified agents above for team managers to hire.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Modal: Add Agent -->
<div id="add-scout-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="glass-card max-w-lg w-full p-6 border border-gold/30">
        <div class="flex items-center justify-between border-b border-border-dark pb-4 mb-4">
            <h3 class="font-heading font-black text-xl text-gold uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5"></i> Register Scouting Agent
            </h3>
            <button onclick="document.getElementById('add-scout-modal').classList.add('hidden')" class="text-muted hover:text-text-light">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('admin.scouts.store') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-muted uppercase font-bold mb-1">Agent Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Kwame Mensah" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-text-light focus:border-gold outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-muted uppercase font-bold mb-1">Specialization</label>
                    <select name="specialization" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-3 py-2 text-text-light focus:border-gold outline-none">
                        <option value="Youth & Academy">Youth & Academy</option>
                        <option value="Attacking Talent">Attacking Talent</option>
                        <option value="Defensive Tacticians">Defensive Tacticians</option>
                        <option value="International / Global">International / Global</option>
                        <option value="Bargain Hunters">Bargain Hunters</option>
                    </select>
                </div>
                <div>
                    <label class="block text-muted uppercase font-bold mb-1">Experience Rating (1-5)</label>
                    <input type="number" name="experience_rating" min="1" max="5" value="4" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-text-light focus:border-gold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-muted uppercase font-bold mb-1">Weekly Retainer Fee (GH₵)</label>
                <input type="number" step="0.01" name="weekly_fee" value="750.00" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2 text-text-light focus:border-gold outline-none">
            </div>

            <div class="pt-4 border-t border-border-dark flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('add-scout-modal').classList.add('hidden')" class="px-4 py-2 bg-bg-dark3 border border-border-dark rounded-xl text-muted font-bold uppercase hover:bg-white/5">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-gold text-black rounded-xl font-heading font-black uppercase tracking-wider hover:bg-gold2 shadow-lg shadow-gold/20">
                    Register Agent
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
