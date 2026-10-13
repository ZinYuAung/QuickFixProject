<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('cancelled_by')->nullable()->after('status');

            $table->text('cancellation_reason')
                ->nullable()
                ->after('cancelled_by');

            $table->timestamp('cancelled_at')
                ->nullable()
                ->after('cancellation_reason');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancelled_by',
                'cancellation_reason',
                'cancelled_at',
            ]);
        });
    }
};