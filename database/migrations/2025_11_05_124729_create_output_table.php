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
		Schema::create('output', function (Blueprint $table) {
			$table->id();
			$table->string('uuid', 128);
			$table->foreignId('id_kegiatan')->constrained('kegiatan')->onDelete('cascade');
			$table->string('kode_output')->index();
			$table->string('nama_output')->index();
			$table->string('deskripsi')->index();

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
