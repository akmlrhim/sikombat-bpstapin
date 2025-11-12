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
        Schema::create('kontrak', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 128);
            $table->string('nomor_kontrak', 16)->index();
            $table->foreignId('mitra_id')->constrained('mitra')->onDelete('cascade');
            $table->string('periode')->index();
            $table->date('tanggal_kontrak');
            $table->date('tanggal_bast');
            $table->date('tanggal_surat');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->text('keterangan')->nullable();
            $table->decimal('total_honor', 15, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontrak');
    }
};
