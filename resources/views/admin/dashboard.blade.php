@extends('layouts.admin')
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
        
        /* Hover effects */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
        
        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #f0c040 0%, #fff0a0 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        
        /* Border gradient animation */
        @keyframes borderGlow {
            0%, 100% {
                border-color: rgba(240, 192, 64, 0.2);
            }
            50% {
                border-color: rgba(240, 192, 64, 0.5);
            }
        }
        
        .stat-card {
            animation: borderGlow 2s ease-in-out infinite;
        }
    </style>
<div class="font-sans antialiased">

    <div class="min-h-screen p-4 md:p-6 lg:p-8">
        
        <!-- Main Container -->
        <div class="max-w-7xl mx-auto">
            
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white tracking-tight">
                            Dashboard
                        </h1>
                        <p class="text-gray-400 text-sm md:text-base mt-1">
                            Season 2024/25 - Matchweek 32
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <!-- Date display -->
                        <div class="flex items-center gap-2 bg-white/5 backdrop-blur-sm px-4 py-2 rounded-lg border border-white/10">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-gray-300 text-sm">April 2025</span>
                        </div>
                        <!-- Refresh button -->
                        <button class="bg-white/5 hover:bg-white/10 transition-all duration-200 p-2 rounded-lg border border-white/10">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
                
                <!-- Total Teams Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#f0c040]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#f0c040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Total</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_teams'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-gray-400">0 changes this season</span>
                        <span class="text-[10px] text-gray-500">⚽</span>
                    </div>
                </div>

                <!-- Registered Players Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#22c55e]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Players</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_players'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-xs text-[#22c55e] font-semibold">▲ 0</span>
                        <span class="text-xs text-gray-400">this month</span>
                    </div>
                </div>

                <!-- Matches Played Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#00e5ff]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#00e5ff]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Matches</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_matches'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-xs text-[#22c55e] font-semibold">▲ 0</span>
                        <span class="text-xs text-gray-400">this week</span>
                    </div>
                </div>

                <!-- Goals Scored Card -->
                <div class="stat-card bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-[#f0c040]/20 p-5 md:p-6 backdrop-blur-sm">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-10 h-10 bg-[#ff3b3b]/10 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#ff3b3b]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <span class="text-[10px] font-semibold text-gray-500 uppercase tracking-wider bg-white/5 px-2 py-1 rounded">Goals</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-3xl md:text-4xl font-bold text-white">{{ $stats['total_goals'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-[#22c55e]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        <span class="text-xs text-[#22c55e] font-semibold">▲ 0</span>
                        <span class="text-xs text-gray-400">this week</span>
                    </div>
                </div>
            </div>

            <!-- Bottom Section: Table and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                <!-- Top Scorers Table -->
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 overflow-hidden backdrop-blur-sm">
                        <!-- Table Header -->
                        <div class="px-5 md:px-6 py-4 border-b border-white/10">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-lg md:text-xl font-bold text-white">Top Scorers</h2>
                                    <p class="text-xs text-gray-400 mt-1">Leading goal scorers this season</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="text-xs text-[#f0c040] hover:text-[#fff0a0] transition-colors font-semibold">
                                        View All →
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Table -->
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-white/10 bg-white/5">
                                        <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Player</th>
                                        <th class="text-left px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Team</th>
                                        <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Matches Played</th>
                                        <th class="text-center px-5 md:px-6 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Goals Scored</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Row 1 - E. Haaland -->
                                    <tr class="table-row border-b border-white/5 cursor-pointer">
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#f0c040] to-[#c8930a] flex items-center justify-center text-black font-bold text-sm">EH</div>
                                                <div>
                                                    <span class="font-semibold text-white text-sm">E. Haaland</span>
                                                    <span class="block text-xs text-gray-500">Forward</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#1a3cff] flex items-center justify-center text-white text-[10px] font-bold">MC</div>
                                                <span class="text-gray-300 text-sm">Man City</span>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <span class="text-white font-medium">24</span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-[#f0c040] font-bold text-lg">24</span>
                                                <span class="text-xs text-gray-500">⚽</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row 2 - O. Watkins -->
                                    <tr class="table-row border-b border-white/5 cursor-pointer">
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#6c1d45] to-[#8b2d5a] flex items-center justify-center text-white font-bold text-sm">OW</div>
                                                <div>
                                                    <span class="font-semibold text-white text-sm">O. Watkins</span>
                                                    <span class="block text-xs text-gray-500">Forward</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#6c1d45] flex items-center justify-center text-white text-[10px] font-bold">AV</div>
                                                <span class="text-gray-300 text-sm">Aston Villa</span>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <span class="text-white font-medium">28</span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-[#f0c040] font-bold text-lg">18</span>
                                                <span class="text-xs text-gray-500">⚽</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row 3 - A. Lacazette -->
                                    <tr class="table-row border-b border-white/5 cursor-pointer">
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#ef0107] to-[#ff2a2a] flex items-center justify-center text-white font-bold text-sm">AL</div>
                                                <div>
                                                    <span class="font-semibold text-white text-sm">A. Lacazette</span>
                                                    <span class="block text-xs text-gray-500">Forward</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#ef0107] flex items-center justify-center text-white text-[10px] font-bold">AR</div>
                                                <span class="text-gray-300 text-sm">Arsenal</span>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <span class="text-white font-medium">26</span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-[#f0c040] font-bold text-lg">15</span>
                                                <span class="text-xs text-gray-500">⚽</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row 4 - M. Salah -->
                                    <tr class="table-row border-b border-white/5 cursor-pointer">
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#d00027] to-[#ff2a2a] flex items-center justify-center text-white font-bold text-sm">MS</div>
                                                <div>
                                                    <span class="font-semibold text-white text-sm">M. Salah</span>
                                                    <span class="block text-xs text-gray-500">Forward</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#d00027] flex items-center justify-center text-white text-[10px] font-bold">LP</div>
                                                <span class="text-gray-300 text-sm">Liverpool</span>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <span class="text-white font-medium">25</span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-[#f0c040] font-bold text-lg">14</span>
                                                <span class="text-xs text-gray-500">⚽</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row 5 - J. Wilson -->
                                    <tr class="table-row cursor-pointer">
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-[#241f20] to-[#3a3235] flex items-center justify-center text-white font-bold text-sm">JW</div>
                                                <div>
                                                    <span class="font-semibold text-white text-sm">J. Wilson</span>
                                                    <span class="block text-xs text-gray-500">Forward</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3">
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-[#241f20] flex items-center justify-center text-white text-[10px] font-bold">NC</div>
                                                <span class="text-gray-300 text-sm">Newcastle</span>
                                            </div>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <span class="text-white font-medium">22</span>
                                        </td>
                                        <td class="px-5 md:px-6 py-3 text-center">
                                            <div class="flex items-center justify-center gap-1">
                                                <span class="text-[#f0c040] font-bold text-lg">13</span>
                                                <span class="text-xs text-gray-500">⚽</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Table Footer -->
                        <div class="px-5 md:px-6 py-3 border-t border-white/10 bg-white/5">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-gray-400">Showing 5 of 20 players</span>
                                <div class="flex gap-2">
                                    <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">←</button>
                                    <button class="px-2 py-1 rounded bg-[#f0c040]/20 text-[#f0c040] font-semibold">1</button>
                                    <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">2</button>
                                    <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">3</button>
                                    <button class="px-2 py-1 rounded bg-white/5 text-gray-400 hover:bg-white/10 transition-colors">→</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Panel -->
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 overflow-hidden backdrop-blur-sm">
                        <div class="px-5 md:px-6 py-4 border-b border-white/10">
                            <h2 class="text-lg md:text-xl font-bold text-white">Quick Actions</h2>
                            <p class="text-xs text-gray-400 mt-1">Manage your league</p>
                        </div>
                        <div class="p-5 md:p-6 space-y-4">
                            <!-- Add Team Button -->
                            <button class="action-btn w-full bg-gradient-to-r from-[#f0c040] to-[#c8930a] hover:from-[#fff0a0] hover:to-[#f0c040] text-black font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-3 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                <span class="text-sm uppercase tracking-wide">Add Team</span>
                            </button>
                            
                            <!-- Add Player Button -->
                            <button class="action-btn w-full bg-gradient-to-r from-[#00e5ff] to-[#007fa8] hover:from-[#4deaff] hover:to-[#00e5ff] text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-3 transition-all duration-200 shadow-lg hover:shadow-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <span class="text-sm uppercase tracking-wide">Add Player</span>
                            </button>

                            <!-- Divider -->
                            <div class="relative my-4">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-white/10"></div>
                                </div>
                                <div class="relative flex justify-center text-xs">
                                    <span class="px-2 bg-[#0d1117] text-gray-500">Other Actions</span>
                                </div>
                            </div>

                            <!-- Export Data Button -->
                            <button class="action-btn w-full bg-white/5 hover:bg-white/10 text-gray-300 font-semibold py-2.5 px-4 rounded-lg flex items-center justify-center gap-3 transition-all duration-200 border border-white/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                <span class="text-xs uppercase tracking-wide">Export Data</span>
                            </button>

                            <!-- Schedule Match Button -->
                            <button class="action-btn w-full bg-white/5 hover:bg-white/10 text-gray-300 font-semibold py-2.5 px-4 rounded-lg flex items-center justify-center gap-3 transition-all duration-200 border border-white/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-xs uppercase tracking-wide">Schedule Match</span>
                            </button>

                            <!-- Generate Report Button -->
                            <button class="action-btn w-full bg-white/5 hover:bg-white/10 text-gray-300 font-semibold py-2.5 px-4 rounded-lg flex items-center justify-center gap-3 transition-all duration-200 border border-white/10">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-xs uppercase tracking-wide">Generate Report</span>
                            </button>
                        </div>
                    </div>

                    <!-- Recent Activity Card -->
                    <div class="mt-6 bg-gradient-to-br from-[#0d1117] to-[#161b24] rounded-xl border border-white/10 p-5 backdrop-blur-sm">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-bold text-white">Recent Activity</h3>
                            <span class="text-[10px] text-gray-500">Last 7 days</span>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#22c55e] mt-1.5"></div>
                                <div>
                                    <p class="text-xs text-gray-300">New player registered: <span class="text-white font-medium">Marcus Rashford</span></p>
                                    <p class="text-[10px] text-gray-600">2 hours ago</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#f0c040] mt-1.5"></div>
                                <div>
                                    <p class="text-xs text-gray-300">Team registration: <span class="text-white font-medium">AFC Richmond</span></p>
                                    <p class="text-[10px] text-gray-600">Yesterday</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="w-1.5 h-1.5 rounded-full bg-[#00e5ff] mt-1.5"></div>
                                <div>
                                    <p class="text-xs text-gray-300">Match result: Man City 3 - 1 Arsenal</p>
                                    <p class="text-[10px] text-gray-600">2 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

