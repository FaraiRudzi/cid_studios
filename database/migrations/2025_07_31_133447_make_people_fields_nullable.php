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
            // Change all relevant columns to be nullable.
            $table->string('first_name')->nullable()->change();
            $table->string('surname')->nullable()->change();
            $table->string('id_number')->unique()->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('phone_number')->nullable()->change();
            $table->string('email')->unique()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('people', function (Blueprint $table) {
            // This is not perfectly reversible, as we don't know the original state.
            // We'll revert to a sensible default (not nullable).
            $table->string('first_name')->nullable(false)->change();
            $table->string('surname')->nullable(false)->change();
        });
    }
};

