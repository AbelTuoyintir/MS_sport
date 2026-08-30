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
            $table->integer('experience_rating')->default(3); // 1-5 scale
            $table->string('specialization')->default('General Scouting');
            $table->decimal('weekly_fee', 12, 2)->default(500.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('scout_agent_team', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scout_agent_id')->constrained('scout_agents')->onDelete('cascade');
            $table->foreignId('team_id')->constrained('teams')->onDelete('cascade');
            $table->timestamp('signed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_agent_team');
        Schema::dropIfExists('scout_agents');
    }
};
