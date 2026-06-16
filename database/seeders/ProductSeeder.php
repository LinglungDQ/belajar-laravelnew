<?php
// database/seeders/ProductSeeder.php
namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 50 produk random
        Product::factory()->count(50)->create();

        // Buat 10 produk aktif dan featured
        Product::factory()->count(10)->active()->featured()->create();

        // Buat 5 produk stok habis
        Product::factory()->count(5)->outOfStock()->create();

        $this->command->info('✅ 65 produk berhasil dibuat.');
    }
}

