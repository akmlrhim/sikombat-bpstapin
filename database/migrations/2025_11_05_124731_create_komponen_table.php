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
		Schema::create('komponen', function (Blueprint $table) {
			$table->id();
			$table->foreignId('id_output')->constrained('output')->onDelete('cascade');
			$table->string('kode_komponen', 50);
			$table->text('nama_komponen');
			$table->integer('jumlah_sampel')->default(0);
			$table->string('satuan', 40);
			$table->decimal('harga_satuan', 15, 0);
			$table->decimal('total_harga', 15, 0);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('kegiatan');
	}
};
