@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold font-heading uppercase tracking-wider">Scouting Agent Network</h2>
            <p class="text-xs text-gray-400">Sign certified scouting agents, release scouts, and discover top new talent for your club.</p>
        </div>
        @if($myScouts->count() > 0)
            <button onclick="toggleModal('submit-player-modal')" class="bg-accent-gold text-bg-dark font-bold px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Submit Discovered Player
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-900/30 border border-red-800 text-red-400 text-sm rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Signed Scouts -->
    <div class="space-y-4">
        <h3 class="text-lg font-bold font-heading uppercase text-accent-gold flex items-center gap-2">
            <i data-lucide="badge-check" class="w-5 h-5 text-accent-gold"></i> Signed Club Scouts ({{ $myScouts->count() }})
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myScouts as $scout)
                <div class="glass-card p-5 border border-amber-500/30 bg-amber-500/5 relative flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-emerald-500/20 text-emerald-400">Signed Agent</span>
                            <span class="text-xs font-black text-accent-gold">OVR {{ $scout->experience_rating }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-white">{{ $scout->name }}</h4>
                        <p class="text-xs text-gray-400 mb-3">{{ $scout->specialization }} · {{ $scout->nationality ?? 'Global' }}</p>
                        <div class="text-xs text-gray-400 flex justify-between py-1 border-t border-white/5">
                            <span>Weekly Retainer Fee:</span>
                            <span class="text-white font-bold">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span>
                        </div>
                    </div>
                    <form action="{{ route('manager.scouts.release', $scout->id) }}" method="POST" class="mt-4 pt-3 border-t border-white/10">
                        @csrf
                        <button type="submit" class="w-full bg-red-600/20 text-red-400 border border-red-600/40 hover:bg-red-600 hover:text-white font-bold py-1.5 rounded text-xs uppercase transition-colors">
                            Release Scout
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full glass-card p-6 text-center text-gray-400 italic text-sm">
                    No scouting agents currently hired by your club. Sign a scout from the available pool below.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Available Scouts Pool -->
    <div class="space-y-4 pt-6">
        <h3 class="text-lg font-bold font-heading uppercase text-cyan-400 flex items-center gap-2">
            <i data-lucide="users" class="w-5 h-5 text-cyan-400"></i> Available Scout Pool ({{ $availableScouts->count() }})
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableScouts as $scout)
                <div class="glass-card p-5 relative flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] font-extrabold uppercase px-2 py-0.5 rounded bg-blue-500/20 text-blue-400">Available</span>
                            <span class="text-xs font-black text-cyan-400">OVR {{ $scout->experience_rating }}</span>
                        </div>
                        <h4 class="text-lg font-bold text-white">{{ $scout->name }}</h4>
                        <p class="text-xs text-gray-400 mb-3">{{ $scout->specialization }} · {{ $scout->nationality ?? 'Global' }}</p>
                        <div class="text-xs text-gray-400 flex justify-between py-1 border-t border-white/5">
                            <span>Weekly Retainer Fee:</span>
                            <span class="text-white font-bold">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span>
                        </div>
                    </div>
                    <form action="{{ route('manager.scouts.sign', $scout->id) }}" method="POST" class="mt-4 pt-3 border-t border-white/10">
                        @csrf
                        <button type="submit" class="w-full bg-cyan-600 text-white font-bold py-1.5 rounded text-xs uppercase hover:bg-cyan-500 transition-colors">
                            Sign Scout Agent
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-full glass-card p-6 text-center text-gray-400 italic text-sm">
                    No available scouts in the public pool at the moment.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Submit Player Modal -->
@if($myScouts->count() > 0)
<div id="submit-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6">
        <h3 class="text-xl font-bold mb-6">Submit Discovered Player</h3>
        <form action="{{ route('manager.scouts.submit-player') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Assigned Scout</label>
                <select name="scout_agent_id" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                    @foreach($myScouts as $scout)
                        <option value="{{ $scout->id }}" class="bg-gray-900 text-white">{{ $scout->name }} (OVR {{ $scout->experience_rating }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Player Name</label>
                    <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Position</label>
                    <select name="position" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                        <option value="FWD" class="bg-gray-900 text-white">FWD (Forward)</option>
                        <option value="MID" class="bg-gray-900 text-white">MID (Midfielder)</option>
                        <option value="DEF" class="bg-gray-900 text-white">DEF (Defender)</option>
                        <option value="GK" class="bg-gray-900 text-white">GK (Goalkeeper)</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Age</label>
                    <input type="number" name="age" value="19" min="15" max="40" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Rating (OVR)</label>
                    <input type="number" name="rating" value="76" min="50" max="99" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div class="p-3 bg-white/5 border border-white/10 rounded-lg space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="list_on_market" value="1" id="list_market_check" onchange="toggleMarketOptions()" class="rounded border-white/20 bg-white/5 text-accent-gold focus:ring-0">
                    <span class="text-xs font-bold text-gray-300 uppercase">Immediately List on Transfer Market</span>
                </label>
                <div id="market_options" class="hidden space-y-3 pt-2">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Listing Deal Type</label>
                        <select name="deal_type" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white">
                            <option value="permanent" class="bg-gray-900">Permanent Transfer</option>
                            <option value="loan_half" class="bg-gray-900">Loan (Half Season)</option>
                            <option value="loan_full" class="bg-gray-900">Loan (Full Season)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1">Asking Price / Loan Fee (GH₵)</label>
                        <input type="number" name="asking_price" value="15000" class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg">Submit Player</button>
                <button type="button" onclick="toggleModal('submit-player-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10">Cancel</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
function toggleMarketOptions() {
    const check = document.getElementById('list_market_check');
    const options = document.getElementById('market_options');
    if (check && options) {
        if (check.checked) {
            options.classList.remove('hidden');
        } else {
            options.classList.add('hidden');
        }
    }
}
</script>
@endsection
