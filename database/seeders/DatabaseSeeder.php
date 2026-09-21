<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed kategori barang
        $this->call(BarangKategoriSeeder::class);
        
        // Seed barang
        $this->call(BarangSeeder::class);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@aulacoffee.id',
            'password' => 'password',
            'role' => 'admin'
        ]);

        User::factory()->create([
            'name' => 'Owner',
            'email' => 'owner@aulacoffee.id',
            'password' => 'password',
            'role' => 'owner'
        ]);

        User::factory()->create([
            'name' => 'Karyawan 1',
            'email' => 'karyawan1@gmail.com',
            'password' => 'password',
            'role' => 'karyawan'
        ]);
    }
}
