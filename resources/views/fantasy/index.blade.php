<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Fantasy Football Manager Hub — MP League</title>
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
    <a href="{{ route('fantasy.index') }}" class="text-gold">Fantasy Hub</a>
    <a href="{{ route('fantasy.leaderboard') }}" class="hover:text-text-light">Leaderboard</a>
    <a href="{{ route('stats') }}" class="hover:text-text-light">Stats</a>
  </div>
</nav>

<!-- MAIN CONTENT -->
<main class="max-w-7xl mx-auto px-4 py-8">
  <!-- BANNER -->
  <div class="bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 border border-border-dark rounded-2xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-6">
    <div>
      <div class="flex items-center gap-2 text-gold font-heading text-xs font-bold tracking-widest uppercase mb-1">
        <span>⚡ Fantasy Premier Manager</span>
      </div>
      <h1 class="font-display text-4xl text-text-light">Build Your <span class="text-gold">Dream Squad</span></h1>
      <p class="text-xs text-muted max-w-lg mt-1">Select your team within the £100.0M budget. Designate a Captain for double (2x) points and compete for glory on the global leaderboard!</p>
    </div>

    <div class="flex items-center gap-6 bg-bg-dark/60 border border-border-dark rounded-xl p-4">
      <div class="text-center">
        <div class="text-[9px] font-heading font-bold text-muted uppercase tracking-wider">Budget Left</div>
        <div class="font-display text-3xl {{ $budgetLeft < 0 ? 'text-red-400' : 'text-emerald-400' }}">£{{ number_format($budgetLeft, 1) }}M</div>
      </div>
      <div class="w-px h-10 bg-border-dark"></div>
      <div class="text-center">
        <div class="text-[9px] font-heading font-bold text-muted uppercase tracking-wider">Total Value</div>
        <div class="font-display text-3xl text-gold">£{{ number_format($totalSpent, 1) }}M</div>
      </div>
      <div class="w-px h-10 bg-border-dark"></div>
      <div class="text-center">
        <div class="text-[9px] font-heading font-bold text-muted uppercase tracking-wider">Fantasy Pts</div>
        <div class="font-display text-3xl text-accent">{{ $totalPoints }}</div>
      </div>
    </div>
  </div>

  @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-xl text-emerald-400 text-xs font-semibold">
      ✓ {{ session('success') }}
    </div>
  @endif

  @if($errors->any())
    <div class="mb-6 p-4 bg-red-500/10 border border-red-500/30 rounded-xl text-red-400 text-xs font-semibold">
      ⚠️ {{ $errors->first() }}
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- LEFT SQUAD BUILDER FORM & PITCH -->
    <div class="lg:col-span-2 space-y-6">
      <form action="{{ route('fantasy.squad.store') }}" method="POST">
        @csrf

        <div class="bg-bg-dark3 border border-border-dark rounded-xl p-5 mb-6">
          <h2 class="font-heading text-lg font-bold text-text-light uppercase tracking-wide mb-4 flex items-center gap-2">
            <span>📋</span> Squad Configuration
          </h2>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-[10px] font-bold text-muted uppercase mb-1">Squad Name</label>
              <input type="text" name="squad_name" value="{{ old('squad_name', $userSquad['squad_name']) }}" required class="w-full bg-bg-dark border border-border-dark rounded-lg px-3 py-2 text-xs text-text-light outline-none focus:border-gold">
            </div>
            <div>
              <label class="block text-[10px] font-bold text-muted uppercase mb-1">Manager Name</label>
              <input type="text" name="manager_name" value="{{ old('manager_name', $userSquad['manager_name']) }}" required class="w-full bg-bg-dark border border-border-dark rounded-lg px-3 py-2 text-xs text-text-light outline-none focus:border-gold">
            </div>
          </div>
        </div>

        <!-- PITCH VISUALIZER -->
        <div class="bg-emerald-950/20 border border-emerald-500/20 rounded-2xl p-6 relative overflow-hidden min-h-[420px] flex flex-col justify-between">
          <!-- pitch grid lines -->
          <div class="absolute inset-0 opacity-10 pointer-events-none">
            <div class="absolute inset-4 border-2 border-white rounded-lg"></div>
            <div class="absolute top-1/2 left-0 right-0 h-0.5 bg-white"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 border-2 border-white rounded-full"></div>
          </div>

          <div class="relative z-10 flex items-center justify-between mb-4">
            <span class="font-heading text-xs font-bold text-emerald-400 uppercase tracking-widest">Active Pitch Selection ({{ $selectedPlayers->count() }} / 11)</span>
            <span class="text-[10px] text-muted">Select Captain for 2x Multiplier</span>
          </div>

          <!-- SELECTED SQUAD LIST -->
          @if($selectedPlayers->isEmpty())
            <div class="relative z-10 text-center py-16 text-muted text-xs">
              <div class="text-3xl mb-2">⚽</div>
              Your squad is empty. Choose up to 11 players from the player pool on the right!
            </div>
          @else
            <div class="relative z-10 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 py-4">
              @foreach($selectedPlayers as $player)
                <div class="bg-bg-dark2/90 border border-border-dark rounded-xl p-3 flex flex-col items-center text-center relative group">
                  <div class="w-10 h-10 rounded-full bg-gold/10 border border-gold/30 flex items-center justify-center font-display text-base text-gold mb-1">
                    {{ strtoupper(substr($player->name, 0, 2)) }}
                  </div>

                  <div class="font-heading text-xs font-bold text-text-light truncate w-full">{{ $player->name }}</div>
                  <div class="text-[9px] text-muted">{{ $player->team->team_name ?? 'Free Agent' }} · {{ $player->position }}</div>

                  <div class="flex items-center gap-2 mt-2">
                    <span class="text-[10px] font-bold text-emerald-400">£{{ number_format($player->price, 1) }}M</span>
                    <span class="text-[10px] font-bold text-accent">{{ $player->fantasy_points }} pts</span>
                  </div>

                  <div class="mt-2 flex items-center gap-2">
                    <label class="flex items-center gap-1 text-[9px] text-gold cursor-pointer">
                      <input type="radio" name="captain_id" value="{{ $player->id }}" {{ $userSquad['captain_id'] == $player->id ? 'checked' : '' }} required>
                      <span>(C) 2x</span>
                    </label>
                  </div>

                  <input type="hidden" name="player_ids[]" value="{{ $player->id }}">
                </div>
              @endforeach
            </div>
          @endif

          <div class="relative z-10 mt-6 flex justify-end">
            <button type="submit" class="bg-gold text-bg-dark font-heading font-black text-sm uppercase px-6 py-2.5 rounded-xl hover:bg-gold2 transition-colors">
              Save Fantasy Squad
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- RIGHT PLAYER SELECTION POOL -->
    <div class="bg-bg-dark3 border border-border-dark rounded-xl p-5 h-fit">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-heading text-base font-bold text-text-light uppercase tracking-wide flex items-center gap-2">
          <span>🏃</span> Available Players Pool
        </h2>
        <span class="text-[10px] text-muted">{{ $players->count() }} Players</span>
      </div>

      <div class="space-y-2 max-h-[600px] overflow-y-auto pr-1">
        @foreach($players as $p)
          @php
            $isSelected = in_array($p->id, $userSquad['player_ids']);
          @endphp
          <div class="bg-bg-dark border border-border-dark/60 rounded-lg p-3 flex items-center justify-between gap-3 hover:border-gold/30 transition-colors">
            <div class="flex items-center gap-3 min-w-0">
              <div class="w-8 h-8 rounded-full bg-accent/10 border border-accent/20 flex items-center justify-center font-heading text-xs text-accent font-bold flex-shrink-0">
                {{ $p->position }}
              </div>
              <div class="min-w-0">
                <div class="font-heading text-xs font-bold text-text-light truncate">{{ $p->name }}</div>
                <div class="text-[9px] text-muted truncate">{{ $p->team->team_name ?? 'League' }} · {{ $p->goals }}G {{ $p->assists }}A</div>
              </div>
            </div>

            <div class="flex items-center gap-3 flex-shrink-0">
              <div class="text-right">
                <div class="font-heading text-xs font-bold text-emerald-400">£{{ number_format($p->price, 1) }}M</div>
                <div class="text-[9px] text-accent font-bold">{{ $p->fantasy_points }} pts</div>
              </div>

              <button type="button" onclick="togglePlayerSelect('{{ $p->id }}')" class="px-2.5 py-1 text-[10px] font-heading font-bold rounded uppercase {{ $isSelected ? 'bg-red-500/20 text-red-400 border border-red-500/30' : 'bg-gold/20 text-gold border border-gold/30 hover:bg-gold/30' }}">
                {{ $isSelected ? 'Remove' : 'Select' }}
              </button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</main>

<script>
function togglePlayerSelect(id) {
  let form = document.querySelector('form');
  let inputs = form.querySelectorAll('input[name="player_ids[]"]');
  let existing = Array.from(inputs).find(i => i.value == id);

  if (existing) {
    existing.remove();
  } else {
    if (inputs.length >= 11) {
      alert('You can select a maximum of 11 players for your fantasy squad!');
      return;
    }
    let newInput = document.createElement('input');
    newInput.type = 'hidden';
    newInput.name = 'player_ids[]';
    newInput.value = id;
    form.appendChild(newInput);
  }

  form.submit();
}
</script>
</body>
</html>
