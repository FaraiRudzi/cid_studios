<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  // In the ...update_people_table_for_detailed_fields.php migration file

public function up(): void
{
    Schema::table('people', function (Blueprint $table) {
        // Rename the 'name' column to 'first_name'
        $table->renameColumn('name', 'first_name');

        // Add the new columns
        $table->string('phone_number')->nullable()->after('address');
        $table->string('email')->unique()->nullable()->after('phone_number');
    });
}

public function down(): void
{
    Schema::table('people', function (Blueprint $table) {
        // Reverse the changes if we roll back
        $table->renameColumn('first_name', 'name');
        $table->dropColumn(['phone_number', 'email']);
    });
}
};
