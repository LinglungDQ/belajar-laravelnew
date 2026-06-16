<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_products_table.php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            // foreignId("x") = BIGINT UNSIGNED
            // constrained()  = FK ke tabel categories.id
            // cascadeOnDelete() = hapus produk jika kategori dihapus

            $table->string('name');
            $table->string('slug')->unique();
            $table->decimal('price', 12, 2);  // 12 digit total, 2 desimal
            $table->unsignedInteger('stock')->default(0);
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft', 'out_of_stock'])
                  ->default('draft');
            $table->boolean('is_featured')->default(false);
            $table->decimal('weight', 8, 2)->nullable(); // berat produk (kg)
            $table->json('tags')->nullable();            // JSON field
            $table->integer('view_count')->default(0);
            $table->timestamps();
            $table->softDeletes();  // deleted_at (untuk soft delete)

            // Index untuk performa query
            $table->index('status');
            $table->index(['category_id', 'status']);  // composite index
            $table->fullText('name');                  // full-text search
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

