<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Prediction Leaderboard — MP League</title>
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
    <a href="{{ route('home') }}" class="font-heading text-xs font-bold tracking-[0.8px] uppercase text-gold hover:text-gold3 transition-colors">Home</a>
    <a href="{{ route('stats') }}" class="font-heading text-xs font-bold tracking-[0.8px] uppercase text-muted hover:text-text-light transition-colors">Stats</a>
  </div>
</nav>

<div class="max-w-4xl mx-auto px-4 mt-8">
    <div class="flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4 mb-8">
        <div>
          <div class="flex items-center gap-2 mb-2">
            <div class="w-[14px] sm:w-[18px] h-[2px] bg-gold"></div>
            <div class="font-heading text-[9px] sm:text-[10px] font-bold tracking-[2px] sm:tracking-[3px] text-gold uppercase">Gamified Prediction System</div>
          </div>
          <h1 class="font-display text-4xl sm:text-5xl md:text-6xl text-text-light leading-none">Prediction Leaderboard</h1>
          <p class="text-xs text-muted mt-2 max-w-xl">Who is the ultimate football mind? Check the global prediction rankings. Point scoring rules: <strong class="text-gold">3 points</strong> for an exact score, <strong class="text-accent">1 point</strong> for correct outcome (Win/Draw/Loss).</p>
        </div>
        <a href="{{ route('home') }}" class="font-heading text-xs font-bold uppercase text-accent border border-accent/25 rounded-md px-4 py-2 hover:bg-accent/5 transition-all">← Back to Home</a>
    </div>

    <!-- LEADERBOARD TABLE -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="px-4 py-3.5 border-b border-border-dark flex items-center justify-between bg-bg-dark3">
            <div class="font-heading text-sm font-bold text-text-light uppercase tracking-wider flex items-center gap-2">
                <span>🏆</span> Global Fan Standings
            </div>
            <span class="text-[9px] text-muted uppercase tracking-widest font-extrabold">Top Predictors</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="text-[9px] font-bold tracking-[1px] text-muted uppercase border-b border-border-dark bg-bg-dark2/50">
                        <th class="px-4 py-3 text-center w-12">#</th>
                        <th class="px-4 py-3 text-left">Predictor</th>
                        <th class="px-4 py-3 text-center">Total Predictions</th>
                        <th class="px-4 py-3 text-center">Exact Scores (3 pts)</th>
                        <th class="px-4 py-3 text-center">Correct Outcomes (1 pt)</th>
                        <th class="px-4 py-3 text-center">Total Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-dark/65">
                    @forelse($leaderboard as $row)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 py-3.5 text-center">
                                <span class="font-display text-xl {{ $loop->first ? 'text-gold' : ($loop->iteration == 2 ? 'text-muted2' : ($loop->iteration == 3 ? 'text-amber-700' : 'text-muted')) }}">
                                    {{ $loop->iteration }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-left">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full bg-accent/15 text-accent font-extrabold flex items-center justify-center text-[10px]">⚽</div>
                                    <span class="font-semibold text-text-light text-sm">{{ $row['user_name'] }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3.5 text-center text-sm font-semibold text-muted2">
                                {{ $row['total_predictions'] }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-sm font-bold text-gold">
                                {{ $row['exact'] }}
                            </td>
                            <td class="px-4 py-3.5 text-center text-sm font-bold text-accent">
                                {{ $row['outcome'] }}
                            </td>
                            <td class="px-4 py-3.5 text-center">
                                <span class="font-display text-2xl text-gold">{{ $row['points'] }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-12 text-center text-muted text-sm">
                                No predictions processed yet. Submit predictions on upcoming matches to see yourself here!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
