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
        Schema::create('program_sedekah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_program');
            $table->text('deskripsi')->nullable();

            $table->enum('jenis_target', [
                'sukarela',
                'target'
            ])->default('target');

            $table->decimal('target_dana', 15, 2)->nullable();

            $table->enum('status', [
                'draft',
                'aktif',
                'selesai',
                'ditutup'
            ])->default('draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_sedekah');
    }
};
