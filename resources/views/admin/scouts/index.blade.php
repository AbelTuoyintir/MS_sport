@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-black font-heading tracking-wider uppercase">Scouting Agent Registry</h1>
            <p class="text-xs text-muted">Register and manage certified league scouting agents available for hire by team managers.</p>
        </div>
        <button onclick="toggleModal('create-agent-modal')" class="bg-gradient-to-r from-gold to-gold2 text-black font-heading font-extrabold px-5 py-2.5 rounded-xl uppercase tracking-wider text-xs shadow-lg shadow-gold/20 hover:scale-105 transition-all flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Register New Scout Agent
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-custom-green/10 border border-custom-green/30 text-custom-green text-xs font-semibold rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-4 h-4"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="glass-card overflow-hidden">
        <div class="p-6 border-b border-border-dark flex justify-between items-center">
            <h3 class="font-heading font-bold text-lg uppercase tracking-wider">Certified Scouting Agents</h3>
            <span class="text-xs text-muted font-mono">{{ $agents->count() }} Registered</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-border-dark bg-bg-dark3/50 text-[10px] font-heading font-bold text-muted uppercase tracking-wider">
                        <th class="py-3 px-6">Agent Name</th>
                        <th class="py-3 px-6">Experience Rating</th>
                        <th class="py-3 px-6">Specialization</th>
                        <th class="py-3 px-6">Weekly Fee</th>
                        <th class="py-3 px-6">Status / Assigned Team</th>
                        <th class="py-3 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark/50 text-xs font-body">
                    @forelse($agents as $agent)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-4 px-6 font-bold text-text-light flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-bg-dark4 border border-border-dark flex items-center justify-center font-heading font-extrabold text-gold text-xs">
                                    {{ strtoupper(substr($agent->name, 0, 2)) }}
                                </div>
                                {{ $agent->name }}
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold font-mono bg-accent/10 text-accent border border-accent/20">
                                    {{ $agent->experience_rating }} / 100 EXP
                                </span>
                            </td>
                            <td class="py-4 px-6 font-medium text-muted">
                                {{ $agent->specialization }}
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-gold">
                                GH₵ {{ number_format($agent->weekly_fee, 2) }}
                            </td>
                            <td class="py-4 px-6">
                                @if($agent->team)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-custom-green/10 text-custom-green border border-custom-green/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-custom-green"></span>
                                        Signed: {{ $agent->team->team_name }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-muted/10 text-muted border border-border-dark">
                                        <span class="w-1.5 h-1.5 rounded-full bg-muted"></span>
                                        Available in Pool
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right">
                                <form action="{{ route('admin.scouts.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('Remove this scouting agent?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-muted hover:text-custom-red transition-colors rounded-lg hover:bg-custom-red/10" title="Delete Agent">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-muted italic">
                                No scouting agents registered yet. Click "Register New Scout Agent" to add certified scouts.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Create Scout Agent -->
<div id="create-agent-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="glass-card w-full max-w-lg p-6 space-y-6">
        <div class="flex justify-between items-center border-b border-border-dark pb-4">
            <h3 class="text-lg font-heading font-extrabold uppercase tracking-wider text-gold">Register Scouting Agent</h3>
            <button onclick="toggleModal('create-agent-modal')" class="text-muted hover:text-text-light">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
        <form action="{{ route('admin.scouts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-heading font-bold text-muted uppercase tracking-wider mb-1">Agent Full Name</label>
                <input type="text" name="name" required placeholder="e.g. Kwame Mensah" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2.5 text-xs text-text-light focus:border-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[11px] font-heading font-bold text-muted uppercase tracking-wider mb-1">Experience Rating (1-100)</label>
                    <input type="number" name="experience_rating" min="1" max="100" value="85" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2.5 text-xs text-text-light focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-[11px] font-heading font-bold text-muted uppercase tracking-wider mb-1">Weekly Fee (GH₵)</label>
                    <input type="number" name="weekly_fee" min="0" step="0.01" value="3500" required class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2.5 text-xs text-text-light focus:border-gold outline-none">
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-heading font-bold text-muted uppercase tracking-wider mb-1">Specialization / Region</label>
                <input type="text" name="specialization" required placeholder="e.g. West African Youth & Free Agents" class="w-full bg-bg-dark3 border border-border-dark rounded-xl px-4 py-2.5 text-xs text-text-light focus:border-gold outline-none">
            </div>
            <div class="flex gap-3 pt-4 border-t border-border-dark">
                <button type="submit" class="flex-1 bg-gold text-black font-heading font-extrabold py-3 rounded-xl uppercase text-xs tracking-wider shadow-lg shadow-gold/10 hover:scale-[1.02] transition-transform">
                    Save Scouting Agent
                </button>
                <button type="button" onclick="toggleModal('create-agent-modal')" class="px-5 bg-bg-dark3 border border-border-dark font-heading font-bold text-xs uppercase rounded-xl hover:bg-white/5">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) modal.classList.toggle('hidden');
    }
</script>
@endsection
