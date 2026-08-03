<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Player Comparison — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
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
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="accent-gold hover:underline">← Back to Home</a>
            <h1 class="font-display text-5xl md:text-6xl tracking-tight">Player Comparison</h1>
        </div>

        <!-- Dropdowns Form -->
        <div class="glass-card p-6 mb-8">
            <form action="{{ route('compare') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-xs uppercase text-gray-400 font-bold mb-2">Select Player 1</label>
                    <select name="player1" required class="w-full bg-bg-dark border border-white/10 rounded-lg p-3 text-sm text-white outline-none">
                        <option value="">Select first player</option>
                        @foreach($allPlayers as $p)
                            <option value="{{ $p->id }}" {{ isset($player1) && $player1->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->position }} - {{ $p->team->team_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center items-center py-2 md:py-0">
                    <span class="font-display text-4xl text-gray-500">VS</span>
                </div>

                <div>
                    <label class="block text-xs uppercase text-gray-400 font-bold mb-2">Select Player 2</label>
                    <select name="player2" required class="w-full bg-bg-dark border border-white/10 rounded-lg p-3 text-sm text-white outline-none">
                        <option value="">Select second player</option>
                        @foreach($allPlayers as $p)
                            <option value="{{ $p->id }}" {{ isset($player2) && $player2->id == $p->id ? 'selected' : '' }}>
                                {{ $p->name }} ({{ $p->position }} - {{ $p->team->team_name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-span-1 md:col-span-3">
                    <button type="submit" class="w-full bg-gold text-bg-dark font-display text-2xl py-3 rounded-lg hover:bg-yellow-400 transition-colors">
                        Compare Now
                    </button>
                </div>
            </form>
        </div>

        @if(isset($player1) && isset($player2))
            <!-- Comparison Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-stretch">
                <!-- Player 1 Profile Card -->
                <div class="glass-card p-6 flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-2xl font-black mb-4" style="background-color: {{ $player1->team->primary_color }}">
                        {{ strtoupper(substr($player1->name, 0, 2)) }}
                    </div>
                    <div class="text-[10px] uppercase font-bold text-gold tracking-widest mb-1">{{ $player1->position }} — #{{ $player1->number ?? '?' }}</div>
                    <h2 class="font-display text-4xl mb-1">{{ $player1->name }}</h2>
                    <p class="text-sm text-gray-400 mb-6">{{ $player1->team->team_name }}</p>

                    <div class="w-full p-4 bg-white/5 rounded-xl">
                        <div class="text-[10px] uppercase text-gray-500 font-bold mb-1">Overall Rating</div>
                        <div class="font-display text-5xl text-gold">{{ $player1->rating }}</div>
                    </div>
                </div>

                <!-- Metrics Comparison -->
                <div class="glass-card p-6 md:col-span-1 flex flex-col justify-center space-y-6">
                    <h3 class="font-display text-2xl text-center border-b border-white/10 pb-2 mb-2">Performance Summary</h3>

                    <!-- Goals Comparison -->
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase mb-1">
                            <span>{{ $player1->goals }} Goals</span>
                            <span class="text-gray-500">Goals</span>
                            <span>{{ $player2->goals }} Goals</span>
                        </div>
                        <div class="flex h-2 bg-white/5 rounded-full overflow-hidden">
                            @php
                                $totalGoals = max(1, $player1->goals + $player2->goals);
                                $p1GoalsPct = ($player1->goals / $totalGoals) * 100;
                            @endphp
                            <div class="bg-gold h-full" style="width: {{ $p1GoalsPct }}%"></div>
                            <div class="bg-blue-500 h-full flex-1"></div>
                        </div>
                    </div>

                    <!-- Assists Comparison -->
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase mb-1">
                            <span>{{ $player1->assists }} Assists</span>
                            <span class="text-gray-500">Assists</span>
                            <span>{{ $player2->assists }} Assists</span>
                        </div>
                        <div class="flex h-2 bg-white/5 rounded-full overflow-hidden">
                            @php
                                $totalAssists = max(1, $player1->assists + $player2->assists);
                                $p1AssistsPct = ($player1->assists / $totalAssists) * 100;
                            @endphp
                            <div class="bg-gold h-full" style="width: {{ $p1AssistsPct }}%"></div>
                            <div class="bg-blue-500 h-full flex-1"></div>
                        </div>
                    </div>

                    <!-- Appearances Comparison -->
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase mb-1">
                            <span>{{ $player1->appearances }} Apps</span>
                            <span class="text-gray-500">Appearances</span>
                            <span>{{ $player2->appearances }} Apps</span>
                        </div>
                        <div class="flex h-2 bg-white/5 rounded-full overflow-hidden">
                            @php
                                $totalApps = max(1, $player1->appearances + $player2->appearances);
                                $p1AppsPct = ($player1->appearances / $totalApps) * 100;
                            @endphp
                            <div class="bg-gold h-full" style="width: {{ $p1AppsPct }}%"></div>
                            <div class="bg-blue-500 h-full flex-1"></div>
                        </div>
                    </div>

                    <!-- Discipline (Cards) Comparison -->
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase mb-1">
                            <span>🟨 {{ $player1->yellow_cards }} 🟥 {{ $player1->red_cards }}</span>
                            <span class="text-gray-500">Cards</span>
                            <span>🟨 {{ $player2->yellow_cards }} 🟥 {{ $player2->red_cards }}</span>
                        </div>
                        <div class="flex h-2 bg-white/5 rounded-full overflow-hidden">
                            @php
                                $p1Cards = ($player1->yellow_cards * 1) + ($player1->red_cards * 3);
                                $p2Cards = ($player2->yellow_cards * 1) + ($player2->red_cards * 3);
                                $totalCards = max(1, $p1Cards + $p2Cards);
                                $p1CardsPct = ($p1Cards / $totalCards) * 100;
                            @endphp
                            <div class="bg-gold h-full" style="width: {{ $p1CardsPct }}%"></div>
                            <div class="bg-blue-500 h-full flex-1"></div>
                        </div>
                    </div>

                    <!-- Efficiency (Contribution per App) -->
                    <div>
                        <div class="flex justify-between text-xs font-bold uppercase mb-1">
                            @php
                                $p1Eff = $player1->appearances > 0 ? number_format(($player1->goals + $player1->assists) / $player1->appearances, 2) : '0.00';
                                $p2Eff = $player2->appearances > 0 ? number_format(($player2->goals + $player2->assists) / $player2->appearances, 2) : '0.00';
                            @endphp
                            <span>{{ $p1Eff }} / G</span>
                            <span class="text-gray-500">G+A Per App</span>
                            <span>{{ $p2Eff }} / G</span>
                        </div>
                        <div class="flex h-2 bg-white/5 rounded-full overflow-hidden">
                            @php
                                $totalEff = max(0.1, (float)$p1Eff + (float)$p2Eff);
                                $p1EffPct = ((float)$p1Eff / $totalEff) * 100;
                            @endphp
                            <div class="bg-gold h-full" style="width: {{ $p1EffPct }}%"></div>
                            <div class="bg-blue-500 h-full flex-1"></div>
                        </div>
                    </div>
                </div>

                <!-- Player 2 Profile Card -->
                <div class="glass-card p-6 flex flex-col items-center text-center">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-2xl font-black mb-4" style="background-color: {{ $player2->team->primary_color }}">
                        {{ strtoupper(substr($player2->name, 0, 2)) }}
                    </div>
                    <div class="text-[10px] uppercase font-bold text-blue-400 tracking-widest mb-1">{{ $player2->position }} — #{{ $player2->number ?? '?' }}</div>
                    <h2 class="font-display text-4xl mb-1">{{ $player2->name }}</h2>
                    <p class="text-sm text-gray-400 mb-6">{{ $player2->team->team_name }}</p>

                    <div class="w-full p-4 bg-white/5 rounded-xl">
                        <div class="text-[10px] uppercase text-gray-500 font-bold mb-1">Overall Rating</div>
                        <div class="font-display text-5xl text-blue-400">{{ $player2->rating }}</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Placeholder state -->
            <div class="glass-card p-12 text-center text-gray-500">
                <div class="text-5xl mb-4">📊</div>
                <h3 class="text-xl font-bold mb-2">Compare Player Performances</h3>
                <p class="max-w-md mx-auto text-sm">Select any two players from Cape Coast UCC's Medical Premier League to compare their ratings, goals, assists, and efficiency metrics side-by-side.</p>
            </div>
        @endif
    </div>
</body>
</html>
