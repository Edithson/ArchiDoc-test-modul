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
        Schema::create('archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('archive_type_id')->nullable()->constrained('archive_types')->nullOnDelete();
            $table->string('description', 255)->nullable();
            $table->string('date_doc')->nullable();
            $table->string('emplacement')->nullable();
            $table->string('emplacement2')->nullable();
            $table->string('rayon')->nullable();
            $table->string('travee')->nullable();
            $table->string('cote')->nullable();
            $table->string('format')->nullable();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('sub_department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('piece_jointe')->nullable();
            $table->string('orientation')->nullable();
            $table->string('zip_file')->nullable();
            $table->string('filepath')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'sub_department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('archives');
    }
};
