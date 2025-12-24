<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  // In the ...add_scene_reference_to_cases_table.php migration file

public function up(): void
{
    Schema::table('cases', function (Blueprint $table) {
        $table->string('scene_reference_number')->unique()->nullable()->after('id');
    });
}

public function down(): void
{
    Schema::table('cases', function (Blueprint $table) {
        $table->dropColumn('scene_reference_number');
    });
}
};
