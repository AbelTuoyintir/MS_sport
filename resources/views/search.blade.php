<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Search — MP League</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet"/>
<style>
  body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
  .font-display { font-family: 'Bebas Neue', sans-serif; }
  .font-heading { font-family: 'Barlow Condensed', sans-serif; }
  .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
</style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <a href="{{ route('home') }}" class="text-blue-400 hover:underline mb-8 inline-block">← Back to Home</a>

        <div class="mb-12">
            <h1 class="font-display text-5xl mb-6">Search League</h1>
            <form action="{{ route('search') }}" method="GET" class="relative">
                <input type="text" name="q" value="{{ $query }}" placeholder="Search for teams or players..." class="w-full bg-white/5 border border-white/10 rounded-2xl py-4 px-6 text-xl outline-none focus:border-blue-500 transition-all"/>
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 bg-blue-500 text-black font-bold px-6 py-2 rounded-xl">Search</button>
            </form>
        </div>

        @if($query)
            <div class="space-y-12">
                <!-- Teams Results -->
                <section>
                    <h2 class="font-display text-3xl mb-4 border-b border-white/10 pb-2 text-blue-400">Teams</h2>
                    @if($teams->isEmpty())
                        <p class="text-gray-500 italic">No teams found matching "{{ $query }}".</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($teams as $team)
                                <a href="{{ route('teams.show', $team->id) }}" class="glass-card p-4 flex items-center gap-4 hover:bg-white/10 transition-all">
                                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-black" style="background-color: {{ $team->primary_color }}">
                                        {{ strtoupper(substr($team->team_name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">{{ $team->team_name }}</div>
                                        <div class="text-xs text-gray-500 uppercase">{{ $team->city }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>

                <!-- Players Results -->
                <section>
                    <h2 class="font-display text-3xl mb-4 border-b border-white/10 pb-2 text-blue-400">Players</h2>
                    @if($players->isEmpty())
                        <p class="text-gray-500 italic">No players found matching "{{ $query }}".</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($players as $player)
                                <a href="{{ route('players.show', $player->id) }}" class="glass-card p-4 flex items-center gap-4 hover:bg-white/10 transition-all">
                                    <div class="w-12 h-12 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold">
                                        #{{ $player->number }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-lg">{{ $player->name }}</div>
                                        <div class="text-xs text-gray-500 uppercase">{{ $player->position }} — {{ $player->team->team_name }}</div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>
            </div>
        @endif
    </div>
</body>
</html>
