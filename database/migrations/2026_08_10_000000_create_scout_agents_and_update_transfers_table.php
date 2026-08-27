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
            $table->string('specialization')->default('Global Scouting');
            $table->string('nationality')->nullable();
            $table->integer('experience_rating')->default(75);
            $table->decimal('weekly_fee', 15, 2)->default(500.00);
            $table->foreignId('team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->enum('status', ['available', 'hired'])->default('available');
            $table->timestamps();
        });

        Schema::table('transfer_listings', function (Blueprint $table) {
            $table->string('deal_type')->default('permanent'); // permanent, loan_half, loan_full
            $table->foreignId('scout_agent_id')->nullable()->constrained('scout_agents')->onDelete('set null');
        });

        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->string('deal_type')->default('permanent'); // permanent, loan_half, loan_full
            $table->foreignId('parent_offer_id')->nullable()->constrained('transfer_offers')->onDelete('cascade');
            $table->decimal('counter_amount', 15, 2)->nullable();
            $table->text('counter_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->dropForeign(['parent_offer_id']);
            $table->dropColumn(['deal_type', 'parent_offer_id', 'counter_amount', 'counter_notes']);
        });

        Schema::table('transfer_listings', function (Blueprint $table) {
            $table->dropForeign(['scout_agent_id']);
            $table->dropColumn(['deal_type', 'scout_agent_id']);
        });

        Schema::dropIfExists('scout_agents');
    }
};
