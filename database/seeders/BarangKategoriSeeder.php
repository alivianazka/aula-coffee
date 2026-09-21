<?php

namespace Database\Seeders;

use App\Models\BarangKategori;
use Illuminate\Database\Seeder;

class BarangKategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarangKategori::firstOrCreate(
            ['nama' => 'Bar'],
            ['deskripsi' => 'Minuman Bar - Alcohol & Non-Alcohol Beverages']
        );

        BarangKategori::firstOrCreate(
            ['nama' => 'Kitchen'],
            ['deskripsi' => 'Makanan & Persiapan - Food Items & Ingredients']
        );

        BarangKategori::firstOrCreate(
            ['nama' => 'Peralatan'],
            ['deskripsi' => 'Peralatan & Supplies - Equipment & Supplies']
        );
    }
}
