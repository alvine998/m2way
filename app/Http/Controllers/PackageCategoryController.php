<?php

namespace App\Http\Controllers;

use App\Models\PackageCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageCategoryController extends Controller
{
    public function index()
    {
        $categories = PackageCategory::ordered()->paginate(15);
        return view('package-categories.index', compact('categories'))->with('pageTitle', 'Package Categories');
    }

    public function create()
    {
        return view('package-categories.create')->with('pageTitle', 'Add Category');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:package_categories,name',
            'slug' => 'nullable|string|max:255|unique:package_categories,slug',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:7',
            'bg_color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $category = PackageCategory::create($validated);
        ActivityLogger::log('created', 'Kategori paket ' . $category->name . ' berhasil dibuat.', $category);

        return redirect()->route('package-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(PackageCategory $packageCategory)
    {
        return view('package-categories.edit', ['category' => $packageCategory])
            ->with('pageTitle', 'Edit Category');
    }

    public function update(Request $request, PackageCategory $packageCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:package_categories,name,' . $packageCategory->id,
            'slug' => 'nullable|string|max:255|unique:package_categories,slug,' . $packageCategory->id,
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'required|string|max:7',
            'bg_color' => 'required|string|max:7',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?: Str::slug($validated['name']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $packageCategory->update($validated);

        ActivityLogger::log('updated', 'Kategori paket ' . $packageCategory->name . ' berhasil diubah.', $packageCategory);

        return redirect()->route('package-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(PackageCategory $packageCategory)
    {
        $name = $packageCategory->name;
        $packageCategory->delete();
        ActivityLogger::log('deleted', 'Kategori paket ' . $name . ' berhasil dihapus.');
        return redirect()->route('package-categories.index')->with('success', 'Category deleted successfully.');
    }
}
