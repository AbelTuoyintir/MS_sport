<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->enum('offer_type', ['permanent', 'loan_half', 'loan_full'])->default('permanent')->after('selling_team_id');
            $table->foreignId('parent_offer_id')->nullable()->constrained('transfer_offers')->onDelete('cascade')->after('status');
            $table->decimal('counter_amount', 15, 2)->nullable()->after('parent_offer_id');
            $table->text('counter_notes')->nullable()->after('counter_amount');
        });

        Schema::table('transfer_listings', function (Blueprint $table) {
            $table->enum('type', ['permanent', 'loan_half', 'loan_full'])->default('permanent')->change();
        });
    }

    public function down(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->dropForeign(['parent_offer_id']);
            $table->dropColumn(['offer_type', 'parent_offer_id', 'counter_amount', 'counter_notes']);
        });

        Schema::table('transfer_listings', function (Blueprint $table) {
            $table->enum('type', ['permanent', 'loan'])->default('permanent')->change();
        });
    }
};
