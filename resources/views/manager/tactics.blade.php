<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Tactics — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&family=Barlow+Condensed:wght@700&family=Bebas+Neue&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="font-display text-5xl">Manage Tactics</h1>
            <a href="{{ route('manager.dashboard') }}" class="accent-gold hover:underline">← Back to Dashboard</a>
        </div>

        @if(session('success'))
            <div class="bg-green-500/20 border border-green-500 text-green-500 p-4 rounded-lg mb-8">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('manager.tactics.update') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Formation Selection -->
                <div class="lg:col-span-1 space-y-6">
                    <section class="glass-card p-6">
                        <h2 class="font-heading text-xl uppercase mb-4 accent-gold">Preferred Formation</h2>
                        <select name="formation" class="w-full bg-bg-dark border border-white/10 rounded-lg p-3 text-white outline-none">
                            <option value="4-4-2" {{ $team->formation == '4-4-2' ? 'selected' : '' }}>4-4-2 (Classic)</option>
                            <option value="4-3-3" {{ $team->formation == '4-3-3' ? 'selected' : '' }}>4-3-3 (Attacking)</option>
                            <option value="3-5-2" {{ $team->formation == '3-5-2' ? 'selected' : '' }}>3-5-2 (Wing-back)</option>
                            <option value="4-2-3-1" {{ $team->formation == '4-2-3-1' ? 'selected' : '' }}>4-2-3-1 (Modern)</option>
                            <option value="5-3-2" {{ $team->formation == '5-3-2' ? 'selected' : '' }}>5-3-2 (Defensive)</option>
                        </select>
                        <p class="text-[10px] text-gray-500 mt-2 uppercase tracking-widest font-bold">Formation affects player positioning on the pitch visualizer.</p>
                    </section>

                    <button type="submit" class="w-full bg-gold text-black font-bold py-4 rounded-lg uppercase tracking-widest hover:bg-yellow-500 transition-colors">
                        Save Tactics
                    </button>
                </div>

                <!-- Starting XI Selection -->
                <div class="lg:col-span-2">
                    <section class="glass-card p-6">
                        <h2 class="font-heading text-xl uppercase mb-4 accent-gold">Starting XI</h2>
                        <p class="text-sm text-gray-400 mb-6">Select exactly 11 players for your starting lineup.</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($players as $player)
                                <label class="flex items-center gap-4 p-4 glass-card cursor-pointer hover:bg-white/10 transition-colors border-transparent peer-checked:border-gold">
                                    <input type="checkbox" name="starting_xi[]" value="{{ $player->id }}" class="w-5 h-5 accent-gold"
                                        {{ is_array($team->starting_xi) && in_array($player->id, $team->starting_xi) ? 'checked' : '' }}>
                                    <div>
                                        <div class="font-bold">{{ $player->name }}</div>
                                        <div class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->position }} — Rating: {{ $player->rating }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('starting_xi')
                            <div class="text-red-500 text-sm mt-4">{{ $message }}</div>
                        @enderror
                    </section>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Simple validation to ensure exactly 11 checkboxes are selected
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        const limit = 11;

        checkboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                const checkedCount = document.querySelectorAll('input[type="checkbox"]:checked').length;
                if (checkedCount > limit) {
                    cb.checked = false;
                    alert('You can only select exactly 11 players.');
                }
            });
        });
    </script>
</body>
</html>
