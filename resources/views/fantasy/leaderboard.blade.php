<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Global Fantasy Leaderboard — MP League</title>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;500;600;700;800;900&family=Barlow:wght@300;400;500;600&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          'gold': '#f0c040',
          'gold2': '#c8930a',
          'accent': '#00e5ff',
          'bg-dark': '#06090e',
          'bg-dark2': '#0d1117',
          'bg-dark3': '#161b24',
          'border-dark': '#1e2a38',
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
</head>
<body class="bg-bg-dark text-text-light font-body min-h-screen">

<!-- TOP NAV -->
<nav class="h-[60px] bg-bg-dark2 border-b border-border-dark flex items-center justify-between px-6">
  <a href="{{ route('home') }}" class="flex items-center gap-2.5">
    <div class="w-8 h-8 bg-gradient-to-br from-gold to-gold2 rounded-lg flex items-center justify-center">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 0 20M2 12h20M12 2c-3 4-3 14 0 20M12 2c3 4 3 14 0 20"/></svg>
    </div>
    <div>
      <div class="font-heading text-base font-black tracking-wide text-text-light">MP LEAGUE</div>
      <div class="text-[8px] text-gold tracking-widest font-bold uppercase">Fantasy Football Hub</div>
    </div>
  </a>

  <div class="flex items-center gap-4 font-heading text-xs uppercase font-bold text-muted">
    <a href="{{ route('home') }}" class="hover:text-text-light">Home</a>
    <a href="{{ route('fantasy.index') }}" class="hover:text-text-light">Fantasy Hub</a>
    <a href="{{ route('fantasy.leaderboard') }}" class="text-gold">Leaderboard</a>
    <a href="{{ route('stats') }}" class="hover:text-text-light">Stats</a>
  </div>
</nav>

<main class="max-w-5xl mx-auto px-4 py-8">
  <div class="text-center mb-8">
    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold/10 border border-gold/20 text-gold font-heading text-xs font-bold uppercase tracking-widest mb-2">
      🏆 Global Fantasy Standings
    </div>
    <h1 class="font-display text-4xl text-text-light">Fantasy Manager <span class="text-gold">Leaderboard</span></h1>
    <p class="text-xs text-muted max-w-md mx-auto mt-1">Check out the top fantasy football managers across the entire league season.</p>
  </div>

  <div class="bg-bg-dark3 border border-border-dark rounded-2xl overflow-hidden shadow-2xl">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="border-b border-border-dark text-[10px] font-heading font-bold uppercase tracking-wider text-muted bg-bg-dark2/50">
          <th class="py-3 px-4 text-center">Rank</th>
          <th class="py-3 px-4">Manager &amp; Squad</th>
          <th class="py-3 px-4">Captain</th>
          <th class="py-3 px-4 text-center">Team Value</th>
          <th class="py-3 px-4 text-center">GW Pts</th>
          <th class="py-3 px-4 text-center">Total Pts</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-border-dark/60 text-xs">
        @foreach($leaderboard as $row)
          @php
            $capt = isset($players[$row['captain_id']]) ? $players[$row['captain_id']]->name : 'Captain';
            $isUser = !empty($row['is_user']);
          @endphp
          <tr class="hover:bg-bg-dark2/60 transition-colors {{ $isUser ? 'bg-gold/5 border-l-4 border-l-gold' : '' }}">
            <td class="py-4 px-4 text-center">
              <span class="font-display text-lg {{ $row['rank'] == 1 ? 'text-gold' : ($row['rank'] == 2 ? 'text-slate-300' : ($row['rank'] == 3 ? 'text-amber-600' : 'text-muted')) }}">
                #{{ $row['rank'] }}
              </span>
            </td>

            <td class="py-4 px-4">
              <div class="font-heading font-bold text-text-light text-sm flex items-center gap-2">
                <span>{{ $row['squad_name'] }}</span>
                @if($isUser)
                  <span class="text-[9px] bg-gold text-bg-dark font-extrabold px-1.5 py-0.5 rounded uppercase">You</span>
                @endif
              </div>
              <div class="text-[10px] text-muted">{{ $row['manager_name'] }}</div>
            </td>

            <td class="py-4 px-4">
              <span class="text-gold font-semibold flex items-center gap-1">
                <span>(C)</span> {{ $capt }}
              </span>
            </td>

            <td class="py-4 px-4 text-center font-heading font-bold text-emerald-400">
              £{{ $row['team_value'] }}
            </td>

            <td class="py-4 px-4 text-center font-heading font-bold text-muted">
              +{{ $row['weekly_points'] }}
            </td>

            <td class="py-4 px-4 text-center">
              <span class="font-display text-2xl text-accent">{{ $row['total_points'] }}</span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</main>

</body>
</html>
