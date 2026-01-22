<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'phone' => '081234567891',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'address' => 'Jl. Contoh No. 123, Jakarta',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'phone' => '081234567892',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'address' => 'Jl. Sample No. 456, Bandung',
            ],
            [
                'name' => 'Bob Wilson',
                'email' => 'bob@example.com',
                'phone' => '081234567893',
                'password' => Hash::make('password123'),
                'role' => 'user',
                'email_verified_at' => now(),
                'address' => 'Jl. Test No. 789, Surabaya',
            ],
        ];

        foreach ($users as $userData) {
            $exists = User::where('email', $userData['email'])->exists();
            
            if (!$exists) {
                User::create($userData);
                $this->command->info('✅ User ' . $userData['name'] . ' berhasil dibuat!');
            }
        }

        $this->command->info('');
        $this->command->info('📝 Sample User Credentials:');
        $this->command->info('Email: john@example.com | Password: password123');
        $this->command->info('Email: jane@example.com | Password: password123');
        $this->command->info('Email: bob@example.com | Password: password123');
    }
}