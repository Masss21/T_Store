<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Daftar Kategori
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.categories.index', compact('categories'));
    }

    // Form Tambah Kategori
    public function create()
    {
        return view('admin.categories.create');
    }

    // Simpan Kategori
    public function store(Request $request)
    {
        $request->validate([
    'name' => 'required|string|max:255|unique:categories,name',
    'description' => 'nullable|string',
    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
]);
        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Form Edit Kategori
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Update Kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
    'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
    'description' => 'nullable|string',
    'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048|dimensions:min_width=500,min_height=500',
        ]);

        $data = [
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Hapus image lama jika ada
            if ($category->image) {
                \Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    // Hapus Kategori
   public function destroy(Category $category)
{
    // Cek apakah kategori masih digunakan produk
    if ($category->products()->count() > 0) {
        return redirect()->route('admin.categories.index')
            ->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $category->products()->count() . ' produk!');
    }

    // Hapus image
    if ($category->image) {
        \Storage::disk('public')->delete($category->image);
    }

    $categoryName = $category->name;
    $category->delete();

    return redirect()->route('admin.categories.index')
        ->with('success', "Kategori '{$categoryName}' berhasil dihapus!");
}
}