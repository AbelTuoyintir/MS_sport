@extends('layouts.manager')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold">Reports & Exports</h2>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="glass-card p-6">
            <div class="w-12 h-12 rounded-full bg-accent-gold/20 flex items-center justify-center mb-4">
                <i data-lucide="users" class="w-6 h-6 text-accent-gold"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Squad Report</h3>
            <p class="text-sm text-gray-400 mb-6">Complete list of players with their stats, rating, and current status.</p>
            <a href="{{ route('manager.reports.export.players') }}" class="inline-flex items-center gap-2 bg-accent-gold text-bg-dark font-bold px-4 py-2 rounded-lg text-sm">
                <i data-lucide="download" class="w-4 h-4"></i> Export CSV
            </a>
        </div>

        <div class="glass-card p-6 opacity-50 cursor-not-allowed">
            <div class="w-12 h-12 rounded-full bg-blue-500/20 flex items-center justify-center mb-4">
                <i data-lucide="bar-chart-3" class="w-6 h-6 text-blue-400"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Match Analysis</h3>
            <p class="text-sm text-gray-400 mb-6">Detailed performance report for the current season's matches.</p>
            <span class="text-[10px] font-bold uppercase text-gray-500">Coming Soon</span>
        </div>

        <div class="glass-card p-6 opacity-50 cursor-not-allowed">
            <div class="w-12 h-12 rounded-full bg-green-500/20 flex items-center justify-center mb-4">
                <i data-lucide="wallet" class="w-6 h-6 text-green-400"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Financial Summary</h3>
            <p class="text-sm text-gray-400 mb-6">Annual financial statement covering salaries and expenses.</p>
            <span class="text-[10px] font-bold uppercase text-gray-500">Coming Soon</span>
        </div>
    </div>
</div>
@endsection
