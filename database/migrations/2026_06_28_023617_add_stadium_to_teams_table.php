<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->string('home_stadium')->nullable();
            $table->text('description')->nullable();
            $table->string('city')->nullable();
            $table->string('founded_year')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['home_stadium', 'description', 'city', 'founded_year']);
        });
    }
};
