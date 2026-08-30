@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 glass-card p-6 border-l-4 border-l-gold">
        <div>
            <h1 class="text-2xl font-black font-heading tracking-wider text-white uppercase flex items-center gap-2">
                <i data-lucide="user-search" class="w-7 h-7 text-accent-gold"></i>
                Manager Scouting & Talent Submission Hub
            </h1>
            <p class="text-xs text-gray-400 mt-1">Hire certified scouting agents, discover prospects, and submit new players directly to your squad or the transfer market.</p>
        </div>
        <button onclick="toggleModal('discover-player-modal')" class="px-4 py-2.5 bg-accent-gold text-black font-heading font-black uppercase text-xs tracking-wider rounded-xl shadow-lg shadow-gold/20 hover:scale-105 transition-all flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4"></i> Submit Scouted Player
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-400 text-xs font-bold rounded-xl flex items-center gap-2">
            <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Section 1: Signed Scouting Agents -->
    <div class="glass-card p-6">
        <h2 class="font-heading font-bold text-lg text-white uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="shield-check" class="w-5 h-5 text-accent-gold"></i> Your Signed Scouting Network
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($myAgents as $agent)
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-heading font-extrabold text-base text-white uppercase">{{ $agent->name }}</h3>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded">Active</span>
                        </div>
                        <p class="text-xs text-accent-gold mt-1 font-semibold">{{ $agent->specialization }}</p>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-400 border-t border-white/5 pt-2">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-500">Rating</span>
                                <div class="flex text-amber-400">
                                    @for($i=1; $i<=5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= $agent->experience_rating ? 'fill-amber-400' : 'text-gray-600' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-500">Weekly Fee</span>
                                <span class="text-emerald-400 font-mono font-bold">GH₵ {{ number_format($agent->weekly_fee, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('manager.scouts.release', $agent->id) }}" method="POST" class="mt-4 pt-2 border-t border-white/5 flex justify-end">
                        @csrf
                        <button type="submit" class="text-xs text-red-400 hover:text-red-300 font-bold uppercase tracking-wider flex items-center gap-1">
                            <i data-lucide="user-minus" class="w-3.5 h-3.5"></i> Release Agent
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-gray-400 text-xs">
                    No scouting agents currently retained. Select from the available league registry below.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Section 2: Certified Scouting Agents Registry -->
    <div class="glass-card p-6">
        <h2 class="font-heading font-bold text-lg text-white uppercase tracking-wider mb-4 flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-cyan-400"></i> Certified League Scouts Available for Hire
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($allAgents as $agent)
                @php $isSigned = $myAgents->contains($agent->id); @endphp
                <div class="bg-white/5 border border-white/10 rounded-xl p-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-heading font-extrabold text-base text-white uppercase">{{ $agent->name }}</h3>
                            @if($isSigned)
                                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400 bg-emerald-400/10 px-2 py-0.5 rounded">Signed</span>
                            @else
                                <span class="text-[10px] font-bold uppercase tracking-wider text-cyan-400 bg-cyan-400/10 px-2 py-0.5 rounded">Available</span>
                            @endif
                        </div>
                        <p class="text-xs text-cyan-300 mt-1 font-semibold">{{ $agent->specialization }}</p>

                        <div class="mt-3 grid grid-cols-2 gap-2 text-xs text-gray-400 border-t border-white/5 pt-2">
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-500">Rating</span>
                                <div class="flex text-amber-400">
                                    @for($i=1; $i<=5; $i++)
                                        <i data-lucide="star" class="w-3 h-3 {{ $i <= $agent->experience_rating ? 'fill-amber-400' : 'text-gray-600' }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div>
                                <span class="block text-[10px] uppercase font-bold text-gray-500">Fee</span>
                                <span class="text-emerald-400 font-mono font-bold">GH₵ {{ number_format($agent->weekly_fee, 2) }}/wk</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-2 border-t border-white/5 flex justify-end">
                        @if($isSigned)
                            <button disabled class="px-3 py-1.5 bg-white/10 text-gray-400 rounded-lg text-xs font-bold uppercase cursor-not-allowed">
                                Retained
                            </button>
                        @else
                            <form action="{{ route('manager.scouts.sign', $agent->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-3 py-1.5 bg-accent-gold text-black rounded-lg text-xs font-heading font-black uppercase tracking-wider hover:bg-amber-400 shadow">
                                    Sign Scout
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-6 text-center text-gray-400 text-xs">
                    No league scouting agents currently registered by Admin.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal: Discover & Submit Player -->
<div id="discover-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 flex items-center justify-center hidden p-4">
    <div class="glass-card max-w-lg w-full p-6 border border-white/20">
        <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-4">
            <h3 class="font-heading font-black text-xl text-accent-gold uppercase tracking-wider flex items-center gap-2">
                <i data-lucide="user-check" class="w-5 h-5"></i> Submit Scouted Player
            </h3>
            <button onclick="toggleModal('discover-player-modal')" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('manager.scouts.submit-player') }}" method="POST" class="space-y-4 text-xs">
            @csrf
            <div>
                <label class="block text-gray-400 uppercase font-bold mb-1">Player Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Abednego Mensah" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
            </div>

            <div class="grid grid-cols-3 gap-3">
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Position</label>
                    <select name="position" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="FWD">FWD (Forward)</option>
                        <option value="MID">MID (Midfielder)</option>
                        <option value="DEF">DEF (Defender)</option>
                        <option value="GK">GK (Goalkeeper)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Rating (50-99)</label>
                    <input type="number" name="rating" min="50" max="99" value="78" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Age</label>
                    <input type="number" name="age" min="16" max="40" value="21" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Nationality</label>
                    <input type="text" name="nationality" value="Ghanaian" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-gray-400 uppercase font-bold mb-1">Destination Action</label>
                    <select name="destination" id="dest-select" onchange="toggleMarketFields(this.value)" required class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="squad">Sign Directly to Squad</option>
                        <option value="market">List Directly on Transfer Market</option>
                    </select>
                </div>
            </div>

            <div id="market-fields" class="hidden space-y-3 pt-2 border-t border-white/10">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-gray-400 uppercase font-bold mb-1">Listing Type</label>
                        <select name="listing_type" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-2 text-white focus:border-accent-gold outline-none">
                            <option value="permanent">Permanent Purchase</option>
                            <option value="loan_half">Loan (Half Season)</option>
                            <option value="loan_full">Loan (Full Season)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-400 uppercase font-bold mb-1">Asking Price (GH₵)</label>
                        <input type="number" step="0.01" name="asking_price" placeholder="e.g. 1500000" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-2 text-white focus:border-accent-gold outline-none">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-white/10 flex justify-end gap-3">
                <button type="button" onclick="toggleModal('discover-player-modal')" class="px-4 py-2 bg-white/5 border border-white/10 rounded-xl text-gray-400 font-bold uppercase hover:bg-white/10">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2 bg-accent-gold text-black rounded-xl font-heading font-black uppercase tracking-wider hover:bg-amber-400 shadow-lg shadow-gold/20">
                    Submit Player
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleMarketFields(val) {
        const container = document.getElementById('market-fields');
        if (val === 'market') {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }
</script>
@endsection
