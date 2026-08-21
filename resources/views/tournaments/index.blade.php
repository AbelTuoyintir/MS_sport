<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Apex Champions Cup — Tournament Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;600;700;800;900&family=Barlow:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
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
        .bracket-connector {
            position: relative;
        }
        .bracket-connector::after {
            content: '';
            position: absolute;
            right: -20px;
            top: 50%;
            width: 20px;
            height: 2px;
            background: rgba(0, 229, 255, 0.3);
        }
    </style>
</head>
<body class="bg-bg-dark text-gray-100 font-body min-h-screen flex flex-col">

    <!-- NAVIGATION -->
    <nav class="bg-bg-dark2/90 backdrop-blur border-b border-border-dark sticky top-0 z-50 px-4 sm:px-8 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline">
            <div class="w-8 h-8 bg-gradient-to-br from-gold to-gold2 rounded-lg flex items-center justify-center font-bold text-black text-sm">🏆</div>
            <div>
                <div class="font-heading text-base font-black text-white leading-none">APEX CHAMPIONS CUP</div>
                <div class="text-[9px] text-gold tracking-widest font-bold uppercase">Official Knockout Bracket Hub</div>
            </div>
        </a>
        <div class="flex items-center gap-4 text-xs font-heading font-bold uppercase">
            <a href="{{ route('home') }}" class="text-muted hover:text-white transition-colors">Home</a>
            <a href="{{ route('stats') }}" class="text-muted hover:text-white transition-colors">Stats</a>
            <a href="{{ route('predictor') }}" class="text-muted hover:text-white transition-colors">Match Predictor</a>
            <a href="{{ route('tournaments.index') }}" class="text-accent border-b-2 border-accent pb-0.5">Champions Cup</a>
        </div>
    </nav>

    <!-- MAIN CONTAINER -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-8 py-8">

        <!-- HEADER BANNER -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-bg-dark3 via-bg-dark2 to-bg-dark3 border border-border-dark p-6 sm:p-10 mb-8">
            <div class="relative z-10 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gold/10 border border-gold/30 text-gold text-[10px] font-heading font-extrabold tracking-wider uppercase mb-3">
                    <span>🏆 Season 2024/25 Knockout Tournament</span>
                </div>
                <h1 class="font-display text-4xl sm:text-6xl text-white tracking-wide mb-2">The Apex Champions Cup</h1>
                <p class="text-muted text-xs sm:text-sm leading-relaxed mb-6">
                    Track the elite 8 clubs battling for supremacy in the ultimate knockout competition. Predict bracket outcomes, submit your fan challenge, and follow stage progressions live.
                </p>
            </div>
            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-10 hidden md:block text-9xl select-none">
                🏆
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-xs sm:text-sm font-semibold flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
            </div>
        @endif

        <!-- BRACKET CHALLENGE PREDICTION CARD -->
        <div class="bg-bg-dark2 border border-border-dark rounded-2xl p-6 mb-10">
            <div class="flex items-center justify-between border-b border-border-dark pb-4 mb-6">
                <div>
                    <h2 class="font-heading text-xl font-bold text-white uppercase tracking-wide">Fan Bracket Challenge</h2>
                    <p class="text-muted text-xs">Submit your prediction for the Apex Champions Cup Champion & Final Score</p>
                </div>
                <span class="px-3 py-1 rounded bg-accent/10 border border-accent/20 text-accent font-heading font-bold text-xs uppercase">
                    Challenge Active
                </span>
            </div>

            @if($savedPrediction)
                <div class="p-4 rounded-xl bg-bg-dark3 border border-accent/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-accent/20 text-accent flex items-center justify-center font-bold text-lg">🎯</div>
                        <div>
                            <div class="text-xs text-muted uppercase font-heading font-bold">Prediction Locked In for <span class="text-white">{{ $savedPrediction['fan_name'] }}</span></div>
                            <div class="text-sm font-bold text-white">Predicted Champion: <span class="text-gold">{{ $savedPrediction['predicted_winner'] }}</span> (Final Score: {{ $savedPrediction['predicted_score'] }})</div>
                        </div>
                    </div>
                    <div class="text-[10px] text-muted font-mono">{{ $savedPrediction['submitted_at'] }}</div>
                </div>
            @else
                <form action="{{ route('tournaments.predict') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-heading font-bold text-muted uppercase mb-1">Your Name / Fan Handle</label>
                        <input type="text" name="fan_name" placeholder="e.g. Alex Turner" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-accent"/>
                    </div>
                    <div>
                        <label class="block text-[10px] font-heading font-bold text-muted uppercase mb-1">Predicted Champion</label>
                        <select name="predicted_winner_id" required class="w-full bg-bg-dark3 border border-border-dark rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-accent">
                            <option value="">Select Predicted Champion</option>
                            @foreach($bracketTeams as $t)
                                <option value="{{ is_array($t) ? $t['team_name'] : $t->team_name }}">{{ is_array($t) ? $t['team_name'] : $t->team_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-[10px] font-heading font-bold text-muted uppercase mb-1">Predicted Final Score</label>
                        <div class="flex gap-2">
                            <input type="text" name="predicted_final_score" placeholder="e.g. 2 - 1" required class="flex-1 bg-bg-dark3 border border-border-dark rounded-lg px-3 py-2 text-xs text-white outline-none focus:border-accent"/>
                            <button type="submit" class="bg-gold text-black font-heading font-bold text-xs uppercase px-4 py-2 rounded-lg hover:bg-yellow-400 transition-colors">Submit</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>

        <!-- VISUAL KNOCKOUT BRACKET SECTION -->
        <div class="bg-bg-dark2 border border-border-dark rounded-2xl p-6 overflow-x-auto">
            <h2 class="font-heading text-xl font-bold text-white uppercase tracking-wide mb-6 flex items-center gap-2">
                <span>⚡ Interactive Tournament Bracket</span>
            </h2>

            <div class="min-w-[800px] grid grid-cols-3 gap-8 items-center py-4">

                <!-- STAGE 1: QUARTER-FINALS -->
                <div class="flex flex-col gap-6">
                    <div class="text-xs font-heading font-extrabold text-muted uppercase tracking-wider border-b border-border-dark pb-2 mb-2">Quarter-Finals</div>
                    @foreach($quarterFinals as $match)
                        <div class="bg-bg-dark3 border border-border-dark rounded-xl p-3 shadow-lg relative">
                            <div class="flex items-center justify-between text-[10px] text-muted mb-2 font-heading font-bold uppercase">
                                <span>Quarter-Final {{ $loop->iteration }}</span>
                                <span class="text-emerald-400 font-extrabold">{{ $match['status'] }}</span>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs p-1.5 rounded bg-bg-dark/50 {{ $match['winner_id'] == ($match['home']['id'] ?? null) ? 'border-l-2 border-accent text-white font-bold' : 'text-gray-400' }}">
                                    <span>{{ is_array($match['home']) ? $match['home']['team_name'] : $match['home']->team_name }}</span>
                                    <span class="font-display text-sm">{{ $match['home_score'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs p-1.5 rounded bg-bg-dark/50 {{ $match['winner_id'] == ($match['away']['id'] ?? null) ? 'border-l-2 border-accent text-white font-bold' : 'text-gray-400' }}">
                                    <span>{{ is_array($match['away']) ? $match['away']['team_name'] : $match['away']->team_name }}</span>
                                    <span class="font-display text-sm">{{ $match['away_score'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- STAGE 2: SEMI-FINALS -->
                <div class="flex flex-col gap-16 justify-around h-full py-8">
                    <div class="text-xs font-heading font-extrabold text-muted uppercase tracking-wider border-b border-border-dark pb-2 mb-2">Semi-Finals</div>
                    @foreach($semiFinals as $match)
                        <div class="bg-bg-dark3 border border-accent/30 rounded-xl p-3 shadow-lg">
                            <div class="flex items-center justify-between text-[10px] text-muted mb-2 font-heading font-bold uppercase">
                                <span>Semi-Final {{ $loop->iteration }}</span>
                                <span class="text-emerald-400 font-extrabold">{{ $match['status'] }}</span>
                            </div>
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs p-1.5 rounded bg-bg-dark/50 {{ $match['winner_id'] == ($match['home']['id'] ?? null) ? 'border-l-2 border-gold text-white font-bold' : 'text-gray-400' }}">
                                    <span>{{ is_array($match['home']) ? $match['home']['team_name'] : $match['home']->team_name }}</span>
                                    <span class="font-display text-sm">{{ $match['home_score'] }}</span>
                                </div>
                                <div class="flex items-center justify-between text-xs p-1.5 rounded bg-bg-dark/50 {{ $match['winner_id'] == ($match['away']['id'] ?? null) ? 'border-l-2 border-gold text-white font-bold' : 'text-gray-400' }}">
                                    <span>{{ is_array($match['away']) ? $match['away']['team_name'] : $match['away']->team_name }}</span>
                                    <span class="font-display text-sm">{{ $match['away_score'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- STAGE 3: THE GRAND FINAL -->
                <div class="flex flex-col justify-center h-full">
                    <div class="text-xs font-heading font-extrabold text-gold uppercase tracking-wider border-b border-gold/30 pb-2 mb-4">🏆 The Grand Final</div>
                    @foreach($final as $match)
                        <div class="bg-gradient-to-b from-bg-dark3 to-bg-dark2 border-2 border-gold/50 rounded-2xl p-5 shadow-2xl relative">
                            <div class="text-center mb-3">
                                <span class="text-xs font-heading font-extrabold text-gold tracking-widest uppercase">Apex Cup Final</span>
                                <div class="text-[10px] text-muted mt-0.5">{{ $match['status'] }}</div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-sm p-2 rounded-lg bg-bg-dark/80 text-white font-bold border border-gold/20">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        <span>{{ is_array($match['home']) ? $match['home']['team_name'] : $match['home']->team_name }}</span>
                                    </div>
                                    <span class="font-display text-lg text-gold">{{ $match['home_score'] ?? '-' }}</span>
                                </div>
                                <div class="text-center font-display text-xs text-muted">VS</div>
                                <div class="flex items-center justify-between text-sm p-2 rounded-lg bg-bg-dark/80 text-white font-bold border border-gold/20">
                                    <div class="flex items-center gap-2">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <span>{{ is_array($match['away']) ? $match['away']['team_name'] : $match['away']->team_name }}</span>
                                    </div>
                                    <span class="font-display text-lg text-gold">{{ $match['away_score'] ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </main>

    <!-- FOOTER -->
    <footer class="bg-bg-dark2 border-t border-border-dark py-6 text-center text-xs text-muted mt-auto">
        <p>© 2025 Apex League — Apex Champions Cup Tournament Hub. All rights reserved.</p>
    </footer>

</body>
</html>
