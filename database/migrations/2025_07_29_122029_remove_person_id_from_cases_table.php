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
        Schema::table('cases', function (Blueprint $table) {
            // Check if the column exists before trying to drop it
            if (Schema::hasColumn('cases', 'person_id')) {
                // IMPORTANT: We must drop the foreign key constraint before dropping the column.
                // Laravel 8+ uses a conventional name for the foreign key.
                $table->dropForeign(['person_id']);
                
                // Now we can safely drop the column.
                $table->dropColumn('person_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // This allows us to reverse the change if needed.
            $table->foreignId('person_id')->nullable()->constrained('people')->after('photographer_id');
        });
    }
};