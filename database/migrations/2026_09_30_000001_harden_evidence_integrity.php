<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * [table, column, referenced table] - every foreign key that could delete or detach evidence,
     * switched to RESTRICT. The repository's create_studios_tables migration used ON DELETE CASCADE for
     * cases.station_id and cases.photographer_id, so a fresh install would lose every case when a
     * station or user was deleted. The live database already refuses this; this makes both agree.
     */
    private array $restricted = [
        ['cases', 'station_id', 'stations'],
        ['cases', 'photographer_id', 'users'],
        ['case_logs', 'case_id', 'cases'],
        ['case_logs', 'user_id', 'users'],
        ['media', 'case_id', 'cases'],
        ['case_person', 'case_id', 'cases'],
        ['case_person', 'person_id', 'people'],
    ];

    public function up(): void
    {
        // A multi-file record stores a JSON array of paths; varchar(500) truncates it.
        Schema::table('media', function (Blueprint $table) {
            $table->text('file_path')->nullable(false)->change();
        });

        if (! Schema::hasColumn('media', 'file_hashes')) {
            Schema::table('media', function (Blueprint $table) {
                $table->json('file_hashes')->nullable();
                $table->timestamp('removed_at')->nullable();
                $table->unsignedBigInteger('removed_by')->nullable();
                $table->text('removal_reason')->nullable();
            });

            if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                Schema::table('media', function (Blueprint $table) {
                    $table->foreign('removed_by')->references('id')->on('users')->restrictOnDelete();
                });
            }
        }

        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
            // Live database already has ARCHIVED; fresh installs from migrations did not.
            DB::statement("ALTER TABLE cases MODIFY status ENUM('OPEN','PENDING_REVIEW','CLOSED','ARCHIVED') NOT NULL DEFAULT 'OPEN'");

            foreach ($this->restricted as [$table, $column, $references]) {
                foreach (Schema::getForeignKeys($table) as $foreignKey) {
                    if (($foreignKey['columns'] ?? []) === [$column]) {
                        Schema::table($table, fn (Blueprint $t) => $t->dropForeign($foreignKey['name']));
                    }
                }

                Schema::table($table, function (Blueprint $t) use ($column, $references) {
                    $t->foreign($column)->references('id')->on($references)->restrictOnDelete();
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            if (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)) {
                $table->dropForeign(['removed_by']);
            }

            $table->dropColumn(['file_hashes', 'removed_at', 'removed_by', 'removal_reason']);
        });
        // Foreign keys are intentionally left as RESTRICT: reverting to CASCADE would
        // re-enable silent deletion of evidence and audit history.
    }
};
