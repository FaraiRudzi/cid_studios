<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('cases', function (Blueprint $table) {
        // Ensure case_type is a string (not enum) to allow manual typing
        $table->string('case_type')->change();

        // Ensure cause_of_death exists for Sudden Death/Murder cases
        if (!Schema::hasColumn('cases', 'cause_of_death')) {
            $table->string('cause_of_death')->nullable()->after('case_type');
        }

        // Ensure circumstances can hold long text for indications/photography details
        $table->text('circumstances')->change();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
