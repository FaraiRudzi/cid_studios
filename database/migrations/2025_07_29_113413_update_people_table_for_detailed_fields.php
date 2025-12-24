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
        Schema::table('people', function (Blueprint $table) {
            // RENAME COLUMN: Only rename 'name' if it exists to 'first_name'
            // If the column is already 'first_name' (due to a prior migration fix), this is skipped.
            if (Schema::hasColumn('people', 'name') && !Schema::hasColumn('people', 'first_name')) {
                $table->renameColumn('name', 'first_name');
            }

            // ADD COLUMNS: Only add if they don't already exist
            if (!Schema::hasColumn('people', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('address');
            }

            // Note: Removed ->unique() here as it causes errors if run twice.
            // We assume unique constraints are in the initial 'create_people_table'
            if (!Schema::hasColumn('people', 'email')) {
                $table->string('email')->nullable()->after('phone_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // Reverse the column rename if 'first_name' exists and 'name' does not
            if (Schema::hasColumn('people', 'first_name') && !Schema::hasColumn('people', 'name')) {
                 $table->renameColumn('first_name', 'name');
            }

            // Reverse the column additions
            if (Schema::hasColumn('people', 'phone_number')) {
                $table->dropColumn('phone_number');
            }
            if (Schema::hasColumn('people', 'email')) {
                $table->dropColumn('email');
            }
        });
    }
};
