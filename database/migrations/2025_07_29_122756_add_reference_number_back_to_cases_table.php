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
            // Check if the column already exists to prevent the 1060 Duplicate error
            if (!Schema::hasColumn('cases', 'reference_number')) {
                // Add the column back, ensuring it is unique and cannot be null.
                $table->string('reference_number')->unique()->after('scene_reference_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cases', function (Blueprint $table) {
            // Use hasColumn() here as well for safe rollback
            if (Schema::hasColumn('cases', 'reference_number')) {
                 $table->dropUnique(['reference_number']); // Drop unique index first
                 $table->dropColumn('reference_number');
            }
        });
    }
};
