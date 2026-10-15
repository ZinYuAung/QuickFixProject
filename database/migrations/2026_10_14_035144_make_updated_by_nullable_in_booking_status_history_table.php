
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
        });

        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->foreignId('updated_by')
                ->nullable()
                ->change();
        });

        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->dropForeign(['updated_by']);
        });

        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->unsignedBigInteger('updated_by')
                ->nullable(false)
                ->change();
        });

        Schema::table('booking_status_history', function (Blueprint $table) {
            $table->foreign('updated_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }
};
