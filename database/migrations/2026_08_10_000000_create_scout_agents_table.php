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
            $table->integer('experience_rating')->default(80); // 1-100 or 1-5
            $table->string('specialization')->default('General Scouting');
            $table->decimal('weekly_fee', 15, 2)->default(2500.00);
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scout_agents');
    }
};
