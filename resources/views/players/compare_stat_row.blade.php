@php
    $isP1Winner = false;
    $isP2Winner = false;

    $lowerIsBetter = isset($lower_is_better) && $lower_is_better;

    if ($val1 != $val2) {
        if ($lowerIsBetter) {
            $isP1Winner = $val1 < $val2;
            $isP2Winner = $val2 < $val1;
        } else {
            $isP1Winner = $val1 > $val2;
            $isP2Winner = $val2 > $val1;
        }
    }

    $pct1 = $max > 0 ? min(100, ($val1 / $max) * 100) : 0;
    $pct2 = $max > 0 ? min(100, ($val2 / $max) * 100) : 0;
@endphp

<div class="space-y-2">
    <!-- Stat Label and Values -->
    <div class="flex justify-between items-center text-xs md:text-sm font-semibold">
        <!-- Player 1 value -->
        <span class="w-1/4 text-left font-bold transition-all {{ $isP1Winner ? 'text-green-400 text-base font-black' : 'text-gray-400' }}">
            {{ $val1 }} {{ $isP1Winner ? '🏆' : '' }}
        </span>

        <!-- Center Label -->
        <span class="w-2/4 text-center text-gray-500 uppercase tracking-wider font-heading font-extrabold text-[10px] sm:text-xs">
            {{ $label }}
        </span>

        <!-- Player 2 value -->
        <span class="w-1/4 text-right font-bold transition-all {{ $isP2Winner ? 'text-green-400 text-base font-black' : 'text-gray-400' }}">
            {{ $isP2Winner ? '🏆' : '' }} {{ $val2 }}
        </span>
    </div>

    <!-- Comparative Progress Bars -->
    <div class="flex items-center gap-4 w-full">
        <!-- Player 1 Bar (runs right to left) -->
        <div class="w-1/2 h-2.5 bg-white/5 rounded-full overflow-hidden flex justify-end">
            <div class="h-full rounded-full transition-all duration-500 {{ $isP1Winner ? 'bg-gradient-to-l from-green-400 to-green-600 shadow-lg shadow-green-500/20' : 'bg-gold' }}"
                 style="width: {{ $pct1 }}%">
            </div>
        </div>

        <!-- Player 2 Bar (runs left to right) -->
        <div class="w-1/2 h-2.5 bg-white/5 rounded-full overflow-hidden flex justify-start">
            <div class="h-full rounded-full transition-all duration-500 {{ $isP2Winner ? 'bg-gradient-to-r from-green-400 to-green-600 shadow-lg shadow-green-500/20' : 'bg-accent-color' }}"
                 style="width: {{ $pct2 }}%">
            </div>
        </div>
    </div>
</div>
