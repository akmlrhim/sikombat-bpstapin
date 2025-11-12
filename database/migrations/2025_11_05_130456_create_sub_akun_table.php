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
        Schema::create('sub_akun', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 128);
            $table->foreignId('id_akun')->constrained('akun')->onDelete('cascade');
            $table->string('kode_sub_akun')->index();
            $table->string('nama_sub_akun')->index();
            $table->string('nama_kegiatan_sub_akun')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_akun');
    }
};
