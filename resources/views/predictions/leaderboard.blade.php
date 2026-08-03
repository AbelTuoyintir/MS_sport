<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prediction Leaderboard — MP League</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Barlow+Condensed:wght@400;700;900&family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Barlow', sans-serif; background: #06090e; color: #e8edf4; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .font-heading { font-family: 'Barlow Condensed', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 1rem; }
        .accent-gold { color: #f0c040; }
        .bg-gold { background-color: #f0c040; }
    </style>
</head>
<body class="p-4 md:p-8">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('home') }}" class="accent-gold hover:underline">← Back to Home</a>
            <h1 class="font-display text-5xl md:text-6xl tracking-tight">Prediction Leaderboard</h1>
        </div>

        <div class="glass-card p-6 mb-8 text-center md:text-left bg-gradient-to-r from-amber-500/10 to-yellow-500/10">
            <h2 class="font-display text-3xl mb-2 text-gold">🎮 Fan Prediction Championship</h2>
            <p class="text-sm text-gray-400">
                Predict the scores of upcoming matches to earn points! <strong>3 points</strong> for a perfect exact score prediction, and <strong>1 point</strong> for predicting the correct outcome (winner or draw).
            </p>
        </div>

        <div class="glass-card overflow-hidden">
            <div class="table-wrapper overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="text-[10px] sm:text-xs font-bold tracking-[0.8px] text-gray-500 uppercase border-b border-white/10">
                            <th class="px-4 py-3 text-center">Rank</th>
                            <th class="px-4 py-3 text-left">Predictor</th>
                            <th class="px-4 py-3 text-center">Predictions</th>
                            <th class="px-4 py-3 text-center">Exact Scores (3pts)</th>
                            <th class="px-4 py-3 text-center">Correct Outcome (1pt)</th>
                            <th class="px-4 py-3 text-right">Total Points</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaderboard as $index => $row)
                            <tr class="hover:bg-white/5 transition-colors border-b border-white/5 last:border-0">
                                <td class="px-4 py-3 text-center">
                                    <span class="font-display text-2xl {{ $index < 3 ? 'accent-gold' : 'text-gray-500' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-left font-bold text-base sm:text-lg">
                                    {{ $row['user_name'] }}
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-gray-400">
                                    {{ $row['total_predictions'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-green-400 font-semibold">
                                    {{ $row['exact_scores'] }}
                                </td>
                                <td class="px-4 py-3 text-center text-blue-400 font-semibold">
                                    {{ $row['correct_outcomes'] }}
                                </td>
                                <td class="px-4 py-3 text-right font-display text-3xl accent-gold">
                                    {{ $row['points'] }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-8 text-center text-gray-500">
                                    No predictions calculated yet. Start predicting upcoming games to show up on the leaderboard!
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
