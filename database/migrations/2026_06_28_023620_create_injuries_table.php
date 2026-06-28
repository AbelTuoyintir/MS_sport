<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('injuries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->string('type'); // Hamstring, Ankle, etc.
            $table->text('description')->nullable();
            $table->date('started_at');
            $table->date('expected_return_at')->nullable();
            $table->date('returned_at')->nullable();
            $table->enum('severity', ['minor', 'moderate', 'severe'])->default('moderate');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('injuries');
    }
};
