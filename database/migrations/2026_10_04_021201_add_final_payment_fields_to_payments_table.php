<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('remaining_amount', 10, 2)
                ->default(0)
                ->after('prepaid_amount');

            $table->enum('final_payment_status', [
                'not_required',
                'pending',
                'verified',
                'rejected'
            ])
                ->default('not_required')
                ->after('payment_status');

            $table->string('final_slip_image')
                ->nullable()
                ->after('final_payment_status');

            $table->text('final_rejection_reason')
                ->nullable()
                ->after('final_slip_image');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'remaining_amount',
                'final_payment_status',
                'final_slip_image',
                'final_rejection_reason',
            ]);
        });
    }
};