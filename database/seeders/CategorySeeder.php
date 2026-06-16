<?php
// database/seeders/CategorySeeder.php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Elektronik',     'description' => 'Perangkat elektronik dan gadget'],
            ['name' => 'Pakaian',        'description' => 'Fashion dan pakaian'],
            ['name' => 'Makanan',        'description' => 'Produk makanan dan minuman'],
            ['name' => 'Olahraga',       'description' => 'Peralatan dan pakaian olahraga'],
            ['name' => 'Rumah & Taman',  'description' => 'Perabotan dan dekorasi rumah'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name'        => $cat['name'],
                'slug'        => Str::slug($cat['name']),
                'description' => $cat['description'],
                'is_active'   => true,
            ]);
        }

        $this->command->info('✅ ' . count($categories) . ' kategori berhasil dibuat.');
    }
}
