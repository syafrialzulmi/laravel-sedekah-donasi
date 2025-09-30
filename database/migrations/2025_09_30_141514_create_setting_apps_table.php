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
        Schema::create('setting_apps', function (Blueprint $table) {
            $table->id();
            $table->string('name_app');
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable();    // path file
            $table->string('banner')->nullable();  // path file
            $table->string('favicon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_apps');
    }
};
