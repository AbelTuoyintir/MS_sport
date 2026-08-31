@extends('layouts.manager')

@section('content')
<div class="space-y-8">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h2 class="text-3xl font-black font-heading tracking-wider uppercase">Scouting Agents & Talent Hub</h2>
            <p class="text-sm text-gray-400">Sign certified scouting agents, recruit new talent, and submit players to your squad or the transfer market.</p>
        </div>
        @if($signedScouts->count() > 0)
            <button onclick="toggleModal('submit-player-modal')" class="bg-accent-gold text-bg-dark font-heading font-extrabold px-5 py-2.5 rounded-lg uppercase tracking-wider text-sm shadow-lg hover:scale-105 transition-all flex items-center gap-2">
                <i data-lucide="user-plus" class="w-4 h-4"></i>
                Discover & Submit New Player
            </button>
        @endif
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-900/30 border border-green-800 text-green-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="p-4 bg-red-900/30 border border-red-800 text-red-400 text-sm rounded-lg flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i>
            {{ session('error') }}
        </div>
    @endif

    <!-- Signed Scouting Agents Section -->
    <div>
        <h3 class="text-xl font-black font-heading uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="text-accent-gold">🛡️</span> Your Signed Scouting Agents
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($signedScouts as $scout)
                <div class="glass-card p-6 border border-accent-gold/30 bg-gradient-to-br from-accent-gold/10 to-transparent relative">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="font-bold font-heading text-lg text-white">{{ $scout->name }}</div>
                            <div class="text-xs text-accent-gold font-semibold uppercase">{{ $scout->specialization }}</div>
                        </div>
                        <span class="bg-accent-gold text-bg-dark font-mono font-black text-xs px-2.5 py-1 rounded">
                            {{ $scout->experience_rating }} EXP
                        </span>
                    </div>
                    <div class="text-xs text-gray-400 mb-6 space-y-1">
                        <div>Weekly Cost: <span class="text-white font-mono font-bold">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span></div>
                        <div>Status: <span class="text-green-400 font-bold uppercase">Active Duty</span></div>
                    </div>
                    <form action="{{ route('manager.scouts.release', $scout->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-red-600/20 text-red-400 border border-red-500/30 hover:bg-red-600 hover:text-white font-bold py-2 rounded-lg text-xs uppercase tracking-wider transition-all">
                            Release Scout
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-3 glass-card p-8 text-center border-dashed border-white/10">
                    <i data-lucide="user-x" class="w-10 h-10 text-gray-500 mx-auto mb-3"></i>
                    <p class="text-gray-400 text-sm font-semibold">No scouting agents signed yet.</p>
                    <p class="text-xs text-gray-500 mt-1">Sign an available scout from the pool below to unlock talent discovery and submission.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Available Scouts Pool Section -->
    <div>
        <h3 class="text-xl font-black font-heading uppercase tracking-wider mb-4 flex items-center gap-2">
            <span class="text-cyan-400">🌐</span> Certified Scouting Agent Pool
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($availableScouts as $scout)
                <div class="glass-card p-6 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <div class="font-bold font-heading text-lg">{{ $scout->name }}</div>
                            <span class="bg-cyan-500/20 text-cyan-300 font-mono font-bold text-xs px-2.5 py-1 rounded border border-cyan-500/30">
                                {{ $scout->experience_rating }} EXP
                            </span>
                        </div>
                        <div class="text-xs text-gray-400 mb-4 space-y-1">
                            <div>Specialization: <span class="text-gray-200 font-semibold">{{ $scout->specialization }}</span></div>
                            <div>Weekly Fee: <span class="text-accent-gold font-mono font-bold">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span></div>
                        </div>
                    </div>
                    <form action="{{ route('manager.scouts.sign', $scout->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 rounded-lg text-xs uppercase tracking-wider transition-all">
                            Sign Scout
                        </button>
                    </form>
                </div>
            @empty
                <div class="col-span-3 glass-card p-8 text-center text-gray-500 text-sm">
                    No scouting agents available in pool right now.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal: Discover & Submit Player -->
<div id="submit-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-md p-6 space-y-6 border border-accent-gold/40">
        <div class="flex justify-between items-center border-b border-white/10 pb-4">
            <h3 class="text-xl font-black font-heading uppercase text-accent-gold">Submit New Scouted Player</h3>
            <button onclick="toggleModal('submit-player-modal')" class="text-gray-400 hover:text-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('manager.scouts.submit-player') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Player Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Samuel Annan" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Position</label>
                    <select name="position" required class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                        <option value="FWD">FWD (Forward)</option>
                        <option value="MID">MID (Midfielder)</option>
                        <option value="DEF">DEF (Defender)</option>
                        <option value="GK">GK (Goalkeeper)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Age</label>
                    <input type="number" name="age" min="16" max="40" value="20" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Overall Rating (OVR)</label>
                    <input type="number" name="rating" min="50" max="99" value="78" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Est. Value (GH₵)</label>
                    <input type="number" name="estimated_value" min="0" step="1000" value="150000" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Submission Destination</label>
                <select name="submission_type" required class="w-full bg-bg-dark border border-white/10 rounded-lg px-4 py-2.5 text-sm text-white focus:border-accent-gold outline-none">
                    <option value="squad">Sign Directly to Squad</option>
                    <option value="market">Submit to Transfer Market for Bidding</option>
                </select>
            </div>
            <div class="flex gap-4 pt-4 border-t border-white/10">
                <button type="submit" class="flex-1 bg-accent-gold text-bg-dark font-bold py-3 rounded-lg text-sm uppercase font-heading tracking-wider">
                    Submit Player
                </button>
                <button type="button" onclick="toggleModal('submit-player-modal')" class="px-5 bg-white/5 font-bold py-3 rounded-lg border border-white/10 text-sm">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
