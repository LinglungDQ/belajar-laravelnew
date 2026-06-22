<?php

// app/Http/Controllers/ProductController.php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
//use App\Http\Requests\StoreProductRequest;


class ProductController extends Controller
{
    /**
     * GET /products
     * Tampilkan daftar semua produk.
     */
    public function index(Request $request): View
    {
        // Ambil data dari request (query string)
        $search     = $request->input('search', '');
        $categoryId = $request->input('category_id');
        $sortBy     = $request->input('sort_by', 'created_at');
        $sortOrder  = $request->input('sort_order', 'desc');

        // Query produk dengan filter
        $products = Product::with('category')
            ->when(
                $search,
                fn ($q) =>
                $q->where('name', 'LIKE', "%{$search}%")
            )
            ->when(
                $categoryId,
                fn ($q) =>
                $q->where('category_id', $categoryId)
            )
            ->orderBy($sortBy, $sortOrder)
            ->paginate(10)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        // Kirim data ke view
        return view(
            'products.index',
            compact('products', 'categories', 'search')
        );
    }

    /**
     * GET /products/create
     * Tampilkan form tambah produk baru.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    /**
     * POST /products
     * Simpan produk baru ke database.
     */
 // Validasi di controller dengan pesan kustom
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required','string','min:3','max:255','unique:products,name',
            ],
            'price' => [
                'required','numeric','min:1000',
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
                    ->where('is_active', true),
            ],
            'stock' => [
                'required','integer','min:0',
            ],
            'description' => [
                'nullable','string',
            ],
            'status' => [
                'required','in:active,inactive,draft',
            ],
            'image' => [
                'nullable','image','mimes:jpg,jpeg,png,webp','max:2048','dimensions:min_width=100,min_height=100',
            ],
        ], [
            'name.required'       => 'Nama produk wajib diisi.',
            'name.unique'         => 'Nama produk sudah terdaftar.',
            'price.min'           => 'Harga minimum adalah Rp 1.000.',
            'category_id.exists'  => 'Kategori tidak valid atau tidak aktif.',
            'image.dimensions'    => 'Gambar harus minimal 100x100 pixel.',
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('products', 'public');
        }

        // Generate slug otomatis
        $validated['slug'] = Str::slug($validated['name']) . '-' . Str::random(5);

        Product::create($validated);

        return to_route('products.index')
            ->with('success', 'Produk disimpan!');
    }
    /**
     * GET /products/{product}
     * Tampilkan detail satu produk.
     */
    public function show(Product $product): View
    {
        // Load relasi yang dibutuhkan
        $product->load('category');

        // Produk serupa
        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'related'));
    }

    /**
     * GET /products/{product}/edit
     * Tampilkan form edit produk.
     */
    public function edit(Product $product): View
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * PUT /products/{product}
     * Update data produk di database.
     */
    public function update(Request $request, Product $product): RedirectResponse {
        $validated = $request->validate([
            'name'        => 'required|string|min:3|max:200',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'description' => 'nullable|string|max:5000',
            'status'      => 'required|in:active,inactive,draft',
            'image'       => 'nullable|image|mimes:jpeg,png,webp|max:2048',
        ]);

        // Update gambar jika ada file baru
        if ($request->hasFile('image')) {

            // Hapus gambar lama
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $validated['image'] = $request
                ->file('image')
                ->store('products', 'public');
        }

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                "Produk \"{$product->name}\" berhasil diperbarui!"
            );
    }

    /**
     * DELETE /products/{product}
     * Hapus produk dari database.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $nama = $product->name;

        // Hapus gambar dari storage
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with(
                'success',
                "Produk \"{$nama}\" berhasil dihapus."
            );
    }
}
