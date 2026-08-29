@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold flex items-center gap-2">
                <i data-lucide="user-search" class="w-6 h-6 text-accent-gold"></i>
                Scouting Management & Player Discovery Hub
            </h2>
            <p class="text-xs text-gray-400">Sign scouting agents, manage your talent scouts, and submit new scouted players to your squad or market.</p>
        </div>
        @if($myScouts->isNotEmpty())
            <button onclick="toggleModal('submit-player-modal')" class="px-5 py-2.5 rounded-xl bg-accent-gold text-bg-dark font-bold text-xs uppercase tracking-wider hover:bg-yellow-500 transition-all flex items-center gap-2 shadow-md">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Submit New Scouted Player
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-900/30 border border-green-800 text-green-400 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-3 bg-red-900/30 border border-red-800 text-red-400 text-xs font-bold rounded-lg flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Signed Scouts -->
    <div class="space-y-4">
        <h3 class="text-xl font-bold flex items-center gap-2 text-white">
            <span>🛡️</span> My Signed Scouting Staff
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($myScouts as $scout)
                <div class="glass-card p-5 border border-accent-gold/30 bg-accent-gold/5 relative">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $scout->nationality }}</span>
                            <div>
                                <h4 class="font-bold text-lg text-white">{{ $scout->name }}</h4>
                                <span class="text-xs text-accent-gold font-medium">{{ $scout->specialization }}</span>
                            </div>
                        </div>
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-green-500/20 text-green-400 border border-green-500/30">Active</span>
                    </div>

                    <div class="space-y-1.5 py-3 border-y border-white/10 text-xs">
                        <div class="flex justify-between text-gray-400">
                            <span>Scouting Rating</span>
                            <span class="font-bold text-white">{{ $scout->experience_rating }} / 99</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Retainer Fee</span>
                            <span class="font-bold text-white">GH₵ {{ number_format($scout->weekly_fee, 2) }}/wk</span>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-between items-center">
                        <button onclick="toggleModal('submit-player-modal')" class="text-xs text-accent-gold hover:underline font-bold flex items-center gap-1">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> Submit Player
                        </button>

                        <form action="{{ route('manager.scouts.release', $scout->id) }}" method="POST" onsubmit="return confirm('Release this scout from your club?');">
                            @csrf
                            <button type="submit" class="px-3 py-1.5 rounded bg-red-600/20 text-red-400 hover:bg-red-600/40 text-xs font-bold uppercase tracking-wider transition-all">
                                Release Scout
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center glass-card text-gray-400 text-xs">
                    <p class="mb-3">You do not have any scouting agents signed currently.</p>
                    <p class="text-[11px] text-gray-500">Sign a certified scout below to enable new player discovery and player submissions.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Available Agents Directory -->
    <div class="space-y-4 pt-4">
        <h3 class="text-xl font-bold flex items-center gap-2 text-white">
            <span>🌍</span> Available Scout Agents Directory
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableAgents as $agent)
                <div class="glass-card p-5">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">{{ $agent->nationality }}</span>
                            <div>
                                <h4 class="font-bold text-base text-white">{{ $agent->name }}</h4>
                                <span class="text-xs text-cyan-400 font-medium">{{ $agent->specialization }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5 py-3 border-y border-white/10 text-xs">
                        <div class="flex justify-between text-gray-400">
                            <span>Rating</span>
                            <span class="font-bold text-accent-gold">{{ $agent->experience_rating }} / 99</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Weekly Retainer Fee</span>
                            <span class="font-bold text-white">GH₵ {{ number_format($agent->weekly_fee, 2) }}</span>
                        </div>
                    </div>

                    <div class="pt-4">
                        <form action="{{ route('manager.scouts.sign', $agent->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-2 rounded-lg bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold uppercase tracking-wider transition-colors shadow-sm">
                                Sign Scout Agent
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full p-8 text-center glass-card text-gray-400 text-xs">
                    No scouting agents available for hire right now.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Submit Player Modal -->
<div id="submit-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 bg-bg-dark border border-white/10">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i data-lucide="user-plus" class="w-5 h-5 text-accent-gold"></i> Submit Scouted Player
            </h3>
            <button onclick="toggleModal('submit-player-modal')" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <form action="{{ route('manager.scouts.submit-player') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Player Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Samuel Kuffour" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Position</label>
                    <select name="position" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                        <option value="GK">Goalkeeper (GK)</option>
                        <option value="DEF">Defender (DEF)</option>
                        <option value="MID">Midfielder (MID)</option>
                        <option value="FWD">Forward (FWD)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Rating (50-99)</label>
                    <input type="number" name="rating" min="50" max="99" value="75" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Nationality Emoji</label>
                <input type="text" name="nationality" value="🇬🇭" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Destination</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 bg-white/5 p-3 rounded-lg border border-white/10 cursor-pointer">
                        <input type="radio" name="destination" value="squad" checked onchange="toggleMarketOptions(false)" class="text-accent-gold focus:ring-0">
                        <span class="text-xs font-bold text-white">Direct to Squad</span>
                    </label>
                    <label class="flex items-center gap-2 bg-white/5 p-3 rounded-lg border border-white/10 cursor-pointer">
                        <input type="radio" name="destination" value="market" onchange="toggleMarketOptions(true)" class="text-accent-gold focus:ring-0">
                        <span class="text-xs font-bold text-white">List on Market</span>
                    </label>
                </div>
            </div>

            <div id="market-options-box" class="hidden space-y-4 pt-2 border-t border-white/10">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Listing Type</label>
                        <select name="listing_type" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                            <option value="permanent">Permanent Sale</option>
                            <option value="loan_half">Half-Season Loan</option>
                            <option value="loan_full">Full-Season Loan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-1">Asking Price (GH₵)</label>
                        <input type="number" name="asking_price" placeholder="e.g. 500000" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-xs text-white focus:border-accent-gold outline-none">
                    </div>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg text-xs uppercase tracking-wider">
                    Submit Player
                </button>
                <button type="button" onclick="toggleModal('submit-player-modal')" class="flex-1 bg-white/5 font-bold py-3 rounded-lg border border-white/10 text-xs uppercase tracking-wider text-gray-300">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleMarketOptions(show) {
    const box = document.getElementById('market-options-box');
    if (show) {
        box.classList.remove('hidden');
    } else {
        box.classList.add('hidden');
    }
}
</script>
@endsection
