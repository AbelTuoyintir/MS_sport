<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Comparison Engine — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 1.25rem; }
        .accent-gold { color: #f0c040; }
        .border-gold { border-color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
        .accent-accent { color: #00e5ff; }
        .bg-accent-color { background-color: #00e5ff; }
    </style>
</head>
<body class="p-4 md:p-8 bg-[#04060a] min-h-screen">
    <div class="max-w-6xl mx-auto">
        <!-- Back and Title -->
        <div class="mb-8">
            <a href="{{ route('home') }}" class="accent-gold hover:underline font-heading font-bold text-sm tracking-widest uppercase flex items-center gap-2 mb-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Home
            </a>
            <h1 class="font-display text-5xl md:text-7xl tracking-tight leading-none bg-gradient-to-r from-white via-gray-100 to-amber-200 bg-clip-text text-transparent">Comparison Hub</h1>
            <p class="text-gray-400 text-sm md:text-base mt-2">Compare player statistics side-by-side and view estimated market valuations.</p>
        </div>

        <!-- Selector Form -->
        <div class="glass-card p-6 mb-8">
            <form action="{{ route('players.compare') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Select Player A</label>
                    <select name="player1_id" required class="w-full bg-[#0d1117] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#f0c040] outline-none text-sm appearance-none">
                        <option value="">Choose Player A...</option>
                        @foreach($all_players as $p)
                            <option value="{{ $p->id }}" {{ isset($player1) && $player1->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->position }} — {{ $p->team->team_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center items-center py-2 lg:py-0">
                    <span class="font-display text-2xl text-gray-500">VS</span>
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2 tracking-wider">Select Player B</label>
                    <select name="player2_id" required class="w-full bg-[#0d1117] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-[#f0c040] outline-none text-sm appearance-none">
                        <option value="">Choose Player B...</option>
                        @foreach($all_players as $p)
                            <option value="{{ $p->id }}" {{ isset($player2) && $player2->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->position }} — {{ $p->team->team_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="lg:col-span-5 flex justify-end">
                    <button type="submit" class="bg-gold hover:bg-yellow-500 transition-all text-black font-bold font-heading uppercase px-8 py-3 rounded-xl tracking-wider text-sm flex items-center gap-2 shadow-lg">
                        ⚡ Compare Players
                    </button>
                </div>
            </form>
        </div>

        @if($player1 && $player2)
            <!-- Comparison Profiles Header -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Player 1 Header Card -->
                <div class="glass-card p-6 border-l-4 border-gold bg-gradient-to-br from-gold/5 via-transparent to-transparent">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-gold/10 flex items-center justify-center text-3xl font-black text-[#f0c040]">
                            {{ strtoupper(substr($player1->name, 0, 1)) }}{{ $player1->number ?? '' }}
                        </div>
                        <div>
                            <div class="text-xs text-[#f0c040] font-extrabold uppercase tracking-widest">{{ $player1->position }} — #{{ $player1->number ?? '?' }}</div>
                            <h2 class="font-display text-3xl text-white">{{ $player1->name }}</h2>
                            <div class="text-sm text-gray-400 font-semibold">{{ $player1->team->team_name }}</div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Estimated Valuation</span>
                        <span class="font-display text-2xl text-green-400">GH₵ {{ number_format($player1_value, 0) }}</span>
                    </div>
                </div>

                <!-- Player 2 Header Card -->
                <div class="glass-card p-6 border-l-4 border-accent bg-gradient-to-br from-accent-cyan/5 via-transparent to-transparent">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-xl bg-accent/10 flex items-center justify-center text-3xl font-black text-[#00e5ff]">
                            {{ strtoupper(substr($player2->name, 0, 1)) }}{{ $player2->number ?? '' }}
                        </div>
                        <div>
                            <div class="text-xs text-[#00e5ff] font-extrabold uppercase tracking-widest">{{ $player2->position }} — #{{ $player2->number ?? '?' }}</div>
                            <h2 class="font-display text-3xl text-white">{{ $player2->name }}</h2>
                            <div class="text-sm text-gray-400 font-semibold">{{ $player2->team->team_name }}</div>
                        </div>
                    </div>
                    <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                        <span class="text-xs text-gray-500 uppercase font-bold tracking-wider">Estimated Valuation</span>
                        <span class="font-display text-2xl text-green-400">GH₵ {{ number_format($player2_value, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Table & Progress Bars -->
            <div class="glass-card p-6 md:p-8 mb-8 space-y-6">
                <h3 class="font-display text-3xl border-b border-white/10 pb-2">Statistical Comparison</h3>

                <div class="space-y-6">
                    <!-- Rating -->
                    @include('players.compare_stat_row', [
                        'label' => 'Overall Rating',
                        'val1' => $player1->rating,
                        'val2' => $player2->rating,
                        'max' => 100,
                        'format' => false
                    ])

                    <!-- Goals -->
                    @include('players.compare_stat_row', [
                        'label' => 'Goals Scored',
                        'val1' => $player1->goals,
                        'val2' => $player2->goals,
                        'max' => max(1, max($player1->goals, $player2->goals)),
                        'format' => false
                    ])

                    <!-- Assists -->
                    @include('players.compare_stat_row', [
                        'label' => 'Assists Created',
                        'val1' => $player1->assists,
                        'val2' => $player2->assists,
                        'max' => max(1, max($player1->assists, $player2->assists)),
                        'format' => false
                    ])

                    <!-- Clean Sheets -->
                    @include('players.compare_stat_row', [
                        'label' => 'Clean Sheets',
                        'val1' => $player1->clean_sheets,
                        'val2' => $player2->clean_sheets,
                        'max' => max(1, max($player1->clean_sheets, $player2->clean_sheets)),
                        'format' => false
                    ])

                    <!-- Appearances -->
                    @include('players.compare_stat_row', [
                        'label' => 'Appearances',
                        'val1' => $player1->appearances,
                        'val2' => $player2->appearances,
                        'max' => max(1, max($player1->appearances, $player2->appearances)),
                        'format' => false
                    ])

                    <!-- MOTM Awards -->
                    @include('players.compare_stat_row', [
                        'label' => 'MOTM Awards',
                        'val1' => $player1->motm_awards,
                        'val2' => $player2->motm_awards,
                        'max' => max(1, max($player1->motm_awards, $player2->motm_awards)),
                        'format' => false
                    ])

                    <!-- Yellow Cards (Lower is better) -->
                    @include('players.compare_stat_row', [
                        'label' => 'Yellow Cards',
                        'val1' => $player1->yellow_cards,
                        'val2' => $player2->yellow_cards,
                        'max' => max(1, max($player1->yellow_cards, $player2->yellow_cards)),
                        'format' => false,
                        'lower_is_better' => true
                    ])

                    <!-- Red Cards (Lower is better) -->
                    @include('players.compare_stat_row', [
                        'label' => 'Red Cards',
                        'val1' => $player1->red_cards,
                        'val2' => $player2->red_cards,
                        'max' => max(1, max($player1->red_cards, $player2->red_cards)),
                        'format' => false,
                        'lower_is_better' => true
                    ])
                </div>
            </div>

            <!-- Market Value breakdown explanation -->
            <div class="glass-card p-6 md:p-8">
                <h3 class="font-display text-3xl border-b border-white/10 pb-2 mb-4">Value Estimator Methodology</h3>
                <p class="text-gray-400 text-xs md:text-sm mb-4 leading-relaxed">
                    Estimated valuations are computed dynamically using a highly specialized performance-rating algorithm.
                    It combines <span class="text-white font-bold">Base Skill Weight (Overall Rating squared)</span>,
                    <span class="text-white font-bold">Goal Premium (GH₵ 60k/goal)</span>,
                    <span class="text-white font-bold">Assist Premium (GH₵ 45k/assist)</span>,
                    <span class="text-white font-bold">Clean Sheet Premium (GH₵ 40k/sheet)</span>,
                    and <span class="text-white font-bold">Age Prospect Premium (+15% for under-23 prospects)</span>, minus booking deductions.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <div class="font-bold text-white text-sm mb-2">{{ $player1->name }} Value Profile</div>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li>• Base Rating Component: <span class="text-white">GH₵ {{ number_format(($player1->rating * $player1->rating) * 300, 0) }}</span></li>
                            <li>• Goals Contribution: <span class="text-white">GH₵ {{ number_format($player1->goals * 60000, 0) }}</span></li>
                            <li>• Assists Contribution: <span class="text-white">GH₵ {{ number_format($player1->assists * 45000, 0) }}</span></li>
                            <li>• Age Factor: <span class="text-white">
                                @if($player1->age && $player1->age < 23) +15% (Prospect Premium)
                                @elseif($player1->age && $player1->age <= 29) +5% (Peak Age)
                                @else Veterans factor applied
                                @endif
                            </span></li>
                        </ul>
                    </div>
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <div class="font-bold text-white text-sm mb-2">{{ $player2->name }} Value Profile</div>
                        <ul class="text-xs text-gray-400 space-y-1">
                            <li>• Base Rating Component: <span class="text-white">GH₵ {{ number_format(($player2->rating * $player2->rating) * 300, 0) }}</span></li>
                            <li>• Goals Contribution: <span class="text-white">GH₵ {{ number_format($player2->goals * 60000, 0) }}</span></li>
                            <li>• Assists Contribution: <span class="text-white">GH₵ {{ number_format($player2->assists * 45000, 0) }}</span></li>
                            <li>• Age Factor: <span class="text-white">
                                @if($player2->age && $player2->age < 23) +15% (Prospect Premium)
                                @elseif($player2->age && $player2->age <= 29) +5% (Peak Age)
                                @else Veterans factor applied
                                @endif
                            </span></li>
                        </ul>
                    </div>
                </div>
            </div>
        @else
            <!-- Placeholder screen when players aren't selected -->
            <div class="glass-card p-12 text-center flex flex-col items-center justify-center">
                <span class="text-6xl mb-4">🆚</span>
                <h3 class="font-display text-3xl mb-2 text-white">Select Players to Compare</h3>
                <p class="text-gray-400 text-sm max-w-md">Use the dropdowns above to select two football players from any club and compare their statistical profiles side-by-side.</p>
            </div>
        @endif
    </div>
</body>
</html>