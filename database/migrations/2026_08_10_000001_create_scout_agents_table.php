<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scout_agents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialization')->default('General Scouting');
            $table->integer('experience_rating')->default(75);
            $table->decimal('weekly_fee', 12, 2)->default(1500.00);
            $table->string('nationality')->default('🇬🇭');
            $table->enum('status', ['available', 'hired'])->default('available');
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_agents');
    }
};
