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
        Schema::create('import_munfiq', function (Blueprint $table) {
            $table->id();

            $table->string('sheet_name');      // GANG 1
            $table->integer('no')->nullable();

            $table->string('kode')->nullable();
            $table->string('nama')->nullable();
            $table->string('no_hp')->nullable();

            $table->decimal('jan', 12, 2)->nullable();
            $table->decimal('feb', 12, 2)->nullable();
            $table->decimal('mar', 12, 2)->nullable();
            $table->decimal('apr', 12, 2)->nullable();
            $table->decimal('mei', 12, 2)->nullable();
            $table->decimal('jun', 12, 2)->nullable();
            $table->decimal('jul', 12, 2)->nullable();
            $table->decimal('agt', 12, 2)->nullable();
            $table->decimal('sept', 12, 2)->nullable();
            $table->decimal('okt', 12, 2)->nullable();
            $table->decimal('nov', 12, 2)->nullable();
            $table->decimal('des', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('import_munfiq');
    }
};
