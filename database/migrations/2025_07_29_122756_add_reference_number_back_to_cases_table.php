<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // In the ...add_reference_number_back_to_cases_table.php migration file

public function up(): void
{
    Schema::table('cases', function (Blueprint $table) {
        // Add the column back, ensuring it is unique and cannot be null.
        $table->string('reference_number')->unique()->after('scene_reference_number');
    });
}

public function down(): void
{
    Schema::table('cases', function (Blueprint $table) {
        $table->dropColumn('reference_number');
    });
}
};
