<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('transfer_offers', 'offer_type')) {
                $table->string('offer_type')->default('permanent')->after('offer_amount'); // permanent, loan_half, loan_full
            }
            if (!Schema::hasColumn('transfer_offers', 'counter_amount')) {
                $table->decimal('counter_amount', 15, 2)->nullable()->after('offer_type');
            }
            if (!Schema::hasColumn('transfer_offers', 'counter_type')) {
                $table->string('counter_type')->nullable()->after('counter_amount');
            }
            if (!Schema::hasColumn('transfer_offers', 'counter_notes')) {
                $table->text('counter_notes')->nullable()->after('counter_type');
            }
            if (!Schema::hasColumn('transfer_offers', 'parent_offer_id')) {
                $table->foreignId('parent_offer_id')->nullable()->after('id')->constrained('transfer_offers')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transfer_offers', function (Blueprint $table) {
            $table->dropColumn(['offer_type', 'counter_amount', 'counter_type', 'counter_notes', 'parent_offer_id']);
        });
    }
};
