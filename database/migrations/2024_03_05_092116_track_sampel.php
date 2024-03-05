<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('track_sampel', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_terima')->nullable();
            $table->dateTime('tanggal_memo')->nullable();
            $table->integer('jenis_sampel')->nullable();
            $table->string('asal_sampel', 50)->nullable();
            $table->integer('nomor_kupa')->nullable();
            $table->string('nama_pengirim', 50)->nullable();
            $table->string('departemen', 50)->nullable();
            $table->string('kode_sampel', 50)->nullable();
            $table->string('nomor_surat', 100)->nullable();
            $table->string('nomor_lab', 256)->nullable();
            $table->dateTime('estimasi')->nullable();
            $table->dateTime('tanggal_pengantaran')->nullable();
            $table->string('tujuan', 20)->nullable();
            $table->string('parameter_analisis', 100)->nullable();
            $table->string('parameter_analisisid', 15);
            $table->integer('progress')->nullable();
            $table->string('last_update', 800)->nullable();
            $table->integer('admin')->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('emailTo', 256)->nullable();
            $table->string('emailCc', 256)->nullable();
            $table->string('foto_sampel', 256)->nullable();
            $table->string('kode_track', 15);
            $table->integer('jumlah_sampel')->nullable();
            $table->string('skala_prioritas', 10);
            $table->string('kondisi_sampel', 10);
            $table->tinyInteger('personel');
            $table->tinyInteger('alat');
            $table->tinyInteger('bahan');
            $table->integer('discount');
            $table->tinyInteger('konfirmasi');
            $table->string('kemasan_sampel', 20)->nullable();
            $table->longText('catatan')->nullable();
            $table->enum('status', ['Approved', 'Rejected', 'Waiting Approved', 'Draft'])->default('Waiting Approved');
            $table->integer('status_changed_by_id')->nullable();
            $table->string('status_approved_by_role', 50)->nullable();
            $table->timestamp('status_timestamp')->CURRENT_TIMESTAMP();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_sampel');
    }
};
