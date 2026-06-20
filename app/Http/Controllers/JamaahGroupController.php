<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\JamaahGroup;
use App\Models\Package;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class JamaahGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = JamaahGroup::with(['package', 'leader']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('group_number', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $groups = $query->latest()->paginate(15)->withQueryString();
        return view('jamaah-groups.index', compact('groups'))->with('pageTitle', 'Jamaah Groups');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $packages = Package::where('status', 'active')->orderBy('name')->get();
        return view('jamaah-groups.create', compact('customers', 'packages'))->with('pageTitle', 'Add Group');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'leader_id' => 'nullable|exists:customers,id',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'quota' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
            'customers' => 'nullable|array',
            'customers.*' => 'exists:customers,id',
        ]);

        $group = JamaahGroup::create(collect($validated)->except(['customers'])->toArray());

        ActivityLogger::log('created', 'Kelompok ' . $group->name . ' berhasil dibuat.', $group);

        if (!empty($validated['customers'])) {
            $customerData = [];
            foreach ($validated['customers'] as $customerId) {
                $customerData[$customerId] = ['room_type' => 'double'];
            }
            $group->customers()->sync($customerData);
        }

        return redirect()->route('jamaah-groups.index')->with('success', 'Group created successfully.');
    }

    public function show(JamaahGroup $jamaahGroup)
    {
        $jamaahGroup->load(['package', 'leader', 'customers']);
        return view('jamaah-groups.show', compact('jamaahGroup'))->with('pageTitle', $jamaahGroup->name);
    }

    public function edit(JamaahGroup $jamaahGroup)
    {
        $jamaahGroup->load('customers');
        $customers = Customer::orderBy('name')->get();
        $packages = Package::where('status', 'active')->orderBy('name')->get();
        $selectedCustomers = $jamaahGroup->customers->pluck('id')->toArray();
        return view('jamaah-groups.edit', compact('jamaahGroup', 'customers', 'packages', 'selectedCustomers'))->with('pageTitle', 'Edit Group');
    }

    public function update(Request $request, JamaahGroup $jamaahGroup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'package_id' => 'required|exists:packages,id',
            'leader_id' => 'nullable|exists:customers,id',
            'departure_date' => 'required|date',
            'return_date' => 'nullable|date|after_or_equal:departure_date',
            'quota' => 'required|integer|min:1|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
            'customers' => 'nullable|array',
            'customers.*' => 'exists:customers,id',
        ]);

        $jamaahGroup->update(collect($validated)->except(['customers'])->toArray());

        ActivityLogger::log('updated', 'Kelompok ' . $jamaahGroup->name . ' berhasil diubah.', $jamaahGroup);

        $customerData = [];
        if (!empty($validated['customers'])) {
            foreach ($validated['customers'] as $customerId) {
                $customerData[$customerId] = ['room_type' => 'double'];
            }
        }
        $jamaahGroup->customers()->sync($customerData);

        return redirect()->route('jamaah-groups.index')->with('success', 'Group updated successfully.');
    }

    public function destroy(JamaahGroup $jamaahGroup)
    {
        $name = $jamaahGroup->name;
        $jamaahGroup->delete();
        ActivityLogger::log('deleted', 'Kelompok ' . $name . ' berhasil dihapus.');
        return redirect()->route('jamaah-groups.index')->with('success', 'Group deleted successfully.');
    }
}
