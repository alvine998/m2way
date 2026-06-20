<?php
namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageCategory;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::query();
        if ($type = $request->input('type')) $query->where('type', $type);
        if ($search = $request->input('search')) $query->where('name', 'like', "%{$search}%");
        $packages = $query->latest()->paginate(10)->withQueryString();
        $categories = PackageCategory::active()->ordered()->get();
        return view('packages.index', compact('packages', 'categories'))->with('pageTitle', 'Packages');
    }

    public function create()
    {
        $categories = PackageCategory::active()->ordered()->get();
        return view('packages.create', compact('categories'))->with('pageTitle', 'Add Package');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|exists:package_categories,slug',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'departure_city' => 'nullable|string|max:100',
            'quota' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'hpp' => 'required|numeric|min:0',
            'cost_details' => 'nullable|array',
            'airline' => 'nullable|string|max:100',
            'hotel_name' => 'nullable|string|max:255',
            'hotel_star' => 'nullable|integer|min:1|max:5',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',
        ]);
        $validated['quota_remaining'] = $validated['quota'];
        if (!empty($validated['includes'])) {
            $validated['includes'] = array_filter(array_map('trim', explode("\n", $validated['includes'])));
        }
        if (!empty($validated['excludes'])) {
            $validated['excludes'] = array_filter(array_map('trim', explode("\n", $validated['excludes'])));
        }
        $package = Package::create($validated);
        ActivityLogger::log('created', 'Paket ' . $package->name . ' berhasil dibuat.', $package);
        return redirect()->route('packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(Package $package)
    {
        $categories = PackageCategory::active()->ordered()->get();
        return view('packages.edit', compact('package', 'categories'))->with('pageTitle', 'Edit Package');
    }

    public function update(Request $request, Package $package)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|exists:package_categories,slug',
            'description' => 'nullable|string',
            'duration' => 'required|integer|min:1',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'departure_city' => 'nullable|string|max:100',
            'quota' => 'required|integer|min:1',
            'base_price' => 'required|numeric|min:0',
            'hpp' => 'required|numeric|min:0',
            'cost_details' => 'nullable|array',
            'airline' => 'nullable|string|max:100',
            'hotel_name' => 'nullable|string|max:255',
            'hotel_star' => 'nullable|integer|min:1|max:5',
            'includes' => 'nullable|string',
            'excludes' => 'nullable|string',
            'status' => 'required|in:draft,active,inactive',
        ]);
        if (!empty($validated['includes'])) {
            $validated['includes'] = array_filter(array_map('trim', explode("\n", $validated['includes'])));
        }
        if (!empty($validated['excludes'])) {
            $validated['excludes'] = array_filter(array_map('trim', explode("\n", $validated['excludes'])));
        }
        if (isset($validated['quota']) && $validated['quota'] != $package->quota) {
            $used = $package->quota - $package->quota_remaining;
            $validated['quota_remaining'] = max(0, $validated['quota'] - $used);
        }
        $package->update($validated);
        ActivityLogger::log('updated', 'Paket ' . $package->name . ' berhasil diubah.', $package);
        return redirect()->route('packages.index')->with('success', 'Package updated successfully.');
    }

    public function destroy(Package $package)
    {
        $name = $package->name;
        $package->delete();
        ActivityLogger::log('deleted', 'Paket ' . $name . ' berhasil dihapus.');
        return redirect()->route('packages.index')->with('success', 'Package deleted successfully.');
    }
}
