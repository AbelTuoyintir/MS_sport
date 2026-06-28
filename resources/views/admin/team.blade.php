@extends('layouts.admin')
@section('content')
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'gold': '#f0c040',
                        'gold2': '#c8930a',
                        'gold3': '#fff0a0',
                        'accent': '#00e5ff',
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
                        'sans': ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #0a0f1a 0%, #0d1117 100%);
        }
        
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
        
        /* Hover effects */
        .stat-card, .squad-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .stat-card:hover, .squad-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px rgba(0, 0, 0, 0.3);
        }
        
        .team-row {
            transition: background-color 0.2s ease;
        }
        
        .team-row:hover {
            background-color: rgba(240, 192, 64, 0.05);
        }
        
        .action-btn {
            transition: all 0.2s ease;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
        }
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #f0c040 0%, #fff0a0 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Club badge animations */
        @keyframes badgePulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .club-badge {
            transition: all 0.2s ease;
        }
        
        .team-row:hover .club-badge {
            animation: badgePulse 0.3s ease;
        }
        
        /* Table container for responsive scrolling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Filter tabs */
        .filter-tab {
            transition: all 0.2s ease;
        }
        
        .filter-tab:hover {
            background-color: rgba(240, 192, 64, 0.1);
            color: #f0c040;
        }
    </style>
</head>
<body class="font-sans antialiased">

    <div class="min-h-screen p-4 md:p-6 lg:p-8">
        
        <!-- Main Container -->
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white tracking-tight">
                            Teams
                        </h1>
                        <p class="text-gray-400 text-sm md:text-base mt-1">
                            Manage all 20 clubs - Season 2024/25
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Season selector -->
                        <div class="flex items-center gap-2 bg-white/5 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/10">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="text-gray-300 text-sm">2024/25</span>
                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                        <!-- Add Team Button -->
                        <button class="bg-gradient-to-r from-[#f0c040] to-[#c8930a] hover:from-[#fff0a0] hover:to-[#f0c040] text-black font-semibold px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200 shadow-lg hover:shadow-xl text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Club
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid - First Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                
                <!-- Total Teams Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#f0c040]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#f0c040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Total Clubs</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">20</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">No changes</span>
                        <span class="text-[10px] text-gray-500">🏆</span>
                    </div>
                </div>

                <!-- Avg Squad Size Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#22c55e]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#22c55e]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Avg Squad Size</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">24</span>
                        <span class="text-sm text-gray-400 ml-1">Players/team</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-full bg-white/10 rounded-full h-1.5">
                            <div class="bg-[#22c55e] h-1.5 rounded-full" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- Suspensions Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#ff3b3b]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#ff3b3b]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#ff3b3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Suspensions</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">7</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-[#ff3b3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                        <span class="text-xs text-[#ff3b3b] font-semibold">▼ 2 cleared</span>
                        <span class="text-xs text-gray-400">this week</span>
                    </div>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2 border-b border-border-dark pb-3">
                    <button class="filter-tab px-4 py-1.5 rounded-lg bg-[#f0c040]/20 text-[#f0c040] font-semibold text-sm">All Clubs</button>
                    <button class="filter-tab px-4 py-1.5 rounded-lg text-gray-400 hover:text-[#f0c040] text-sm">Premier Division</button>
                    <button class="filter-tab px-4 py-1.5 rounded-lg text-gray-400 hover:text-[#f0c040] text-sm">Division 1</button>
                    <button class="filter-tab px-4 py-1.5 rounded-lg text-gray-400 hover:text-[#f0c040] text-sm">Division 2</button>
                    <button class="filter-tab px-4 py-1.5 rounded-lg text-gray-400 hover:text-[#f0c040] text-sm">Amateur League</button>
                </div>
            </div>

            <!-- Teams Table Section -->
            <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 overflow-hidden backdrop-blur-sm mb-8">
                <div class="px-5 md:px-6 py-4 border-b border-white/10">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <h2 class="text-lg md:text-xl font-bold text-white">All Clubs — Season 2024/25</h2>
                            <p class="text-xs text-gray-400 mt-1">Complete list of registered teams and their managers</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="relative">
                                <input type="text" placeholder="Search clubs..." class="bg-white/5 border border-white/10 rounded-lg px-3 py-1.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#f0c040]/50 transition-colors">
                                <svg class="absolute right-3 top-2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button class="text-xs text-[#f0c040] hover:text-[#fff0a0] transition-colors font-semibold flex items-center gap-1">
                                Export List
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-container">
                    <table class="w-full min-w-[800px]">
                        <thead>
                            <tr class="border-b border-white/10 bg-white/5">
                                <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider w-16">Club</th>
                                <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Home Team</th>
                                <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Away Team</th>
                                <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider w-20">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 - Man City -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#1a3cff] flex items-center justify-center text-white font-bold text-xs">MC</div>
                                        <span class="font-semibold text-white text-sm">Man City</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">P. Guardiola</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">M. Arteta</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 2 - Liverpool -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#d00027] flex items-center justify-center text-white font-bold text-xs">LIV</div>
                                        <span class="font-semibold text-white text-sm">Liverpool</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">J. Klopp</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">U. Emery</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 3 - Tottenham -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#132257] flex items-center justify-center text-white font-bold text-xs">TOT</div>
                                        <span class="font-semibold text-white text-sm">Tottenham</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">A. Postecoglou</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">M. Pochettino</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 4 - Chelsea -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#034694] flex items-center justify-center text-white font-bold text-xs">CHE</div>
                                        <span class="font-semibold text-white text-sm">Chelsea</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">M. Pochettino</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">E. Howe</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 5 - Brighton -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#0057b8] flex items-center justify-center text-white font-bold text-xs">BHA</div>
                                        <span class="font-semibold text-white text-sm">Brighton</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">R. De Zerbi</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">D. Moyes</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 6 - Crystal Palace -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#1b458f] flex items-center justify-center text-white font-bold text-xs">CRY</div>
                                        <span class="font-semibold text-white text-sm">Crystal Palace</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">O. Glasner</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">M. Silva</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 7 - Newcastle -->
                            <tr class="team-row border-b border-white/5 cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#241f20] flex items-center justify-center text-white font-bold text-xs">NEW</div>
                                        <span class="font-semibold text-white text-sm">Newcastle</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">E. Howe</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">E. Ten Hag</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            
                            <!-- Row 8 - Wolves -->
                            <tr class="team-row cursor-pointer">
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="club-badge w-8 h-8 rounded-full bg-[#fdb913] flex items-center justify-center text-black font-bold text-xs">WOL</div>
                                        <span class="font-semibold text-white text-sm">Wolves</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-gray-300 text-sm">G. O'Neil</span>
                                        <span class="text-[10px] text-gray-500">(Home)</span>
                                    </div>
                                </td>
                                <td class="px-5 md:px-6 py-3">
                                    <span class="text-gray-300 text-sm">S. Dyche</span>
                                </td>
                                <td class="px-5 md:px-6 py-3 text-center">
                                    <button class="text-gray-500 hover:text-[#f0c040] transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer with Pagination -->
                <div class="px-5 md:px-6 py-3 border-t border-white/10 bg-white/5">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <span class="text-gray-400">Showing 8 of 20 clubs</span>
                        <div class="flex gap-2">
                            <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">←</button>
                            <button class="px-2 py-1 rounded bg-[#f0c040]/20 text-[#f0c040] font-semibold">1</button>
                            <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">2</button>
                            <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">3</button>
                            <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">→</button>
                        </div>
                        <div class="flex items-center gap-3">
                            <select class="bg-white/5 border border-white/10 rounded px-2 py-1 text-gray-300 text-xs">
                                <option>10 per page</option>
                                <option>20 per page</option>
                                <option>50 per page</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Squad Size Distribution Card -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="squad-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-5 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-white">Squad Size Distribution</h3>
                        <span class="text-[10px] text-gray-500">Season 2024/25</span>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">20-22 players</span>
                                <span class="text-white font-semibold">4 clubs</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="bg-[#f0c040] h-2 rounded-full" style="width: 20%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">23-25 players</span>
                                <span class="text-white font-semibold">12 clubs</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="bg-[#00e5ff] h-2 rounded-full" style="width: 60%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs mb-1">
                                <span class="text-gray-400">26-30 players</span>
                                <span class="text-white font-semibold">4 clubs</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2">
                                <div class="bg-[#22c55e] h-2 rounded-full" style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="squad-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-5 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-white">Recent Team Updates</h3>
                        <span class="text-[10px] text-gray-500">Last 30 days</span>
                    </div>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#22c55e] mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-300"><span class="text-white font-medium">AFC Richmond</span> completed registration</p>
                                <p class="text-[10px] text-gray-600">3 days ago</p>
                            </div>
                            <span class="text-[10px] text-gray-500">New</span>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#f0c040] mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-300"><span class="text-white font-medium">Man City</span> updated squad (3 players added)</p>
                                <p class="text-[10px] text-gray-600">1 week ago</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="w-1.5 h-1.5 rounded-full bg-[#00e5ff] mt-1.5"></div>
                            <div class="flex-1">
                                <p class="text-xs text-gray-300"><span class="text-white font-medium">Liverpool</span> management change confirmed</p>
                                <p class="text-[10px] text-gray-600">2 weeks ago</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection-=