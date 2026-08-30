<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_listings', function (Blueprint $table) {
            // Modify type enum or add loan duration types
            $table->string('type')->default('permanent')->change();
        });

        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->string('offer_type')->default('permanent'); // permanent, loan_half, loan_full
            $table->foreignId('parent_offer_id')->nullable()->constrained('transfer_offers')->onDelete('cascade');
            $table->decimal('counter_amount', 15, 2)->nullable();
            $table->text('counter_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->dropForeign(['parent_offer_id']);
            $table->dropColumn(['offer_type', 'parent_offer_id', 'counter_amount', 'counter_notes']);
        });
    }
};
