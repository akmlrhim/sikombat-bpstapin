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
        Schema::create('detail_kontrak', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kontrak_id')->constrained('kontrak')->onDelete('cascade');
            $table->foreignId('akun_id')->constrained('akun')->onDelete('cascade');
            $table->foreignId('id_sub_akun')->constrained('sub_akun')->onDelete('cascade');
            $table->foreignId('id_kegiatan')->constrained('kegiatan')->onDelete('cascade');
            $table->integer('jumlah_target_dokumen');
            $table->integer('jumlah_dokumen');
            $table->decimal('total_honor', 15, 0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations. 
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kontrak');
    }
};
