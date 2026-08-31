@extends('layouts.admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold font-heading text-text-light uppercase tracking-wider">Scouting Agent Registry</h1>
            <p class="text-xs text-muted">Register and oversee certified scouting agents available for hire by team managers.</p>
        </div>
        <button onclick="toggleModal('register-scout-modal')" class="bg-gold text-black px-4 py-2 rounded-lg font-heading font-bold uppercase text-xs hover:bg-gold-light transition-all flex items-center gap-2">
            <i data-lucide="user-plus" class="w-4 h-4"></i>
            Register Scout Agent
        </button>
    </div>

    @if(session('success'))
        <div class="p-3 bg-custom-green/20 border border-custom-green text-custom-green text-sm rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($scouts as $scout)
            <div class="bg-bg-dark2 border border-border-dark rounded-xl p-5 relative overflow-hidden flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold uppercase px-2 py-0.5 rounded {{ $scout->status === 'available' ? 'bg-custom-green/20 text-custom-green' : 'bg-cyan-500/20 text-cyan-400' }}">
                            {{ ucfirst($scout->status) }}
                        </span>
                        <span class="text-xs text-gold font-bold">OVR {{ $scout->experience_rating }}</span>
                    </div>

                    <h3 class="text-lg font-bold text-text-light font-heading">{{ $scout->name }}</h3>
                    <div class="text-xs text-muted mb-2">{{ $scout->specialization }} · {{ $scout->nationality ?? 'Global' }}</div>

                    <div class="space-y-1 my-3 text-xs">
                        <div class="flex justify-between text-muted">
                            <span>Weekly Fee:</span>
                            <span class="text-text-light font-bold">GH₵ {{ number_format($scout->weekly_fee, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-muted">
                            <span>Assigned Team:</span>
                            <span class="text-accent font-bold">{{ $scout->team ? $scout->team->team_name : 'None (Free Agent Pool)' }}</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-border-dark flex justify-end">
                    <form action="{{ route('admin.scouts.destroy', $scout->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this scout agent?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-custom-red hover:underline flex items-center gap-1 font-bold">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-bg-dark2 border border-border-dark rounded-xl p-8 text-center text-muted">
                No scouting agents currently registered in the database.
            </div>
        @endforelse
    </div>
</div>

<!-- Register Scout Modal -->
<div id="register-scout-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-bg-dark2 border border-border-dark w-full max-w-md p-6 rounded-xl">
        <h3 class="text-xl font-bold font-heading text-text-light uppercase tracking-wider mb-6">Register Scouting Agent</h3>
        <form action="{{ route('admin.scouts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-muted uppercase mb-2">Full Name</label>
                <input type="text" name="name" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-4 py-2 text-text-light focus:border-gold outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-muted uppercase mb-2">Specialization</label>
                    <input type="text" name="specialization" placeholder="e.g. Youth Scouting" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-4 py-2 text-text-light focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-muted uppercase mb-2">Nationality</label>
                    <input type="text" name="nationality" placeholder="e.g. Ghana" class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-4 py-2 text-text-light focus:border-gold outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-muted uppercase mb-2">Rating (1-100)</label>
                    <input type="number" name="experience_rating" value="80" min="1" max="100" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-4 py-2 text-text-light focus:border-gold outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-muted uppercase mb-2">Weekly Fee (GH₵)</label>
                    <input type="number" step="0.01" name="weekly_fee" value="500.00" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-4 py-2 text-text-light focus:border-gold outline-none">
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-gold text-black font-heading font-bold uppercase py-3 rounded-lg">Register Agent</button>
                <button type="button" onclick="toggleModal('register-scout-modal')" class="flex-1 bg-bg-dark3 font-heading font-bold uppercase py-3 rounded-lg border border-border-dark text-muted">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const el = document.getElementById(id);
    if (el) el.classList.toggle('hidden');
}
</script>
@endsection
