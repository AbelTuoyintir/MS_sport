@extends('layouts.manager')
@section('content')
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #1e2530;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #f0c040;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c8930a;
        }

        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: borderGlow 2s ease-in-out infinite;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.3);
        }

        .table-row {
            transition: background-color 0.2s ease;
        }

        .table-row:hover {
            background-color: rgba(240, 192, 64, 0.05);
        }

        .action-btn {
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-2px);
        }

        @keyframes borderGlow {
            0%, 100% { border-color: rgba(240, 192, 64, 0.2); }
            50% { border-color: rgba(240, 192, 64, 0.5); }
        }
    </style>

<div class="font-sans antialiased min-h-screen p-4 md:p-6 lg:p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white tracking-tight">
                        Manager <span class="text-[#f0c040]">Dashboard</span>
                    </h1>
                    <p class="text-gray-400 text-sm md:text-base mt-1">
                        {{ auth()->user()->team?->team_name ?? 'N/A' }} — {{ ucfirst(auth()->user()->team?->division ?? 'Premier') }} Division
                    </p>
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="toggleModal('edit-team-modal')" class="bg-white/5 hover:bg-white/10 transition-all duration-200 px-4 py-2 rounded-lg border border-white/10 text-white text-sm font-semibold flex items-center gap-2">
                        <i data-lucide="edit-3" class="w-4 h-4"></i> Edit Profile
                    </button>
                    <div class="hidden sm:flex items-center gap-2 bg-white/5 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/10">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-300 text-sm">{{ now()->format('F Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 text-green-400 text-sm rounded-xl flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
                {{ session('success') }}
            </div>
        @endif

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
            <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-[#f0c040]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="users" class="w-5 h-5 text-[#f0c040]"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Squad</span>
                </div>
                <div class="mb-2">
                    <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_players'] }}</span>
                </div>
                <div class="text-xs text-gray-400">Total Registered Players</div>
            </div>

            <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-[#22c55e]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="target" class="w-5 h-5 text-[#22c55e]"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Goals</span>
                </div>
                <div class="mb-2">
                    <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_goals'] }}</span>
                </div>
                <div class="text-xs text-gray-400">Team Goals this season</div>
            </div>

            <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-[#00e5ff]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="star" class="w-5 h-5 text-[#00e5ff]"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Rating</span>
                </div>
                <div class="mb-2">
                    <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['avg_rating'] }}</span>
                </div>
                <div class="text-xs text-gray-400">Average Squad Rating</div>
            </div>

            <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-10 h-10 bg-[#ff3b3b]/10 rounded-lg flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-[#ff3b3b]"></i>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Defense</span>
                </div>
                <div class="mb-2">
                    <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['clean_sheets'] }}</span>
                </div>
                <div class="text-xs text-gray-400">Total Clean Sheets</div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

            <!-- Squad Management -->
            <div class="lg:col-span-2">
                <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 overflow-hidden backdrop-blur-sm">
                    <div class="px-5 md:px-6 py-4 border-b border-white/10 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-white">Squad List</h2>
                            <p class="text-xs text-gray-400 mt-1">Manage your players and staff</p>
                        </div>
                        <div class="flex gap-2">
                            <button onclick="switchSquadTab('players')" id="tab-players" class="px-3 py-1.5 rounded-lg bg-[#f0c040]/20 text-[#f0c040] text-xs font-bold border border-[#f0c040]/30">Players</button>
                            <button onclick="switchSquadTab('staff')" id="tab-staff" class="px-3 py-1.5 rounded-lg bg-white/5 text-gray-400 text-xs font-bold border border-white/10">Staff</button>
                        </div>
                    </div>

                    <div id="players-list" class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/5">
                                    <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Player</th>
                                    <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Position</th>
                                    <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rating</th>
                                    <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Goals</th>
                                    <th class="text-right px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse(auth()->user()->team?->players ?? [] as $player)
                                <tr class="table-row border-b border-white/5">
                                    <td class="px-5 md:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#f0c040] to-[#c8930a] flex items-center justify-center text-black font-bold text-xs">
                                                {{ strtoupper(substr($player->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <span class="font-semibold text-white text-sm">{{ $player->name }}</span>
                                                <span class="block text-[10px] text-gray-500">#{{ $player->number ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 md:px-6 py-4 text-center">
                                        <span class="text-xs font-bold px-2 py-0.5 rounded bg-white/5 text-gray-400">{{ $player->position }}</span>
                                    </td>
                                    <td class="px-5 md:px-6 py-4 text-center">
                                        <span class="text-sm font-bold text-white">{{ $player->rating }}</span>
                                    </td>
                                    <td class="px-5 md:px-6 py-4 text-center">
                                        <span class="text-[#f0c040] font-bold">{{ $player->goals }}</span>
                                    </td>
                                    <td class="px-5 md:px-6 py-4 text-right">
                                        <form action="{{ route('manager.players.destroy', $player->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500/50 hover:text-red-500 transition-colors p-2"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500 text-sm">No players in squad.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div id="staff-list" class="hidden overflow-x-auto">
                         <table class="w-full">
                            <thead>
                                <tr class="border-b border-white/10 bg-white/5">
                                    <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Staff Member</th>
                                    <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role</th>
                                    <th class="text-right px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($staff as $member)
                                <tr class="table-row border-b border-white/5">
                                    <td class="px-5 md:px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-blue-900/30 flex items-center justify-center text-blue-400 font-bold text-xs">ST</div>
                                            <span class="font-semibold text-white text-sm">{{ $member->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 md:px-6 py-4 text-gray-400 text-sm">{{ $member->role }}</td>
                                    <td class="px-5 md:px-6 py-4 text-right">
                                        <form action="{{ route('manager.staff.destroy', $member->id) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-500/50 hover:text-red-500 transition-colors p-2"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-6 py-8 text-center text-gray-500 text-sm">No staff members.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Side Panel -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-6 backdrop-blur-sm">
                    <h2 class="text-lg font-bold text-white mb-4">Quick Actions</h2>
                    <div class="space-y-3">
                        <button onclick="toggleModal('add-player-modal')" class="action-btn w-full bg-gradient-to-r from-[#f0c040] to-[#c8930a] text-black font-bold py-3 rounded-lg flex items-center justify-center gap-2 text-xs uppercase tracking-wider">
                            <i data-lucide="user-plus" class="w-4 h-4"></i> Add Player
                        </button>
                        <button onclick="toggleModal('add-staff-modal')" class="action-btn w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 text-xs uppercase tracking-wider border border-white/10">
                            <i data-lucide="users" class="w-4 h-4"></i> Add Staff
                        </button>
                        <a href="{{ route('manager.tactics.index') }}" class="action-btn w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-lg flex items-center justify-center gap-2 text-xs uppercase tracking-wider border border-white/10 no-underline">
                            <i data-lucide="layout" class="w-4 h-4"></i> Manage Tactics
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-5 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-white">Recent Results</h3>
                        <i data-lucide="activity" class="w-4 h-4 text-gray-500"></i>
                    </div>
                    <div class="space-y-4">
                        @forelse($recent_activities as $activity)
                        <div class="flex items-start gap-3">
                            <div class="w-1.5 h-1.5 rounded-full mt-1.5" style="background-color: {{ $activity->color }}"></div>
                            <div>
                                <p class="text-xs text-gray-300">{!! $activity->message !!}</p>
                                <p class="text-[10px] text-gray-600">{{ $activity->time }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-gray-500">No recent results.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Upcoming -->
                <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-5 backdrop-blur-sm">
                    <h3 class="text-sm font-bold text-white mb-4">Next Fixture</h3>
                    @if($upcoming_games->count() > 0)
                        @php $game = $upcoming_games[0]; @endphp
                        <div class="bg-white/5 p-4 rounded-xl border border-white/10">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-[10px] font-bold text-[#f0c040] uppercase tracking-widest">Matchweek {{ $game->matchweek }}</span>
                                <span class="text-[10px] text-gray-500">{{ $game->kickoff->format('M d, H:i') }}</span>
                            </div>
                            <div class="flex items-center justify-between gap-2">
                                <div class="text-center flex-1">
                                    <div class="text-xs font-bold text-white truncate">{{ $game->homeTeam->team_name }}</div>
                                </div>
                                <div class="text-[10px] font-bold text-gray-600">VS</div>
                                <div class="text-center flex-1">
                                    <div class="text-xs font-bold text-white truncate">{{ $game->awayTeam->team_name }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <p class="text-xs text-gray-500">No upcoming games.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<div id="add-player-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-[#0d1117] border border-white/10 w-full max-w-md p-6 rounded-2xl shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Add New Player</h3>
            <button onclick="toggleModal('add-player-modal')" class="text-gray-500 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form action="{{ route('manager.players.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Player Name</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Position</label>
                    <select name="position" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm appearance-none">
                        <option value="GK">GK</option>
                        <option value="DEF">DEF</option>
                        <option value="MID">MID</option>
                        <option value="FWD">FWD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Squad Number</label>
                    <input type="number" name="number" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
                </div>
            </div>
            <button type="submit" class="w-full bg-[#f0c040] text-black font-bold py-3 rounded-xl mt-4 hover:bg-[#fff0a0] transition-colors uppercase text-xs tracking-widest">Register Player</button>
        </form>
    </div>
</div>

<div id="add-staff-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-[#0d1117] border border-white/10 w-full max-w-md p-6 rounded-2xl shadow-2xl">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-white">Add Staff Member</h3>
            <button onclick="toggleModal('add-staff-modal')" class="text-gray-500 hover:text-white"><i data-lucide="x" class="w-6 h-6"></i></button>
        </div>
        <form action="{{ route('manager.staff.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Full Name</label>
                <input type="text" name="name" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Role</label>
                <select name="role" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm appearance-none">
                    <option value="Head Coach">Head Coach</option>
                    <option value="Assistant Coach">Assistant Coach</option>
                    <option value="Doctor">Doctor</option>
                    <option value="Physiotherapist">Physiotherapist</option>
                    <option value="Kit Manager">Kit Manager</option>
                    <option value="Scout">Scout</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-[#f0c040] text-black font-bold py-3 rounded-xl mt-4 hover:bg-[#fff0a0] transition-colors uppercase text-xs tracking-widest">Add Member</button>
        </form>
    </div>
</div>

<div id="edit-team-modal" class="fixed inset-0 bg-black/80 backdrop-blur-md z-[100] hidden flex items-center justify-center p-4">
    <div class="bg-[#0d1117] border border-white/10 w-full max-w-lg p-6 rounded-2xl shadow-2xl">
        <h3 class="text-xl font-bold text-white mb-6">Edit Team Profile</h3>
        <form action="{{ route('teams.update', auth()->user()->team_id ?? 0) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Team Name</label>
                <input type="text" name="team_name" value="{{ auth()->user()->team?->team_name ?? '' }}" required class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Home Stadium</label>
                    <input type="text" name="home_stadium" value="{{ auth()->user()->team?->home_stadium ?? '' }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">City</label>
                    <input type="text" name="city" value="{{ auth()->user()->team?->city ?? '' }}" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1.5 tracking-wider">Description</label>
                <textarea name="description" rows="3" class="w-full bg-white/5 border border-white/10 rounded-lg px-4 py-2.5 text-white focus:border-[#f0c040] outline-none text-sm">{{ auth()->user()->team?->description ?? '' }}</textarea>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 bg-[#f0c040] text-black font-bold py-3 rounded-xl uppercase text-xs tracking-widest">Update</button>
                <button type="button" onclick="toggleModal('edit-team-modal')" class="flex-1 bg-white/5 text-gray-400 font-bold py-3 rounded-xl border border-white/10 uppercase text-xs tracking-widest">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleModal(id) {
    const modal = document.getElementById(id);
    modal.classList.toggle('hidden');
}

function switchSquadTab(tab) {
    const playersList = document.getElementById('players-list');
    const staffList = document.getElementById('staff-list');
    const playerTab = document.getElementById('tab-players');
    const staffTab = document.getElementById('tab-staff');

    if (tab === 'players') {
        playersList.classList.remove('hidden');
        staffList.classList.add('hidden');
        playerTab.classList.add('bg-[#f0c040]/20', 'text-[#f0c040]', 'border-[#f0c040]/30');
        playerTab.classList.remove('bg-white/5', 'text-gray-400', 'border-white/10');
        staffTab.classList.remove('bg-[#f0c040]/20', 'text-[#f0c040]', 'border-[#f0c040]/30');
        staffTab.classList.add('bg-white/5', 'text-gray-400', 'border-white/10');
    } else {
        staffList.classList.remove('hidden');
        playersList.classList.add('hidden');
        staffTab.classList.add('bg-[#f0c040]/20', 'text-[#f0c040]', 'border-[#f0c040]/30');
        staffTab.classList.remove('bg-white/5', 'text-gray-400', 'border-white/10');
        playerTab.classList.remove('bg-[#f0c040]/20', 'text-[#f0c040]', 'border-[#f0c040]/30');
        playerTab.classList.add('bg-white/5', 'text-gray-400', 'border-white/10');
    }
}
</script>
@endsection
