<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->enum(
                'refund_customer_response',
                [
                    'pending',
                    'received',
                    'not_received',
                ]
            )
            ->default('pending')
            ->after('refund_customer_verified_at');

        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {

            $table->dropColumn(
                'refund_customer_response'
            );

        });
    }
};