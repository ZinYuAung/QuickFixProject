```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('reviews', 'provider_hidden_at')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->timestamp('provider_hidden_at')
                    ->nullable()
                    ->after('deleted_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reviews', 'provider_hidden_at')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('provider_hidden_at');
            });
        }
    }
};

