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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('barang_kategori')->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->integer('stok_awal')->default(0);
            $table->integer('stok_akhir')->default(0);
            $table->integer('stok_opname')->default(100)->comment('Batas minimal stok untuk notifikasi');
            $table->integer('qty')->default(0)->comment('Jumlah barang yang tersedia');
            $table->string('unit')->default('pcs');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['kategori_id', 'nama']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
