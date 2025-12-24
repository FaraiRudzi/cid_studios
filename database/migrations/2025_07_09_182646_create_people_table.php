<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name'); // Renamed 'name' to 'first_name' for clarity
            $table->string('surname');
            $table->string('id_number')->unique()->nullable(); // Added unique() constraint
            $table->string('email')->unique()->nullable(); // Added missing email column with unique()
            $table->string('phone_number')->nullable(); // Added missing phone_number column
            $table->text('address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
