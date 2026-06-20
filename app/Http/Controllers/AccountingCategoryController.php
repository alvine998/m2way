<?php

namespace App\Http\Controllers;

use App\Models\AccountingCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AccountingCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingCategory::query();

        if ($type = $request->input('type')) {
            $query->byType($type);
        }

        $categories = $query->ordered()->paginate(15)->withQueryString();
        return view('accounting-categories.index', compact('categories'))->with('pageTitle', 'Accounting Categories');
    }

    public function create()
    {
        return view('accounting-categories.create')->with('pageTitle', 'Add Category');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? true;

        $category = AccountingCategory::create($validated);
        ActivityLogger::log('created', 'Kategori akuntansi ' . $category->name . ' berhasil dibuat.', $category);

        return redirect()->route('accounting-categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(AccountingCategory $accountingCategory)
    {
        return view('accounting-categories.edit', compact('accountingCategory'))->with('pageTitle', 'Edit Category');
    }

    public function update(Request $request, AccountingCategory $accountingCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:7',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_active'] = $validated['is_active'] ?? false;

        $accountingCategory->update($validated);

        ActivityLogger::log('updated', 'Kategori akuntansi ' . $accountingCategory->name . ' berhasil diubah.', $accountingCategory);

        return redirect()->route('accounting-categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(AccountingCategory $accountingCategory)
    {
        $name = $accountingCategory->name;
        $accountingCategory->delete();
        ActivityLogger::log('deleted', 'Kategori akuntansi ' . $name . ' berhasil dihapus.');
        return redirect()->route('accounting-categories.index')->with('success', 'Category deleted successfully.');
    }
}
