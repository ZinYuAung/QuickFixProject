<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Add the columns first as nullable.
        // Existing review records do not have these values yet.
        Schema::table('reviews', function (Blueprint $table) {

            if (!Schema::hasColumn('reviews', 'customer_id')) {
                $table->foreignId('customer_id')
                    ->nullable()
                    ->after('booking_id');
            }

            if (!Schema::hasColumn('reviews', 'provider_id')) {
                $table->foreignId('provider_id')
                    ->nullable()
                    ->after('customer_id');
            }
        });

        // Step 2: Get customer/provider from the existing booking.
        DB::statement("
            UPDATE reviews
            INNER JOIN bookings
                ON bookings.id = reviews.booking_id
            SET
                reviews.customer_id = bookings.customer_id,
                reviews.provider_id = bookings.provider_id
            WHERE
                reviews.customer_id IS NULL
                OR reviews.provider_id IS NULL
        ");

        // Step 3: Add foreign keys after the existing data is filled.
        Schema::table('reviews', function (Blueprint $table) {

            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->cascadeOnDelete();

            $table->foreign('provider_id')
                ->references('id')
                ->on('providers')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {

            $table->dropForeign(['customer_id']);
            $table->dropForeign(['provider_id']);

            $table->dropColumn([
                'customer_id',
                'provider_id',
            ]);
        });
    }
};