<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       DB::statement("
            ALTER TABLE booking_status_history
            MODIFY status ENUM(
                'pending',
                'accepted',
                'on_the_way',
                'arrived',
                'in_progress',
                'completed',
                'cancelled',
                'timeout'
            ) NOT NULL DEFAULT 'pending'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       DB::statement("
            ALTER TABLE booking_status_history
            MODIFY status ENUM(
                'pending',
                'accepted',
                'on_the_way',
                'arrived',
                'in_progress',
                'completed',
                'canceled',
                'timeout'
            ) NOT NULL DEFAULT 'pending'
        ");
    }
};
