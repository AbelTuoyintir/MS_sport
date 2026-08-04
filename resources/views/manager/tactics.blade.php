@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black font-heading uppercase tracking-wider">Tactics & Lineup</h2>
        <div class="bg-white/5 px-4 py-2 rounded-lg border border-white/10">
            <span class="text-gray-400 text-xs uppercase font-bold tracking-wider mr-2">Current Formation:</span>
            <span class="text-accent-gold font-bold">{{ $formation }}</span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-900/30 border border-green-800 text-green-400 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('manager.tactics.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Pitch View -->
            <div class="lg:col-span-2 space-y-6">
                <div class="glass-card p-8 relative overflow-hidden flex justify-center bg-[#0a3d0a]/20">
                    <!-- Football Pitch -->
                    <div class="w-full max-w-[500px] aspect-[2/3] border-2 border-white/20 relative rounded-sm bg-[#0d4f0d]">
                        <!-- Lines -->
                        <div class="absolute inset-0 border border-white/10 m-4"></div>
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20"></div>
                        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-48 h-20 border-2 border-white/20"></div>
                        <div class="absolute top-1/2 left-0 right-0 h-px bg-white/20"></div>
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 border-2 border-white/20 rounded-full"></div>

                        <!-- Selected Players will be displayed here via JS if we had more time for complex UI -->
                        <div class="absolute inset-0 p-8 flex flex-col justify-between">
                            <div class="text-center text-white/20 font-heading text-4xl font-black uppercase pointer-events-none">
                                {{ $team->team_name }}
                            </div>
                            <div class="text-center text-white/10 italic text-sm pointer-events-none">
                                Select 11 players from the list to set your starting XI
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-card p-6">
                    <h3 class="text-xl font-bold mb-4">Select Formation</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach(['4-4-2', '4-3-3', '3-5-2', '5-3-2', '4-2-3-1', '4-1-4-1', '3-4-3', '5-4-1'] as $f)
                            <label class="relative cursor-pointer">
                                <input type="radio" name="formation" value="{{ $f }}" class="peer hidden" {{ $formation == $f ? 'checked' : '' }}>
                                <div class="p-3 text-center border border-white/10 rounded-xl peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 hover:bg-white/5 transition-all font-bold">
                                    {{ $f }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Tactical Simulation Center -->
                <div class="glass-card p-6 bg-gradient-to-br from-[#0d1117] to-[#161b24] border-[#f0c040]/15">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <span class="text-accent-gold">⚡</span> Tactical Simulator
                            </h3>
                            <p class="text-xs text-gray-400 mt-0.5">Test your tactics and starting XI against other league clubs in a friendly match simulation.</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col sm:flex-row gap-3 items-end">
                            <div class="flex-1 w-full">
                                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Choose Opponent</label>
                                <select id="opponent-select" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-accent-gold outline-none text-sm appearance-none">
                                    <option value="" class="bg-[#0d1117] text-gray-400">-- Select Opponent --</option>
                                    @foreach($opponents as $opp)
                                        <option value="{{ $opp->id }}" class="bg-[#0d1117]">{{ $opp->team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" id="start-simulation-btn" class="w-full sm:w-auto bg-gradient-to-r from-[#f0c040] to-[#c8930a] text-black font-black uppercase tracking-wider px-6 py-2.5 rounded-lg hover:bg-[#fff0a0] transition-all text-xs disabled:opacity-50 disabled:cursor-not-allowed">
                                Simulate Match
                            </button>
                        </div>

                        <!-- Simulator Interface (Hidden by default) -->
                        <div id="simulation-interface" class="hidden border border-white/10 rounded-xl overflow-hidden mt-6 bg-[#06090e]/40">
                            <!-- Live Score Header -->
                            <div class="p-6 border-b border-white/10 bg-gradient-to-b from-white/5 to-transparent">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex flex-col items-center gap-2 flex-1">
                                        <div id="sim-home-badge" class="w-14 h-14 rounded-full flex items-center justify-center font-black text-white text-base shadow-lg shadow-white/5"></div>
                                        <div id="sim-home-name" class="font-bold text-xs sm:text-sm text-center text-white truncate max-w-[120px]"></div>
                                    </div>
                                    <div class="flex flex-col items-center gap-1.5 min-w-[100px]">
                                        <div id="sim-minute-badge" class="text-[9px] font-extrabold tracking-widest text-[#ff3b3b] bg-[#ff3b3b]/10 px-2 py-0.5 rounded uppercase animate-pulse">0'</div>
                                        <div class="font-display text-4xl sm:text-5xl text-white tracking-widest leading-none">
                                            <span id="sim-home-score">0</span> — <span id="sim-away-score">0</span>
                                        </div>
                                        <div id="sim-status" class="text-[10px] text-gray-500 font-medium">Preparing simulation...</div>
                                    </div>
                                    <div class="flex flex-col items-center gap-2 flex-1">
                                        <div id="sim-away-badge" class="w-14 h-14 rounded-full flex items-center justify-center font-black text-white text-base shadow-lg shadow-white/5"></div>
                                        <div id="sim-away-name" class="font-bold text-xs sm:text-sm text-center text-white truncate max-w-[120px]"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 divide-y md:divide-y-0 md:divide-x divide-white/10">
                                <!-- Commentary Ticker -->
                                <div class="md:col-span-2 p-4">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-ping"></span> Live Match Commentary
                                    </h4>
                                    <div id="sim-commentary-feed" class="space-y-2.5 max-h-[300px] overflow-y-auto pr-2 text-xs font-mono">
                                        <!-- Animated live logs injected here -->
                                    </div>
                                </div>

                                <!-- Statistics Comparison -->
                                <div class="p-4 bg-white/[0.01]">
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Match Statistics</h4>
                                    <div id="sim-stats-comparison" class="space-y-4 text-xs">
                                        <div>
                                            <div class="flex justify-between font-bold text-[10px] uppercase text-gray-500 mb-1">
                                                <span>Ball Possession</span>
                                                <span id="stat-possession-vals">50% - 50%</span>
                                            </div>
                                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden flex">
                                                <div id="stat-possession-home-bar" class="h-full bg-accent-gold transition-all duration-500" style="width: 50%"></div>
                                                <div id="stat-possession-away-bar" class="h-full bg-white/10 transition-all duration-500" style="width: 50%"></div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between font-bold text-[10px] uppercase text-gray-500 mb-1">
                                                <span>Goal Attempts</span>
                                                <span id="stat-shots-vals">0 - 0</span>
                                            </div>
                                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden flex">
                                                <div id="stat-shots-home-bar" class="h-full bg-accent-gold transition-all duration-500" style="width: 50%"></div>
                                                <div id="stat-shots-away-bar" class="h-full bg-white/10 transition-all duration-500" style="width: 50%"></div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between font-bold text-[10px] uppercase text-gray-500 mb-1">
                                                <span>Shots On Target</span>
                                                <span id="stat-target-vals">0 - 0</span>
                                            </div>
                                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden flex">
                                                <div id="stat-target-home-bar" class="h-full bg-accent-gold transition-all duration-500" style="width: 50%"></div>
                                                <div id="stat-target-away-bar" class="h-full bg-white/10 transition-all duration-500" style="width: 50%"></div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between font-bold text-[10px] uppercase text-gray-500 mb-1">
                                                <span>Corners</span>
                                                <span id="stat-corners-vals">0 - 0</span>
                                            </div>
                                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden flex">
                                                <div id="stat-corners-home-bar" class="h-full bg-accent-gold transition-all duration-500" style="width: 50%"></div>
                                                <div id="stat-corners-away-bar" class="h-full bg-white/10 transition-all duration-500" style="width: 50%"></div>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="flex justify-between font-bold text-[10px] uppercase text-gray-500 mb-1">
                                                <span>Fouls Committed</span>
                                                <span id="stat-fouls-vals">0 - 0</span>
                                            </div>
                                            <div class="h-1.5 bg-white/5 rounded-full overflow-hidden flex">
                                                <div id="stat-fouls-home-bar" class="h-full bg-accent-gold transition-all duration-500" style="width: 50%"></div>
                                                <div id="stat-fouls-away-bar" class="h-full bg-white/10 transition-all duration-500" style="width: 50%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Squad Selection -->
            <div class="space-y-6">
                <div class="glass-card p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold">Squad List</h3>
                        <span id="selection-count" class="bg-accent-gold text-bg-dark px-2 py-0.5 rounded text-xs font-black">0/11</span>
                    </div>

                    <div class="space-y-2 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($players as $player)
                            <label class="block group cursor-pointer">
                                <input type="checkbox" name="starting_xi[]" value="{{ $player->id }}"
                                    class="peer hidden player-checkbox"
                                    {{ in_array($player->id, $starting_xi) ? 'checked' : '' }}>
                                <div class="flex items-center justify-between p-3 border border-white/5 rounded-xl bg-white/5 peer-checked:border-accent-gold peer-checked:bg-accent-gold/10 group-hover:bg-white/10 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gray-700 flex items-center justify-center font-bold text-xs">{{ $player->rating }}</div>
                                        <div>
                                            <p class="font-bold text-sm">{{ $player->name }}</p>
                                            <p class="text-[10px] text-gray-500 uppercase font-bold">{{ $player->position }}</p>
                                        </div>
                                    </div>
                                    <div class="text-accent-gold opacity-0 peer-checked:opacity-100">
                                        <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <button type="submit" id="submit-btn" class="w-full mt-6 bg-accent-gold text-bg-dark font-black uppercase tracking-widest py-4 rounded-xl hover:bg-[#fff0a0] transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        Save Tactics
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.player-checkbox');
    const countDisplay = document.getElementById('selection-count');
    const submitBtn = document.getElementById('submit-btn');

    function updateCount() {
        const checkedCount = document.querySelectorAll('.player-checkbox:checked').length;
        countDisplay.textContent = checkedCount + '/11';

        if (checkedCount === 11) {
            countDisplay.classList.remove('bg-accent-gold');
            countDisplay.classList.add('bg-green-500');
            submitBtn.disabled = false;
        } else {
            countDisplay.classList.add('bg-accent-gold');
            countDisplay.classList.remove('bg-green-500');
            submitBtn.disabled = checkedCount !== 11;
        }

        // Disable unchecked if we have 11
        checkboxes.forEach(cb => {
            if (!cb.checked && checkedCount >= 11) {
                cb.disabled = true;
                cb.parentElement.style.opacity = '0.5';
            } else {
                cb.disabled = false;
                cb.parentElement.style.opacity = '1';
            }
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateCount);
    });

    updateCount();

    // Friendly Match Simulator JavaScript
    const opponentSelect = document.getElementById('opponent-select');
    const startSimBtn = document.getElementById('start-simulation-btn');
    const simInterface = document.getElementById('simulation-interface');

    const simHomeBadge = document.getElementById('sim-home-badge');
    const simAwayBadge = document.getElementById('sim-away-badge');
    const simHomeName = document.getElementById('sim-home-name');
    const simAwayName = document.getElementById('sim-away-name');
    const simMinuteBadge = document.getElementById('sim-minute-badge');
    const simHomeScore = document.getElementById('sim-home-score');
    const simAwayScore = document.getElementById('sim-away-score');
    const simStatus = document.getElementById('sim-status');
    const simCommentaryFeed = document.getElementById('sim-commentary-feed');

    // Stat bars
    const statPossessionVals = document.getElementById('stat-possession-vals');
    const statPossessionHomeBar = document.getElementById('stat-possession-home-bar');
    const statPossessionAwayBar = document.getElementById('stat-possession-away-bar');

    const statShotsVals = document.getElementById('stat-shots-vals');
    const statShotsHomeBar = document.getElementById('stat-shots-home-bar');
    const statShotsAwayBar = document.getElementById('stat-shots-away-bar');

    const statTargetVals = document.getElementById('stat-target-vals');
    const statTargetHomeBar = document.getElementById('stat-target-home-bar');
    const statTargetAwayBar = document.getElementById('stat-target-away-bar');

    const statCornersVals = document.getElementById('stat-corners-vals');
    const statCornersHomeBar = document.getElementById('stat-corners-home-bar');
    const statCornersAwayBar = document.getElementById('stat-corners-away-bar');

    const statFoulsVals = document.getElementById('stat-fouls-vals');
    const statFoulsHomeBar = document.getElementById('stat-fouls-home-bar');
    const statFoulsAwayBar = document.getElementById('stat-fouls-away-bar');

    const sleep = ms => new Promise(r => setTimeout(r, ms));

    startSimBtn.addEventListener('click', async function() {
        const opponentId = opponentSelect.value;
        if (!opponentId) {
            alert('Please select an opposing team to simulate!');
            return;
        }

        startSimBtn.disabled = true;
        startSimBtn.textContent = 'Simulating...';
        simInterface.classList.remove('hidden');
        simCommentaryFeed.innerHTML = '<div class="text-gray-500 italic">Connecting to Tactical Engine...</div>';

        simHomeScore.textContent = '0';
        simAwayScore.textContent = '0';
        simMinuteBadge.textContent = "0'";
        simStatus.textContent = 'Preparing teams...';

        try {
            const response = await fetch('{{ route("manager.tactics.simulate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    opponent_team_id: opponentId,
                    _token: '{{ csrf_token() }}'
                })
            });

            if (!response.ok) {
                const errData = await response.json();
                throw new Error(errData.error || 'Simulation failed.');
            }

            const data = await response.json();

            // Set up team presentation
            simHomeName.textContent = data.home_team.name;
            simHomeBadge.textContent = data.home_team.badge;
            simHomeBadge.style.backgroundColor = data.home_team.primary_color;

            simAwayName.textContent = data.away_team.name;
            simAwayBadge.textContent = data.away_team.badge;
            simAwayBadge.style.backgroundColor = data.away_team.primary_color;

            simCommentaryFeed.innerHTML = '';

            // Run through match events sequentially with delays
            let currentHomeScore = 0;
            let currentAwayScore = 0;

            for (let i = 0; i < data.events.length; i++) {
                const event = data.events[i];

                // Simulated delay between events
                await sleep(1500);

                // Update Minute
                simMinuteBadge.textContent = event.minute + "'";

                // If goal, update live scoreboard
                if (event.type === 'goal') {
                    if (event.team_id === {{ auth()->user()->team_id ?? 0 }}) {
                        currentHomeScore++;
                        simHomeScore.textContent = currentHomeScore;
                    } else {
                        currentAwayScore++;
                        simAwayScore.textContent = currentAwayScore;
                    }
                }

                // Add Commentary row
                const row = document.createElement('div');
                row.className = 'py-1 border-b border-white/5 last:border-0 flex items-start gap-2.5';

                let emoji = '🎙️';
                let textClass = 'text-gray-300';
                if (event.type === 'goal') {
                    emoji = '⚽';
                    textClass = 'text-[#f0c040] font-bold';
                } else if (event.type === 'yellow_card') {
                    emoji = '🟨';
                    textClass = 'text-yellow-400';
                } else if (event.type === 'red_card') {
                    emoji = '🟥';
                    textClass = 'text-red-500 font-bold';
                } else if (event.type === 'shot_saved') {
                    emoji = '🧤';
                    textClass = 'text-blue-400';
                } else if (event.type === 'kickoff' || event.type === 'half_time' || event.type === 'full_time') {
                    emoji = '📢';
                    textClass = 'text-accent-gold uppercase font-bold';
                }

                row.innerHTML = `<span class="font-bold text-accent-gold min-w-[24px] text-right">${event.minute}'</span>
                                 <span>${emoji}</span>
                                 <span class="${textClass}">${event.description}</span>`;

                simCommentaryFeed.appendChild(row);
                simCommentaryFeed.scrollTop = simCommentaryFeed.scrollHeight;

                // Dynamically update stats linearly during the simulation
                const progressRatio = (i + 1) / data.events.length;

                // Possession
                const homePoss = Math.round(50 + (data.stats.possession[0] - 50) * progressRatio);
                statPossessionVals.textContent = `${homePoss}% - ${100 - homePoss}%`;
                statPossessionHomeBar.style.width = `${homePoss}%`;
                statPossessionAwayBar.style.width = `${100 - homePoss}%`;

                // Shots
                const homeShots = Math.round(data.stats.shots[0] * progressRatio);
                const awayShots = Math.round(data.stats.shots[1] * progressRatio);
                statShotsVals.textContent = `${homeShots} - ${awayShots}`;
                const totalShots = (homeShots + awayShots) || 1;
                statShotsHomeBar.style.width = `${(homeShots / totalShots) * 100}%`;
                statShotsAwayBar.style.width = `${(awayShots / totalShots) * 100}%`;

                // Shots on target
                const homeTarget = Math.round(data.stats.shots_on_target[0] * progressRatio);
                const awayTarget = Math.round(data.stats.shots_on_target[1] * progressRatio);
                statTargetVals.textContent = `${homeTarget} - ${awayTarget}`;
                const totalTarget = (homeTarget + awayTarget) || 1;
                statTargetHomeBar.style.width = `${(homeTarget / totalTarget) * 100}%`;
                statTargetAwayBar.style.width = `${(awayTarget / totalTarget) * 100}%`;

                // Corners
                const homeCorners = Math.round(data.stats.corners[0] * progressRatio);
                const awayCorners = Math.round(data.stats.corners[1] * progressRatio);
                statCornersVals.textContent = `${homeCorners} - ${awayCorners}`;
                const totalCorners = (homeCorners + awayCorners) || 1;
                statCornersHomeBar.style.width = `${(homeCorners / totalCorners) * 100}%`;
                statCornersAwayBar.style.width = `${(awayCorners / totalCorners) * 100}%`;

                // Fouls
                const homeFouls = Math.round(data.stats.fouls[0] * progressRatio);
                const awayFouls = Math.round(data.stats.fouls[1] * progressRatio);
                statFoulsVals.textContent = `${homeFouls} - ${awayFouls}`;
                const totalFouls = (homeFouls + awayFouls) || 1;
                statFoulsHomeBar.style.width = `${(homeFouls / totalFouls) * 100}%`;
                statFoulsAwayBar.style.width = `${(awayFouls / totalFouls) * 100}%`;

                if (event.type === 'kickoff') {
                    simStatus.textContent = 'First half underway';
                } else if (event.type === 'half_time') {
                    simStatus.textContent = 'Half time interval';
                    await sleep(1000); // extra delay at half time
                    simStatus.textContent = 'Second half underway';
                } else if (event.type === 'full_time') {
                    simStatus.textContent = 'Match Finished';
                }
            }

        } catch (err) {
            console.error(err);
            simCommentaryFeed.innerHTML = `<div class="text-red-500 font-bold">Error during simulation: ${err.message}</div>`;
            simStatus.textContent = 'Simulation aborted';
        } finally {
            startSimBtn.disabled = false;
            startSimBtn.textContent = 'Simulate Match';
        }
    });
});
</script>
@endsection
