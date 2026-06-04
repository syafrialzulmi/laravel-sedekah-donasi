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
        Schema::create('donasi', function (Blueprint $table) {
            $table->id();
            // Donatur
            $table->foreignId('donatur_id')
                ->constrained('donatur')
                ->cascadeOnDelete();

            // Program sedekah
            $table->foreignId('program_id')
                ->constrained('program_sedekah')
                ->cascadeOnDelete();

            // Nominal donasi
            $table->decimal('nominal', 15, 2);

            // Periode donasi
            $table->unsignedTinyInteger('bulan');
            $table->year('tahun');

            // Tanggal pembayaran/donasi
            $table->date('tanggal_donasi');

            // Keterangan tambahan
            $table->text('keterangan')->nullable();

            // Status pengiriman WA laporan
            $table->boolean('wa_terkirim')
                ->default(false);

            $table->timestamp('wa_terkirim_at')
                ->nullable();

            // User yang menginput
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

            // Mencegah donatur tercatat 2x pada periode yang sama
            $table->unique([
                'donatur_id',
                'program_id',
                'bulan',
                'tahun'
            ], 'donasi_periode_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donasi');
    }
};
