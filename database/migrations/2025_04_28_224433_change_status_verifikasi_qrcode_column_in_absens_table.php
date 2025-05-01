<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusVerifikasiQrcodeColumnInAbsensTable extends Migration
{
    public function up()
    {
        Schema::table('absens', function (Blueprint $table) {
            $table->boolean('status_verifikasi_QRcode')->default(false)->change();
        });
    }

    public function down()
    {
        Schema::table('absens', function (Blueprint $table) {
            // Mengembalikan kolom ke tipe sebelumnya, misalnya VARCHAR
            $table->string('status_verifikasi_QRcode', 255)->default('belum terverifikasi')->change();
        });
    }
}
