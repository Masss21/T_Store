<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Tampilkan Daftar Produk
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    // Form Tambah Produk
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    // Proses Simpan Produk
    public function store(Request $request)
    {
       $request->validate([
    'category_id' => 'required|exists:categories,id',
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
    'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
    'specifications' => 'nullable|string',
]);
        // Upload Main Image
        $mainImagePath = $request->file('main_image')->store('products', 'public');

        // Upload Gallery Images
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
        }

        // Parse Specifications (format: key:value per line)
        $specifications = [];
        if ($request->specifications) {
            $lines = explode("\n", $request->specifications);
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) == 2) {
                    $specifications[trim($parts[0])] = trim($parts[1]);
                }
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'specifications' => $specifications,
            'price' => $request->price,
            'stock' => $request->stock,
            'main_image' => $mainImagePath,
            'gallery_images' => $galleryImages,
            'status' => $request->stock > 0 ? 'available' : 'out_of_stock',
            'is_featured' => $request->has('is_featured'),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil ditambahkan!');
    }

    // Form Edit Produk
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    // Proses Update Produk
    public function update(Request $request, Product $product)
    {
        $request->validate([
    'category_id' => 'required|exists:categories,id',
    'name' => 'required|string|max:255',
    'description' => 'required|string',
    'price' => 'required|numeric|min:0',
    'stock' => 'required|integer|min:0',
    'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
    'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
    'specifications' => 'nullable|string',
]);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'status' => $request->stock > 0 ? 'available' : 'out_of_stock',
            'is_featured' => $request->has('is_featured'),
        ];

        // Update Main Image
        if ($request->hasFile('main_image')) {
            // Hapus image lama
            Storage::disk('public')->delete($product->main_image);
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        // Update Gallery Images
        if ($request->hasFile('gallery_images')) {
            // Hapus gallery lama
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
            $data['gallery_images'] = $galleryImages;
        }

        // Parse Specifications
        if ($request->specifications) {
            $specifications = [];
            $lines = explode("\n", $request->specifications);
            foreach ($lines as $line) {
                $parts = explode(':', $line, 2);
                if (count($parts) == 2) {
                    $specifications[trim($parts[0])] = trim($parts[1]);
                }
            }
            $data['specifications'] = $specifications;
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Produk berhasil diupdate!');
    }

    // Hapus Produk
    public function destroy(Product $product)
{
    // Hapus images
    Storage::disk('public')->delete($product->main_image);
    if ($product->gallery_images) {
        foreach ($product->gallery_images as $image) {
            Storage::disk('public')->delete($image);
        }
    }

    $productName = $product->name;
    $product->delete();

    return redirect()->route('admin.products.index')
        ->with('success', "Produk '{$productName}' berhasil dihapus!");
}
}