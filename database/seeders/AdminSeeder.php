<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $adminExists = User::where('email', 'admin@tstore.com')->exists();

        if (!$adminExists) {
            User::create([
                'name' => 'Admin T-Store',
                'email' => 'admin@tstore.com',
                'phone' => '081234567890',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(), // Admin langsung verified
            ]);

            $this->command->info('✅ Admin berhasil dibuat!');
            $this->command->info('📧 Email: admin@tstore.com');
            $this->command->info('🔑 Password: admin123');
        } else {
            $this->command->info('⚠️ Admin sudah ada, skip...');
        }
    }
}