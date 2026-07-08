@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black font-heading uppercase tracking-wider">Tactics & Lineup</h2>
        <div class="bg-white/5 px-4 py-2 rounded-lg border border-white/10">
            <span class="text-gray-400 text-xs uppercase font-bold tracking-wider mr-2">Current Formation:</span>
            <span class="text-accent-gold font-bold">{{ $formation }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-900/30 border border-green-800 text-green-400 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('manager.tactics.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Pitch View -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card p-8 relative overflow-hidden flex justify-center bg-[#0a3d0a]/20">
                    <!-- Football Pitch -->
                    <div class="w-full max-w-[500px] aspect-[2/3] border-2 border-white/20 relative rounded-sm bg-[#0d4f0d]">
                        <!-- Lines -->
                        <div class="absolute inset-0 border border-white/10 m-4"></div>
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20"></div>
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20"></div>
                        <div class="absolute top-1/2 left-0 right-0 h-px bg-white/20"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 border-2 border-white/20 rounded-full"></div>

                        <!-- Selected Players will be displayed here via JS if we had more time for complex UI -->
                        <div class="absolute inset-0 p-8 flex flex-col justify-between">
                            <div class="text-center text-white/20 font-heading text-4xl font-black uppercase pointer-events-none">
                                {{ $team->team_name }}
                            </div>
                            <div class="text-center text-white/10 italic text-sm pointer-events-none">
                                Select 11 players from the list to set your starting XI
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h3 class="text-xl font-bold mb-4">Select Formation</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(['4-4-2', '4-3-3', '3-5-2', '5-3-2', '4-2-3-1', '4-1-4-1', '3-4-3', '5-4-1'] as $f)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="formation" value="{{ $f }}" class="peer hidden" {{ $formation == $f ? 'checked' : '' }}>
                                <div class="p-3 text-center border border-white/10 rounded-xl peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 hover:bg-white/5 transition-all font-bold">
                                    {{ $f }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Squad Selection -->
            <div class="space-y-6">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold">Squad List</h3>
                        <span id="selection-count" class="bg-accent-gold text-bg-dark px-2 py-0.5 rounded text-xs font-black">0/11</span>
                    </div>

                    <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($players as $player)
                            <label class="block group cursor-pointer">
                                <input type="checkbox" name="starting_xi[]" value="{{ $player->id }}"
                                    class="peer hidden player-checkbox"
                                    {{ in_array($player->id, $starting_xi) ? 'checked' : '' }}>
                                <div class="flex items-center justify-between p-3 border border-white/5 rounded-xl bg-white/5 peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 group-hover:bg-white/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center font-bold text-xs">{{ $player->rating }}</div>
                                        <div>
                                            <p class="font-bold text-sm">{{ $player->name }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->position }}</p>
                                        </div>
                                    </div>
                                    <div class="text-accent-gold opacity-0 peer-checked:opacity-100">
                                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" id="submit-btn" class="w-full mt-6 bg-accent-gold text-bg-dark font-black uppercase tracking-widest py-4 rounded-xl hover:bg-[#fff0a0] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Save Tactics
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.player-checkbox');
    const countDisplay = document.getElementById('selection-count');
    const submitBtn = document.getElementById('submit-btn');

    function updateCount() {
        const checkedCount = document.querySelectorAll('.player-checkbox:checked').length;
        countDisplay.textContent = checkedCount + '/11';

        if (checkedCount === 11) {
            countDisplay.classList.remove('bg-accent-gold');
            countDisplay.classList.add('bg-green-500');
            submitBtn.disabled = false;
        } else {
            countDisplay.classList.add('bg-accent-gold');
            countDisplay.classList.remove('bg-green-500');
            submitBtn.disabled = checkedCount !== 11;
        }

        // Disable unchecked if we have 11
        checkboxes.forEach(cb => {
            if (!cb.checked && checkedCount >= 11) {
                cb.disabled = true;
                cb.parentElement.style.opacity = '0.5';
            } else {
                cb.disabled = false;
                cb.parentElement.style.opacity = '1';
            }
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    updateCount();
});
</script>
@endsection
