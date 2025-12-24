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
    Schema::table('media', function (Blueprint $table) {
        // We check if it exists first to prevent the "already exists" error
        if (!Schema::hasColumn('media', 'category')) {
            $table->string('category')->nullable()->after('file_name');
        }
        if (!Schema::hasColumn('media', 'description')) {
            $table->text('description')->nullable()->after('category');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            //
        });
    }
};
