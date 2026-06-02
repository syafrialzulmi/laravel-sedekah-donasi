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
        Schema::table('setting_apps', function (Blueprint $table) {
            $table->foreignId('kecamatan_id')
                ->nullable()
                ->after('id')
                ->constrained('kecamatan')
                ->cascadeOnUpdate()
                ->nullOnDelete();

            $table->foreignId('desa_id')
                ->nullable()
                ->after('kecamatan_id')
                ->constrained('desa')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_apps', function (Blueprint $table) {
            $table->dropForeign(['desa_id']);
            $table->dropForeign(['kecamatan_id']);

            $table->dropColumn(['desa_id', 'kecamatan_id']);
        });
    }
};
