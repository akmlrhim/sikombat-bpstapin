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
		Schema::create('kegiatan', function (Blueprint $table) {
			$table->id();
			$table->string('uuid');
			$table->string('kode_kegiatan', 15)->unique();
			$table->string('nama_kegiatan')->index();
			$table->bigInteger('pagu_anggaran')->default(0);
			$table->bigInteger('sisa_anggaran')->default(0);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('akun_utama');
	}
};
