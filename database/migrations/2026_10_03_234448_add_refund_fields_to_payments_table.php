<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->enum('refund_status', [
                'not_required',
                'pending',
                'refunded'
            ])->default('not_required')->after('rejection_reason');

            $table->decimal('refunded_amount', 10, 2)
                ->default(0)
                ->after('refund_status');

            $table->timestamp('refunded_at')
                ->nullable()
                ->after('refunded_amount');

            $table->text('refund_reason')
                ->nullable()
                ->after('refunded_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'refund_status',
                'refunded_amount',
                'refunded_at',
                'refund_reason',
            ]);
        });
    }
};