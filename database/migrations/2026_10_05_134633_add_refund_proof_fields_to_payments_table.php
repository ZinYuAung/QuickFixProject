<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->string('refund_slip_image')
                ->nullable()
                ->after('refund_reason');

            $table->timestamp('refund_submitted_at')
                ->nullable()
                ->after('refund_slip_image');

            $table->timestamp('refund_customer_verified_at')
                ->nullable()
                ->after('refund_submitted_at');

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn([
                'refund_slip_image',
                'refund_submitted_at',
                'refund_customer_verified_at',
            ]);

        });
    }
};
