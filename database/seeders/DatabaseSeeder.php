<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tambahkan semua seeder yang ingin dijalankan saat --seed di sini
        $this->call([
            SuperAdminSeeder::class,
            // Tambah seeder lain di bawah ini jika perlu
            // PermissionSeeder::class,
        ]);
    }
}
