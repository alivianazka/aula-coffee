<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangKategori;
use App\Models\Barang;

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            ['nama' => 'Bar', 'deskripsi' => 'Bahan baku minuman (Kopi, Susu, Sirup, dll)'],
            ['nama' => 'Kitchen', 'deskripsi' => 'Bahan baku makanan (Roti, Mentega, dll)'],
            ['nama' => 'Peralatan', 'deskripsi' => 'Perlengkapan operasional (Cup, Sedotan, dll)'],
        ];

        foreach ($categories as $cat) {
            BarangKategori::updateOrCreate(['nama' => $cat['nama']], $cat);
        }

        // 2. Items Mapping
        $catBar = BarangKategori::where('nama', 'Bar')->first()->id;
        $catKitchen = BarangKategori::where('nama', 'Kitchen')->first()->id;
        $catPeralatan = BarangKategori::where('nama', 'Peralatan')->first()->id;

        $items = [
            // BAR
            ['kategori_id' => $catBar, 'nama' => 'House Blend Arabica', 'stok_awal' => 50, 'qty' => 50, 'stok_akhir' => 50, 'stok_opname' => 5, 'unit' => 'kg'],
            ['kategori_id' => $catBar, 'nama' => 'Robusta Temanggung', 'stok_awal' => 30, 'qty' => 30, 'stok_akhir' => 30, 'stok_opname' => 3, 'unit' => 'kg'],
            ['kategori_id' => $catBar, 'nama' => 'Fresh Milk Diamond', 'stok_awal' => 100, 'qty' => 100, 'stok_akhir' => 100, 'stok_opname' => 12, 'unit' => 'liter'],
            ['kategori_id' => $catBar, 'nama' => 'Oat Milk Oatside', 'stok_awal' => 24, 'qty' => 24, 'stok_akhir' => 24, 'stok_opname' => 6, 'unit' => 'liter'],
            ['kategori_id' => $catBar, 'nama' => 'Vanilla Syrup Monin', 'stok_awal' => 10, 'qty' => 10, 'stok_akhir' => 10, 'stok_opname' => 2, 'unit' => 'botol'],
            ['kategori_id' => $catBar, 'nama' => 'Caramel Syrup Monin', 'stok_awal' => 10, 'qty' => 10, 'stok_akhir' => 10, 'stok_opname' => 2, 'unit' => 'botol'],
            ['kategori_id' => $catBar, 'nama' => 'Gula Cair (Simple Syrup)', 'stok_awal' => 20, 'qty' => 20, 'stok_akhir' => 20, 'stok_opname' => 5, 'unit' => 'liter'],
            ['kategori_id' => $catBar, 'nama' => 'Es Batu (Kristal)', 'stok_awal' => 100, 'qty' => 100, 'stok_akhir' => 100, 'stok_opname' => 20, 'unit' => 'pack'],
            
            // KITCHEN
            ['kategori_id' => $catKitchen, 'nama' => 'Roti Tawar Bandung', 'stok_awal' => 40, 'qty' => 40, 'stok_akhir' => 40, 'stok_opname' => 5, 'unit' => 'loaf'],
            ['kategori_id' => $catKitchen, 'nama' => 'Butter Unsalted', 'stok_awal' => 10, 'qty' => 10, 'stok_akhir' => 10, 'stok_opname' => 2, 'unit' => 'kg'],
            ['kategori_id' => $catKitchen, 'nama' => 'Daging Patty Burger', 'stok_awal' => 50, 'qty' => 50, 'stok_akhir' => 50, 'stok_opname' => 10, 'unit' => 'pcs'],
            
            // PERALATAN
            ['kategori_id' => $catPeralatan, 'nama' => 'Paper Cup 8oz', 'stok_awal' => 500, 'qty' => 500, 'stok_akhir' => 500, 'stok_opname' => 100, 'unit' => 'pcs'],
            ['kategori_id' => $catPeralatan, 'nama' => 'Plastic Cup 12oz', 'stok_awal' => 1000, 'qty' => 1000, 'stok_akhir' => 1000, 'stok_opname' => 200, 'unit' => 'pcs'],
            ['kategori_id' => $catPeralatan, 'nama' => 'Sedotan Bambu', 'stok_awal' => 500, 'qty' => 500, 'stok_akhir' => 500, 'stok_opname' => 50, 'unit' => 'pcs'],
        ];

        foreach ($items as $item) {
            Barang::updateOrCreate(
                ['nama' => $item['nama']], // Unique by name now to allow re-categorization
                $item
            );
        }

        // Clean up old categories
        BarangKategori::whereNotIn('nama', ['Bar', 'Kitchen', 'Peralatan'])->delete();
    }
}
