<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->string('province')->nullable();
            $table->timestamps();
        });

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('force_number')->nullable()->unique();
                $table->string('first_name');
                $table->string('surname');
                $table->string('email')->unique();
                $table->string('phone_number')->nullable();
                $table->enum('role', ['ADMIN', 'PHOTOGRAPHER'])->default('PHOTOGRAPHER');
                $table->string('password');
                $table->boolean('is_active')->default(true);
                $table->rememberToken();
                $table->timestamps();
            });
        }

        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('scene_reference_number')->unique();
            $table->string('reference_number'); // Manual CR Number
            $table->foreignId('station_id')->constrained('stations')->onDelete('cascade');
            $table->foreignId('photographer_id')->constrained('users')->onDelete('cascade');
            $table->string('case_type');
            $table->longText('circumstances')->nullable();
            $table->string('cause_of_death')->nullable();
            $table->enum('status', ['OPEN', 'PENDING_REVIEW', 'CLOSED'])->default('OPEN');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('surname')->nullable();
            $table->string('id_number')->nullable();
            $table->enum('gender', ['MALE', 'FEMALE', 'UNKNOWN'])->default('UNKNOWN');
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('case_person', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->enum('role', ['informant', 'deceased', 'accused', 'complainant', 'witness', 'other']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('title');
            $table->string('category')->default('General Scene');
            $table->string('file_path', 500);
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('case_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_id')->constrained('cases')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action');
            $table->string('role');
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('case_logs');
        Schema::dropIfExists('media');
        Schema::dropIfExists('case_person');
        Schema::dropIfExists('people');
        Schema::dropIfExists('cases');
        Schema::dropIfExists('users');
        Schema::dropIfExists('stations');
    }
};
