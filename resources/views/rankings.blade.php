<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Club Power Rankings & Financial Matrix — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gold': '#f0c040',
                        'accent': '#00e5ff',
                        'custom-green': '#22c55e',
                        'custom-red': '#ff3b3b',
                        'bg-dark': '#06090e',
                        'bg-dark2': '#0d1117',
                        'bg-dark3': '#161b24',
                        'border-dark': '#1e2a38',
                        'border-dark2': '#2a3848',
                        'text-light': '#e8edf4',
                        'muted': '#6b7a8d',
                    },
                    fontFamily: {
                        'display': ['Bebas Neue', 'sans-serif'],
                        'heading': ['Barlow Condensed', 'sans-serif'],
                        'body': ['Barlow', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        .glass-card {
            background: rgba(22, 27, 36, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="bg-bg-dark text-text-light font-body min-h-screen py-8 px-4 sm:px-6 lg:px-10">

    <!-- NAVIGATION HEADER -->
    <div class="max-w-7xl mx-auto mb-8 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="w-10 h-10 rounded-xl bg-bg-dark3 border border-border-dark flex items-center justify-center hover:border-accent text-accent transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <div class="w-2 h-2 rounded-full bg-accent animate-pulse"></div>
                    <span class="font-heading text-xs font-bold text-accent uppercase tracking-widest">Global Football Intelligence</span>
                </div>
                <h1 class="font-display text-3xl sm:text-4xl text-white tracking-wide leading-none">Club Power Rankings & Financial Matrix</h1>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('stats') }}" class="font-heading text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg bg-bg-dark3 border border-border-dark text-muted hover:text-white hover:border-text-light transition-all">Stats Center</a>
            <a href="{{ route('predictor') }}" class="font-heading text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg bg-accent/10 border border-accent/30 text-accent hover:bg-accent/20 transition-all">xG Predictor</a>
            <a href="{{ route('home') }}" class="font-heading text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-lg bg-gold text-bg-dark hover:bg-yellow-300 transition-all">Home Dashboard</a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto space-y-8">

        <!-- HIGHLIGHT STAT CARDS -->
        @php
            $topCpi = $rankings->first();
            $topVal = $rankings->sortByDesc('total_market_value')->first();
            $topOffense = $rankings->sortByDesc('offense_index')->first();
            $topDefense = $rankings->sortByDesc('defense_index')->first();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- CPI Leader -->
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gold/5 rounded-full blur-2xl"></div>
                <div class="text-[9px] font-heading font-extrabold tracking-widest uppercase text-muted mb-2">⚡ CPI Power Leader</div>
                <div class="font-heading text-2xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white" style="background-color: {{ $topCpi['team']->primary_color ?? '#f0c040' }}">
                        {{ strtoupper(substr($topCpi['team']->team_name ?? 'FC', 0, 2)) }}
                    </span>
                    {{ $topCpi['team']->team_name ?? 'N/A' }}
                </div>
                <div class="font-display text-3xl text-gold mt-2">{{ $topCpi['cpi'] ?? 0 }} <span class="text-xs font-body text-muted">/ 100 Index</span></div>
            </div>

            <!-- Most Valuable Squad -->
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-accent/5 rounded-full blur-2xl"></div>
                <div class="text-[9px] font-heading font-extrabold tracking-widest uppercase text-muted mb-2">💎 Most Valuable Squad</div>
                <div class="font-heading text-2xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white" style="background-color: {{ $topVal['team']->primary_color ?? '#00e5ff' }}">
                        {{ strtoupper(substr($topVal['team']->team_name ?? 'FC', 0, 2)) }}
                    </span>
                    {{ $topVal['team']->team_name ?? 'N/A' }}
                </div>
                <div class="font-display text-3xl text-accent mt-2">£{{ $topVal['total_market_value'] ?? 0 }}M <span class="text-xs font-body text-muted">Est. Value</span></div>
            </div>

            <!-- Top Offense -->
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-500/5 rounded-full blur-2xl"></div>
                <div class="text-[9px] font-heading font-extrabold tracking-widest uppercase text-muted mb-2">⚽ Top Offense Potency</div>
                <div class="font-heading text-2xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white" style="background-color: {{ $topOffense['team']->primary_color ?? '#22c55e' }}">
                        {{ strtoupper(substr($topOffense['team']->team_name ?? 'FC', 0, 2)) }}
                    </span>
                    {{ $topOffense['team']->team_name ?? 'N/A' }}
                </div>
                <div class="font-display text-3xl text-custom-green mt-2">{{ $topOffense['offense_index'] ?? 0 }} <span class="text-xs font-body text-muted">Rating</span></div>
            </div>

            <!-- Top Defense -->
            <div class="glass-card p-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-500/5 rounded-full blur-2xl"></div>
                <div class="text-[9px] font-heading font-extrabold tracking-widest uppercase text-muted mb-2">🛡️ Top Defense Solidity</div>
                <div class="font-heading text-2xl font-bold text-white flex items-center gap-2">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black text-white" style="background-color: {{ $topDefense['team']->primary_color ?? '#a855f7' }}">
                        {{ strtoupper(substr($topDefense['team']->team_name ?? 'FC', 0, 2)) }}
                    </span>
                    {{ $topDefense['team']->team_name ?? 'N/A' }}
                </div>
                <div class="font-display text-3xl text-purple-400 mt-2">{{ $topDefense['defense_index'] ?? 0 }} <span class="text-xs font-body text-muted">Rating</span></div>
            </div>
        </div>

        <!-- SIDE BY SIDE CLUB COMPARISON TOOL -->
        @if(isset($compare1) && isset($compare2))
        <div class="glass-card p-6 border border-accent/20">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6 border-b border-border-dark pb-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-accent/10 text-accent border border-accent/30">Head-to-Head Comparison</span>
                    </div>
                    <h2 class="font-display text-2xl text-white mt-1">Side-by-Side Club Power Battle</h2>
                </div>

                <form action="{{ route('rankings') }}" method="GET" class="flex items-center gap-3 flex-wrap">
                    <select name="team1_id" class="bg-bg-dark border border-border-dark rounded-lg px-3 py-1.5 text-xs text-white outline-none focus:border-accent">
                        @foreach($rankings as $item)
                            <option value="{{ $item['team']->id }}" {{ $compare1['team']->id == $item['team']->id ? 'selected' : '' }}>
                                {{ $item['team']->team_name }} (CPI: {{ $item['cpi'] }})
                            </option>
                        @endforeach
                    </select>

                    <span class="font-display text-xl text-gold">VS</span>

                    <select name="team2_id" class="bg-bg-dark border border-border-dark rounded-lg px-3 py-1.5 text-xs text-white outline-none focus:border-accent">
                        @foreach($rankings as $item)
                            <option value="{{ $item['team']->id }}" {{ $compare2['team']->id == $item['team']->id ? 'selected' : '' }}>
                                {{ $item['team']->team_name }} (CPI: {{ $item['cpi'] }})
                            </option>
                        @endforeach
                    </select>

                    <button type="submit" class="bg-accent text-bg-dark font-heading font-extrabold text-xs uppercase px-4 py-2 rounded-lg hover:bg-cyan-300 transition-colors">
                        Compare Clubs
                    </button>
                </form>
            </div>

            <!-- COMPARISON METRICS BARS -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                <!-- Team 1 Card -->
                <div class="bg-bg-dark/80 p-5 rounded-xl border border-border-dark text-center space-y-2">
                    <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center font-heading font-black text-lg text-white shadow-lg" style="background-color: {{ $compare1['team']->primary_color ?? '#00e5ff' }}">
                        {{ strtoupper(substr($compare1['team']->team_name, 0, 2)) }}
                    </div>
                    <h3 class="font-heading text-xl font-bold text-white">{{ $compare1['team']->team_name }}</h3>
                    <div class="text-xs text-accent font-semibold uppercase tracking-widest">Rank #{{ $compare1['rank'] }}</div>
                    <div class="font-display text-4xl text-gold">{{ $compare1['cpi'] }} <span class="text-xs text-muted font-body">CPI</span></div>
                </div>

                <!-- Mid Metrics Progress Comparison -->
                <div class="space-y-4">
                    <!-- CPI Comparison -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-accent">{{ $compare1['cpi'] }}</span>
                            <span class="text-muted uppercase text-[10px] font-bold">Club Power Index</span>
                            <span class="text-gold">{{ $compare2['cpi'] }}</span>
                        </div>
                        <div class="h-2 bg-bg-dark rounded-full overflow-hidden flex">
                            <div class="bg-accent h-full transition-all duration-500" style="width: {{ ($compare1['cpi'] / max(1, $compare1['cpi'] + $compare2['cpi'])) * 100 }}%"></div>
                            <div class="bg-gold h-full transition-all duration-500" style="width: {{ ($compare2['cpi'] / max(1, $compare1['cpi'] + $compare2['cpi'])) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Squad Valuation -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-accent">£{{ $compare1['total_market_value'] }}M</span>
                            <span class="text-muted uppercase text-[10px] font-bold">Squad Market Value</span>
                            <span class="text-gold">£{{ $compare2['total_market_value'] }}M</span>
                        </div>
                        <div class="h-2 bg-bg-dark rounded-full overflow-hidden flex">
                            <div class="bg-accent h-full transition-all duration-500" style="width: {{ ($compare1['total_market_value'] / max(1, $compare1['total_market_value'] + $compare2['total_market_value'])) * 100 }}%"></div>
                            <div class="bg-gold h-full transition-all duration-500" style="width: {{ ($compare2['total_market_value'] / max(1, $compare1['total_market_value'] + $compare2['total_market_value'])) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Offense Index -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-accent">{{ $compare1['offense_index'] }}</span>
                            <span class="text-muted uppercase text-[10px] font-bold">Offense Rating</span>
                            <span class="text-gold">{{ $compare2['offense_index'] }}</span>
                        </div>
                        <div class="h-2 bg-bg-dark rounded-full overflow-hidden flex">
                            <div class="bg-accent h-full transition-all duration-500" style="width: {{ ($compare1['offense_index'] / max(1, $compare1['offense_index'] + $compare2['offense_index'])) * 100 }}%"></div>
                            <div class="bg-gold h-full transition-all duration-500" style="width: {{ ($compare2['offense_index'] / max(1, $compare1['offense_index'] + $compare2['offense_index'])) * 100 }}%"></div>
                        </div>
                    </div>

                    <!-- Defense Index -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1">
                            <span class="text-accent">{{ $compare1['defense_index'] }}</span>
                            <span class="text-muted uppercase text-[10px] font-bold">Defense Rating</span>
                            <span class="text-gold">{{ $compare2['defense_index'] }}</span>
                        </div>
                        <div class="h-2 bg-bg-dark rounded-full overflow-hidden flex">
                            <div class="bg-accent h-full transition-all duration-500" style="width: {{ ($compare1['defense_index'] / max(1, $compare1['defense_index'] + $compare2['defense_index'])) * 100 }}%"></div>
                            <div class="bg-gold h-full transition-all duration-500" style="width: {{ ($compare2['defense_index'] / max(1, $compare1['defense_index'] + $compare2['defense_index'])) * 100 }}%"></div>
                        </div>
                    </div>
                </div>

                <!-- Team 2 Card -->
                <div class="bg-bg-dark/80 p-5 rounded-xl border border-border-dark text-center space-y-2">
                    <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center font-heading font-black text-lg text-white shadow-lg" style="background-color: {{ $compare2['team']->primary_color ?? '#f0c040' }}">
                        {{ strtoupper(substr($compare2['team']->team_name, 0, 2)) }}
                    </div>
                    <h3 class="font-heading text-xl font-bold text-white">{{ $compare2['team']->team_name }}</h3>
                    <div class="text-xs text-gold font-semibold uppercase tracking-widest">Rank #{{ $compare2['rank'] }}</div>
                    <div class="font-display text-4xl text-gold">{{ $compare2['cpi'] }} <span class="text-xs text-muted font-body">CPI</span></div>
                </div>
            </div>
        </div>
        @endif

        <!-- CPI LEADERBOARD TABLE -->
        <div class="glass-card overflow-hidden">
            <div class="p-5 border-b border-border-dark flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="font-display text-2xl text-white">Full League Power Index Leaderboard</h2>
                    <p class="text-xs text-muted">Dynamically weighted based on match performance, goal differential, squad depth, and total valuation.</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-text-light border-collapse">
                    <thead class="bg-bg-dark3/80 font-heading text-[10px] font-bold text-muted uppercase tracking-wider border-b border-border-dark">
                        <tr>
                            <th class="py-3.5 px-4 text-center">Rank</th>
                            <th class="py-3.5 px-4">Club</th>
                            <th class="py-3.5 px-4 text-center">CPI Index</th>
                            <th class="py-3.5 px-4 text-center">Market Value</th>
                            <th class="py-3.5 px-4 text-center">Avg Rating</th>
                            <th class="py-3.5 px-4 text-center">Offense</th>
                            <th class="py-3.5 px-4 text-center">Defense</th>
                            <th class="py-3.5 px-4 text-center">Clean Sheets</th>
                            <th class="py-3.5 px-4 text-center">Financial Tier</th>
                            <th class="py-3.5 px-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-dark/60">
                        @foreach($rankings as $row)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-3.5 px-4 text-center font-heading font-black text-sm text-gold">
                                    #{{ $row['rank'] }}
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-white">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center font-heading text-[10px] font-black text-white" style="background-color: {{ $row['team']->primary_color ?? '#ccc' }}">
                                            {{ strtoupper(substr($row['team']->team_name, 0, 2)) }}
                                        </div>
                                        <span>{{ $row['team']->team_name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <span class="font-display text-lg text-accent">{{ $row['cpi'] }}</span>
                                        <div class="w-16 h-1.5 bg-bg-dark rounded-full overflow-hidden">
                                            <div class="bg-accent h-full rounded-full" style="width: {{ $row['cpi'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 text-center font-heading font-bold text-sm text-gold">
                                    £{{ $row['total_market_value'] }}M
                                </td>
                                <td class="py-3.5 px-4 text-center font-bold text-white">
                                    {{ $row['avg_rating'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-emerald-400 font-bold">
                                    {{ $row['offense_index'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-purple-400 font-bold">
                                    {{ $row['defense_index'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center text-muted">
                                    {{ $row['clean_sheets'] }}
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase border bg-bg-dark text-{{ $row['tier_color'] }} border-{{ $row['tier_color'] }}/30">
                                        {{ $row['tier'] }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <a href="{{ route('rankings', ['team1_id' => $row['team']->id]) }}" class="text-[10px] font-bold uppercase text-accent hover:underline">
                                        Compare
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</body>
</html>
