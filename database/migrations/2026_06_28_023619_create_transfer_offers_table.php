<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transfer_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->nullable()->constrained('transfer_listings')->onDelete('cascade');
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('buying_team_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('selling_team_id')->constrained('teams')->onDelete('cascade');
            $table->decimal('offer_amount', 15, 2);
            $table->integer('proposed_contract_years')->nullable();
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['pending', 'accepted', 'rejected', 'withdrawn', 'countered'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_offers');
    }
};
