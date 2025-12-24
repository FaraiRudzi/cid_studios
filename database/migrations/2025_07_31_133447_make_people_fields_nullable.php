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
            // Check if the unique index exists before attempting to add it,
            // but since it seems to exist, we only need to change nullability.
            // REMOVED ->unique() calls to prevent duplicate key error (1061).

            $table->string('first_name')->nullable()->change();
            $table->string('surname')->nullable()->change();

            // Only change nullability, keeping the existing unique constraint
            $table->string('id_number')->nullable()->change();

            $table->text('address')->nullable()->change();
            $table->string('phone_number')->nullable()->change();

            // Only change nullability, keeping the existing unique constraint
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // NOTE: Reverting nullability requires that all existing rows
        // must have non-null values for these columns.

        Schema::table('people', function (Blueprint $table) {
            // Revert to non-nullable (or whatever the original state was)

            $table->string('first_name')->nullable(false)->change();
            $table->string('surname')->nullable(false)->change();
            $table->string('id_number')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('phone_number')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();

            // Note: Reverting to non-nullable is usually safer in a down() method,
            // but you should be sure of the original schema.
        });
    }
};
