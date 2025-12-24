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
        Schema::create('case_person', function (Blueprint $table) {
            // Pivot table schema
            $table->foreignId('case_id')->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->string('role'); // e.g., deceased, accused, informant, complainant
            $table->timestamps();

            // Ensures a person can only have one specific role per case
            $table->unique(['case_id', 'person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_person');
    }
};
