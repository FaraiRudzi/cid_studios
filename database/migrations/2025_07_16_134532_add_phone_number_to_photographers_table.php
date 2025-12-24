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
        Schema::table('photographers', function (Blueprint $table) {
            // Check if the column already exists before attempting to add it
            if (!Schema::hasColumn('photographers', 'phone_number')) {
                $table->string('phone_number')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photographers', function (Blueprint $table) {
            // Only drop the column if it exists
            if (Schema::hasColumn('photographers', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
        });
    }
};
