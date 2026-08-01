<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>League Statistics — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@300;400;500;600;700;800;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              'gold': '#f0c040',
              'gold2': '#c8930a',
              'gold3': '#fff0a0',
              'accent': '#00e5ff',
              'accent2': '#007fa8',
              'custom-red': '#ff3b3b',
              'custom-green': '#22c55e',
              'bg-dark': '#06090e',
              'bg-dark2': '#0d1117',
              'bg-dark3': '#161b24',
              'bg-dark4': '#1e2530',
              'border-dark': '#1e2a38',
              'border-dark2': '#2a3848',
              'text-light': '#e8edf4',
              'muted': '#6b7a8d',
              'muted2': '#99aabb',
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
      .bg-noise::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 999;
        opacity: 0.5;
      }
      .glass-card {
        background: rgba(22, 27, 36, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.08);
      }
      .tab-btn.active {
        color: #f0c040;
        border-bottom-color: #f0c040;
        background-color: rgba(240, 192, 64, 0.05);
      }
    </style>
</head>
<body class="bg-bg-dark text-text-light font-body min-h-screen pb-12 overflow-x-hidden">

<div class="bg-noise"></div>

<!-- NAVIGATION -->
<nav class="h-[60px] bg-bg-dark2/90 backdrop-blur-md border-b border-border-dark flex items-center justify-between px-4 sm:px-6 md:px-10 z-[100] sticky top-0">
  <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-2.5 no-underline">
    <div class="w-[30px] h-[30px] md:w-[34px] md:h-[34px] bg-gradient-to-br from-gold to-gold2 rounded-lg flex items-center justify-center">
      <svg class="w-[16px] h-[16px] md:w-[19px] md:h-[19px]" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
    </div>
    <div>
      <div class="font-heading text-xs md:text-base font-black tracking-[0.5px] text-text-light whitespace-nowrap">MEDICAL PREMIER LEAGUE</div>
      <div class="text-[7px] md:text-[8px] text-gold tracking-[1.5px] md:tracking-[2px] font-bold uppercase">CAPE COAST, UCC · Season 2024/25</div>
    </div>
  </a>
  <div class="flex items-center gap-4">
    <a href="{{ route('home') }}" class="font-heading text-xs font-bold tracking-[0.8px] uppercase text-muted hover:text-text-light transition-colors">Home</a>
    <a href="/leaderboard" class="font-heading text-xs font-bold tracking-[0.8px] uppercase text-muted hover:text-text-light transition-colors">Predictions</a>
  </div>
</nav>

<div class="max-w-5xl mx-auto px-4 mt-8">
    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4 mb-8">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
            <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Player Stats</div>
          </div>
          <h1 class="font-display text-4xl sm:text-5xl md:text-6xl text-text-light leading-none">League Statistics</h1>
          <p class="text-xs text-muted mt-2 max-w-xl">Deep dive into UCC Cape Coast's leading players. Filter stats by category below to see top performers, clean sheets, and disciplinaries.</p>
        </div>
        <a href="{{ route('home') }}" class="font-heading text-xs font-bold uppercase text-accent border border-accent/25 rounded-md px-4 py-2 hover:bg-accent/5 transition-all">← Back to Home</a>
    </div>

    <!-- TABS -->
    <div class="flex flex-wrap border-b border-border-dark mb-6 overflow-x-auto gap-1">
        <button class="tab-btn active font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'scorers')">⚽ Goals</button>
        <button class="tab-btn font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'assists')">👟 Assists</button>
        <button class="tab-btn font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'clean-sheets')">🧤 Clean Sheets</button>
        <button class="tab-btn font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'ratings')">⭐ Ratings</button>
        <button class="tab-btn font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'cards')">🟨 Cards</button>
        <button class="tab-btn font-heading text-xs sm:text-sm font-bold tracking-[0.5px] px-4 py-3 cursor-pointer text-muted border-b-2 border-transparent transition-all uppercase whitespace-nowrap" onclick="switchTab(this, 'apps')">🏟 Appearances &amp; MOTM</button>
    </div>

    <!-- CONTENT CONTAINERS -->
    <div class="glass-card rounded-xl overflow-hidden p-2 sm:p-4">

        <!-- Tab 1: Goals -->
        <div id="tab-scorers" class="tab-content block">
            <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">Top Scorers (Golden Boot)</div>
                <div class="text-[10px] text-muted">Season Goals</div>
            </div>
            <div class="divide-y divide-border-dark/65">
                @forelse($topScorers as $index => $player)
                    <div class="flex items-center justify-between py-3.5 px-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3.5">
                            <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-gold' : 'text-muted' }}">{{ $index + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-accent/10 text-accent font-extrabold flex items-center justify-center text-xs">🏃</div>
                            <div>
                                <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light hover:text-gold hover:underline text-sm">{{ $player->name }}</a>
                                <div class="text-[10px] text-muted uppercase font-bold tracking-wider">{{ $player->team->team_name }}</div>
                            </div>
                        </div>
                        <div class="font-display text-3xl text-gold">{{ $player->goals }}</div>
                    </div>
                @empty
                    <p class="p-8 text-center text-muted text-sm">No goals recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Tab 2: Assists -->
        <div id="tab-assists" class="tab-content hidden">
            <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">Top Playmakers (Assists)</div>
                <div class="text-[10px] text-muted">Season Assists</div>
            </div>
            <div class="divide-y divide-border-dark/65">
                @forelse($topAssists as $index => $player)
                    <div class="flex items-center justify-between py-3.5 px-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3.5">
                            <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-accent' : 'text-muted' }}">{{ $index + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-accent/10 text-accent font-extrabold flex items-center justify-center text-xs">👟</div>
                            <div>
                                <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light hover:text-accent hover:underline text-sm">{{ $player->name }}</a>
                                <div class="text-[10px] text-muted uppercase font-bold tracking-wider">{{ $player->team->team_name }}</div>
                            </div>
                        </div>
                        <div class="font-display text-3xl text-accent">{{ $player->assists }}</div>
                    </div>
                @empty
                    <p class="p-8 text-center text-muted text-sm">No assists recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Tab 3: Clean Sheets -->
        <div id="tab-clean-sheets" class="tab-content hidden">
            <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">Golden Glove (Clean Sheets)</div>
                <div class="text-[10px] text-muted">Clean Sheets</div>
            </div>
            <div class="divide-y divide-border-dark/65">
                @forelse($cleanSheets as $index => $player)
                    <div class="flex items-center justify-between py-3.5 px-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3.5">
                            <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-custom-green' : 'text-muted' }}">{{ $index + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-custom-green/10 text-custom-green font-extrabold flex items-center justify-center text-xs">🧤</div>
                            <div>
                                <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light hover:text-custom-green hover:underline text-sm">{{ $player->name }}</a>
                                <div class="text-[10px] text-muted uppercase font-bold tracking-wider">{{ $player->team->team_name }}</div>
                            </div>
                        </div>
                        <div class="font-display text-3xl text-custom-green">{{ $player->clean_sheets }}</div>
                    </div>
                @empty
                    <p class="p-8 text-center text-muted text-sm">No clean sheets recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Tab 4: Ratings -->
        <div id="tab-ratings" class="tab-content hidden">
            <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">Top Rated Players (Squad Rating)</div>
                <div class="text-[10px] text-muted">Average Rating</div>
            </div>
            <div class="divide-y divide-border-dark/65">
                @forelse($topRated as $index => $player)
                    <div class="flex items-center justify-between py-3.5 px-4 hover:bg-white/5 transition-colors">
                        <div class="flex items-center gap-3.5">
                            <span class="font-display text-2xl w-6 text-center {{ $index < 3 ? 'text-gold' : 'text-muted' }}">{{ $index + 1 }}</span>
                            <div class="w-8 h-8 rounded-full bg-yellow-500/10 text-gold font-extrabold flex items-center justify-center text-xs">⭐</div>
                            <div>
                                <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light hover:text-gold hover:underline text-sm">{{ $player->name }}</a>
                                <div class="text-[10px] text-muted uppercase font-bold tracking-wider">{{ $player->team->team_name }}</div>
                            </div>
                        </div>
                        <div class="font-display text-3xl text-gold">{{ number_format($player->rating, 2) }}</div>
                    </div>
                @empty
                    <p class="p-8 text-center text-muted text-sm">No ratings recorded yet.</p>
                @endforelse
            </div>
        </div>

        <!-- Tab 5: Cards -->
        <div id="tab-cards" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Yellow cards -->
                <div>
                    <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                        <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">🟨 Most Yellow Cards</div>
                        <div class="text-[10px] text-muted">Yellow Cards</div>
                    </div>
                    <div class="divide-y divide-border-dark/65">
                        @forelse($mostYellowCards as $index => $player)
                            <div class="flex items-center justify-between py-3 px-4 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="font-display text-xl w-5 text-center text-muted">{{ $index + 1 }}</span>
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light text-xs hover:underline hover:text-gold">{{ $player->name }}</a>
                                        <div class="text-[9px] text-muted uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-2xl text-gold">{{ $player->yellow_cards }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-muted text-xs">No yellow cards.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Red cards -->
                <div>
                    <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                        <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">🟥 Most Red Cards</div>
                        <div class="text-[10px] text-muted">Red Cards</div>
                    </div>
                    <div class="divide-y divide-border-dark/65">
                        @forelse($mostRedCards as $index => $player)
                            <div class="flex items-center justify-between py-3 px-4 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="font-display text-xl w-5 text-center text-muted">{{ $index + 1 }}</span>
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light text-xs hover:underline hover:text-custom-red">{{ $player->name }}</a>
                                        <div class="text-[9px] text-muted uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-2xl text-custom-red">{{ $player->red_cards }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-muted text-xs">No red cards.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 6: Appearances & MOTM -->
        <div id="tab-apps" class="tab-content hidden">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Appearances -->
                <div>
                    <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                        <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">🏟 Most Appearances</div>
                        <div class="text-[10px] text-muted">Appearances</div>
                    </div>
                    <div class="divide-y divide-border-dark/65">
                        @forelse($mostAppearances as $index => $player)
                            <div class="flex items-center justify-between py-3 px-4 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="font-display text-xl w-5 text-center text-muted">{{ $index + 1 }}</span>
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light text-xs hover:underline hover:text-accent">{{ $player->name }}</a>
                                        <div class="text-[9px] text-muted uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-2xl text-accent">{{ $player->appearances }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-muted text-xs">No appearances recorded.</p>
                        @endforelse
                    </div>
                </div>

                <!-- MOTM Awards -->
                <div>
                    <div class="px-4 py-3 border-b border-border-dark flex items-center justify-between">
                        <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider">🌟 Man of the Match Awards</div>
                        <div class="text-[10px] text-muted">MOTM Awards</div>
                    </div>
                    <div class="divide-y divide-border-dark/65">
                        @forelse($motmAwards as $index => $player)
                            <div class="flex items-center justify-between py-3 px-4 hover:bg-white/5 transition-colors">
                                <div class="flex items-center gap-3">
                                    <span class="font-display text-xl w-5 text-center text-muted">{{ $index + 1 }}</span>
                                    <div>
                                        <a href="{{ route('players.show', $player->id) }}" class="font-semibold text-text-light text-xs hover:underline hover:text-gold">{{ $player->name }}</a>
                                        <div class="text-[9px] text-muted uppercase font-bold">{{ $player->team->team_name }}</div>
                                    </div>
                                </div>
                                <div class="font-display text-2xl text-gold">{{ $player->motm_awards }}</div>
                            </div>
                        @empty
                            <p class="p-6 text-center text-muted text-xs">No MOTM awards recorded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function switchTab(btn, tabId) {
        // Remove active class from all buttons
        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.add('text-muted');
        });
        // Add active class to clicked button
        btn.classList.add('active');
        btn.classList.remove('text-muted');

        // Hide all tab contents
        document.querySelectorAll('.tab-content').forEach(c => {
            c.classList.add('hidden');
            c.classList.remove('block');
        });
        // Show target tab content
        const target = document.getElementById('tab-' + tabId);
        if (target) {
            target.classList.remove('hidden');
            target.classList.add('block');
        }
    }
</script>
</body>
</html>
