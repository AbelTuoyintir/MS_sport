<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->integer('appearances')->default(0);
            $table->integer('minutes_played')->default(0);
            $table->integer('yellow_cards')->default(0);
            $table->integer('red_cards')->default(0);
            $table->integer('clean_sheets')->default(0);
            $table->integer('saves')->default(0);
            $table->integer('penalties_scored')->default(0);
            $table->integer('penalties_missed')->default(0);
            $table->integer('motm_awards')->default(0);
            $table->string('photo_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn([
                'appearances', 'minutes_played', 'yellow_cards', 'red_cards',
                'clean_sheets', 'saves', 'penalties_scored', 'penalties_missed', 'motm_awards', 'photo_path'
            ]);
        });
    }
};
