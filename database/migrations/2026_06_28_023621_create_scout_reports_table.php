<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scout_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade'); // The team doing the scouting
            $table->string('player_name');
            $table->string('current_club')->nullable();
            $table->string('position')->nullable();
            $table->integer('age')->nullable();
            $table->integer('rating')->default(1); // 1-5
            $table->text('strengths')->nullable();
            $table->text('weaknesses')->nullable();
            $table->text('summary')->nullable();
            $table->enum('status', ['shortlisted', 'trial', 'monitoring', 'ignored'])->default('shortlisted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_reports');
    }
};
