<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('provider_id')->nullable()->constrained('providers')->nullOnDelete();
            $table->foreignId('area_id')->constrained('service_areas')->onDelete('cascade');
            $table->enum('booking_type', ['asap', 'scheduled'])->default('scheduled');
            $table->timestamp('scheduled_at');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable(); // Nullable Latitude
            $table->decimal('longitude', 11, 8)->nullable(); // Nullable Longitude
            $table->json('chosen_services'); // Multi-service array / JSON
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('response_deadline')->nullable();
            $table->enum('status', ['pending', 'accepted', 'on_the_way', 'arrived', 'in_progress', 'completed', 'cancelled', 'timeout'])->default('pending');
            $table->text('customer_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
