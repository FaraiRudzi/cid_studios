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
    Schema::create('case_logs', function (Blueprint $table) {
        $table->id();
        // Links the log to the specific case
        $table->foreignId('case_id')->constrained()->onDelete('cascade');

        // Links to the user (Admin or Photographer) who performed the action
        $table->foreignId('user_id')->constrained();

        $table->string('action');      // e.g., 'CREATED', 'REASSIGNED', 'UPLOADED', 'EVOLVED'
        $table->string('role');        // 'ADMIN' or 'PHOTOGRAPHER'
        $table->text('description');   // Human-readable summary

        // metadata stores technical details (like old_value vs new_value)
        $table->json('metadata')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_logs');
    }
};
