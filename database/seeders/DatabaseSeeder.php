<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            CategorySeeder::class, // TAMBAHKAN INI
        ]);

        $this->command->info('');
        $this->command->info('🎉 Seeding completed successfully!');
        $this->command->info('');
    }
}