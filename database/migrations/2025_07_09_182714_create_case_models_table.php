<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('station_id')->constrained()->onDelete('cascade');
            $table->foreignId('person_id')->constrained()->onDelete('cascade');
            $table->foreignId('photographer_id')->constrained()->onDelete('cascade');
            $table->string('reference_number')->unique();
            $table->string('case_type'); // e.g. Murder, Sudden Death
            $table->text('circumstances');
            $table->string('cause_of_death')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
