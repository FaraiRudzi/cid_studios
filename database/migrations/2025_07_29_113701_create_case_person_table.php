<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   // In the ...create_case_person_table.php migration file

public function up(): void
{
    Schema::create('case_person', function (Blueprint $table) {
        $table->id();
        $table->foreignId('case_id')->constrained()->onDelete('cascade');
        $table->foreignId('person_id')->constrained()->onDelete('cascade');
        $table->string('role'); // e.g., 'deceased', 'accused', 'informant', 'complainant'
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('case_person');
}
};
