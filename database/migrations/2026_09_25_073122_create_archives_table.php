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
            $table->string('typearchive')->nullable();
            $table->string('description', 255)->nullable();
            $table->string('date_doc')->nullable();
            $table->string('emplacement')->nullable();
            $table->string('emplacement2')->nullable();
            $table->string('rayon')->nullable();
            $table->string('travee')->nullable();
            $table->string('cote')->nullable();
            $table->string('format')->nullable();
            $table->string('departement')->nullable();
            $table->string('piece_jointe')->nullable();
            $table->string('orientation')->nullable();
            $table->string('zip_file')->nullable();
            $table->string('filepath')->nullable();
            $table->unsignedTinyInteger('user_id')->default(1);
            $table->timestamps();
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
