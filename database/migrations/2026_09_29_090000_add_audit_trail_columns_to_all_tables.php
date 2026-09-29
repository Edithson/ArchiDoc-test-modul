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
        $tablesConfig = [
            'users' => ['created_by', 'updated_by', 'deleted_by'],
            'departments' => ['created_by', 'updated_by', 'deleted_by'],
            'roles' => ['created_by', 'updated_by'],
            'archive_types' => ['updated_by', 'deleted_by'],
            'archive_locations' => ['updated_by', 'deleted_by'],
            'archives' => ['created_by', 'updated_by', 'deleted_by', 'soft_deletes'],
            'personnels' => ['created_by', 'updated_by', 'deleted_by'],
            'pieces' => ['created_by', 'updated_by', 'deleted_by'],
            'personnel_files' => ['created_by', 'updated_by', 'deleted_by', 'soft_deletes'],
        ];

        foreach ($tablesConfig as $tableName => $columns) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                if (in_array('soft_deletes', $columns, true) && ! Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->softDeletes();
                }

                if (in_array('created_by', $columns, true) && ! Schema::hasColumn($tableName, 'created_by')) {
                    $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
                }

                if (in_array('updated_by', $columns, true) && ! Schema::hasColumn($tableName, 'updated_by')) {
                    $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
                }

                if (in_array('deleted_by', $columns, true) && ! Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tablesConfig = [
            'users' => ['created_by', 'updated_by', 'deleted_by'],
            'departments' => ['created_by', 'updated_by', 'deleted_by'],
            'roles' => ['created_by', 'updated_by'],
            'archive_types' => ['updated_by', 'deleted_by'],
            'archive_locations' => ['updated_by', 'deleted_by'],
            'archives' => ['created_by', 'updated_by', 'deleted_by', 'soft_deletes'],
            'personnels' => ['created_by', 'updated_by', 'deleted_by'],
            'pieces' => ['created_by', 'updated_by', 'deleted_by'],
            'personnel_files' => ['created_by', 'updated_by', 'deleted_by', 'soft_deletes'],
        ];

        foreach ($tablesConfig as $tableName => $columns) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName, $columns) {
                if (in_array('created_by', $columns, true) && Schema::hasColumn($tableName, 'created_by')) {
                    $table->dropForeign([$tableName.'_created_by_foreign']);
                    $table->dropColumn('created_by');
                }
                if (in_array('updated_by', $columns, true) && Schema::hasColumn($tableName, 'updated_by')) {
                    $table->dropForeign([$tableName.'_updated_by_foreign']);
                    $table->dropColumn('updated_by');
                }
                if (in_array('deleted_by', $columns, true) && Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->dropForeign([$tableName.'_deleted_by_foreign']);
                    $table->dropColumn('deleted_by');
                }
                if (in_array('soft_deletes', $columns, true) && Schema::hasColumn($tableName, 'deleted_at')) {
                    $table->dropSoftDeletes();
                }
            });
        }
    }
};
