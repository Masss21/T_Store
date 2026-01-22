<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Laptop',
                'slug' => 'laptop',
                'description' => 'Laptop untuk berbagai kebutuhan mulai dari gaming, bisnis, hingga multimedia',
                'is_active' => true,
            ],
            [
                'name' => 'Smartphone',
                'slug' => 'smartphone',
                'description' => 'Smartphone terbaru dengan teknologi canggih',
                'is_active' => true,
            ],
            [
                'name' => 'Tablet',
                'slug' => 'tablet',
                'description' => 'Tablet untuk produktivitas dan entertainment',
                'is_active' => true,
            ],
            [
                'name' => 'Smartwatch',
                'slug' => 'smartwatch',
                'description' => 'Jam tangan pintar untuk gaya hidup sehat',
                'is_active' => true,
            ],
            [
                'name' => 'Headphone & Earphone',
                'slug' => 'headphone-earphone',
                'description' => 'Audio devices berkualitas tinggi',
                'is_active' => true,
            ],
            [
                'name' => 'Aksesoris Komputer',
                'slug' => 'aksesoris-komputer',
                'description' => 'Mouse, keyboard, webcam, dan aksesoris lainnya',
                'is_active' => true,
            ],
            [
                'name' => 'Charger & Power Bank',
                'slug' => 'charger-power-bank',
                'description' => 'Pengisi daya dan power bank berbagai kapasitas',
                'is_active' => true,
            ],
            [
                'name' => 'Kabel & Adapter',
                'slug' => 'kabel-adapter',
                'description' => 'Kabel data, HDMI, USB-C, dan adapter',
                'is_active' => true,
            ],
            [
                'name' => 'Casing & Proteksi',
                'slug' => 'casing-proteksi',
                'description' => 'Casing, screen protector, dan pelindung gadget',
                'is_active' => true,
            ],
            [
                'name' => 'Gaming Gear',
                'slug' => 'gaming-gear',
                'description' => 'Peralatan gaming seperti controller, mousepad gaming',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $this->command->info('✅ ' . count($categories) . ' kategori berhasil dibuat!');
    }
}