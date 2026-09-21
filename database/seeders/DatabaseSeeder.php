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

        User::firstOrCreate(
            ['email' => 'admin@aulacoffee.id'],
            [
                'name' => 'Admin',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'owner@aulacoffee.id'],
            [
                'name' => 'Owner',
                'password' => 'password',
                'role' => 'owner',
                'email_verified_at' => now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'karyawan1@gmail.com'],
            [
                'name' => 'Karyawan 1',
                'password' => 'password',
                'role' => 'karyawan',
                'email_verified_at' => now(),
            ]
        );
    }
}
