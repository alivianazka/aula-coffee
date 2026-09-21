<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangKategori;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Barang::count() > 0) {
            return;
        }

        $bar = BarangKategori::where('nama', 'Bar')->first();
        $kitchen = BarangKategori::where('nama', 'Kitchen')->first();
        $peralatan = BarangKategori::where('nama', 'Peralatan')->first();

        // Bar Items
        if ($bar) {
            Barang::create([
                'kategori_id' => $bar->id,
                'nama' => 'Kopi Arabika',
                'stok_awal' => 50,
                'stok_akhir' => 50,
                'qty' => 50,
                'stok_opname' => 100,
                'unit' => 'pack'
            ]);

            Barang::create([
                'kategori_id' => $bar->id,
                'nama' => 'Teh Premium',
                'stok_awal' => 30,
                'stok_akhir' => 30,
                'qty' => 30,
                'stok_opname' => 100,
                'unit' => 'pack'
            ]);

            Barang::create([
                'kategori_id' => $bar->id,
                'nama' => 'Gula Pasir',
                'stok_awal' => 25,
                'stok_akhir' => 25,
                'qty' => 25,
                'stok_opname' => 100,
                'unit' => 'kg'
            ]);
        }

        // Kitchen Items
        if ($kitchen) {
            Barang::create([
                'kategori_id' => $kitchen->id,
                'nama' => 'Roti Tawar',
                'stok_awal' => 20,
                'stok_akhir' => 20,
                'qty' => 20,
                'stok_opname' => 100,
                'unit' => 'loaf'
            ]);

            Barang::create([
                'kategori_id' => $kitchen->id,
                'nama' => 'Butter',
                'stok_awal' => 15,
                'stok_akhir' => 15,
                'qty' => 15,
                'stok_opname' => 100,
                'unit' => 'pack'
            ]);

            Barang::create([
                'kategori_id' => $kitchen->id,
                'nama' => 'Susu Cair',
                'stok_awal' => 18,
                'stok_akhir' => 18,
                'qty' => 18,
                'stok_opname' => 100,
                'unit' => 'liter'
            ]);
        }

        // Equipment Items
        if ($peralatan) {
            Barang::create([
                'kategori_id' => $peralatan->id,
                'nama' => 'Gelas Kopi',
                'stok_awal' => 100,
                'stok_akhir' => 100,
                'qty' => 100,
                'stok_opname' => 200,
                'unit' => 'pcs'
            ]);

            Barang::create([
                'kategori_id' => $peralatan->id,
                'nama' => 'Sendok Kopi',
                'stok_awal' => 80,
                'stok_akhir' => 80,
                'qty' => 80,
                'stok_opname' => 150,
                'unit' => 'pcs'
            ]);

            Barang::create([
                'kategori_id' => $peralatan->id,
                'nama' => 'Serbet',
                'stok_awal' => 500,
                'stok_akhir' => 500,
                'qty' => 500,
                'stok_opname' => 1000,
                'unit' => 'pcs'
            ]);
        }
    }
}
