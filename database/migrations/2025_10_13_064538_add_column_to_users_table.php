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
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('no_hp')->nullable()->after('email');
            $table->string('foto')->nullable()->after('no_hp');
            $table->softDeletes(); // tambahkan kolom deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropForeign(['kecamatan_id']);
            $table->dropForeign(['desa_id']);

            // hapus kolom
            $table->dropColumn(['no_hp', 'kecamatan_id', 'desa_id', 'foto', 'deleted_at']);
        });
    }
};
