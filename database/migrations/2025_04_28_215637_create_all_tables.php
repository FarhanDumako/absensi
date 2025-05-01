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
         // Buat tabel absens
         Schema::create('absens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_dan_waktu');
            $table->string('lokasi'); // format "latitude,longitude"
            $table->string('status_verifikasi_QRcode'); // verified / pending
            $table->timestamps();
        });

        // Buat tabel pengajuans
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('tanggal_pengajuan');
            $table->enum('jenis_pengajuan', ['izin', 'sakit']);
            $table->string('dokumen_pendukung')->nullable();
            $table->enum('status_pengajuan', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         // Drop tabel absens dan pengajuans saat rollback
         Schema::dropIfExists('pengajuans');
         Schema::dropIfExists('absens');
    }
};
