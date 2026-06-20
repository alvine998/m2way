<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAttachment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id_number', 'like', "%{$search}%");
            });
        }
        $customers = $query->latest()->paginate(10)->withQueryString();
        return view('customers.index', compact('customers'))->with('pageTitle', 'Customers');
    }

    public function create()
    {
        return view('customers.create')->with('pageTitle', 'Add Customer');
    }

    public function show(Customer $customer)
    {
        $customer->load(['transactions.package', 'attachments.uploader']);
        return view('customers.show', compact('customer'))->with('pageTitle', $customer->name);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:30',
            'id_type' => 'nullable|in:nik,passport',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'ktp' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
            'passport' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
        ]);

        $customer = Customer::create(collect($validated)->except(['ktp', 'passport'])->toArray());

        if ($request->hasFile('ktp')) {
            $this->storeAttachment($customer, $request->file('ktp'), 'ktp', 'KTP');
        }
        if ($request->hasFile('passport')) {
            $this->storeAttachment($customer, $request->file('passport'), 'passport', 'Passport');
        }

        ActivityLogger::log('created', 'Pelanggan ' . $customer->name . ' berhasil dibuat.', $customer);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'))->with('pageTitle', 'Edit Customer');
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'id_number' => 'nullable|string|max:30',
            'id_type' => 'nullable|in:nik,passport',
            'gender' => 'nullable|in:male,female',
            'date_of_birth' => 'nullable|date',
            'nationality' => 'nullable|string|max:50',
            'occupation' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'ktp' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
            'passport' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
        ]);

        $customer->update(collect($validated)->except(['ktp', 'passport'])->toArray());

        if ($request->hasFile('ktp')) {
            $this->deleteAttachmentByType($customer, 'ktp');
            $this->storeAttachment($customer, $request->file('ktp'), 'ktp', 'KTP');
        }

        if ($request->hasFile('passport')) {
            $this->deleteAttachmentByType($customer, 'passport');
            $this->storeAttachment($customer, $request->file('passport'), 'passport', 'Passport');
        }

        ActivityLogger::log('updated', 'Pelanggan ' . $customer->name . ' berhasil diubah.', $customer);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        foreach ($customer->attachments as $attachment) {
            $filePath = storage_path('app/public/' . $attachment->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
        $customer->delete();

        ActivityLogger::log('deleted', 'Pelanggan ' . $customer->name . ' berhasil dihapus.');

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    private function storeAttachment(Customer $customer, $file, string $type, string $description): void
    {
        $fileName = time() . '_' . $type . '_' . $file->getClientOriginalName();
        $filePath = 'customer_attachments/' . $customer->id . '/' . $fileName;

        $file->storeAs('public/customer_attachments/' . $customer->id, $fileName);

        $attachment = CustomerAttachment::create([
            'customer_id' => $customer->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $description,
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLogger::log('attachment_uploaded', 'Lampiran ' . $file->getClientOriginalName() . ' berhasil diunggah untuk pelanggan ' . $customer->name . '.', $attachment);
    }

    private function deleteAttachmentByType(Customer $customer, string $type): void
    {
        $attachment = $customer->attachments()
            ->where('description', ucfirst($type))
            ->first();

        if ($attachment) {
            $filePath = storage_path('app/public/' . $attachment->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $attachment->delete();

            ActivityLogger::log('attachment_deleted', 'Lampiran ' . $attachment->file_name . ' berhasil dihapus dari pelanggan ' . $customer->name . '.');
        }
    }
}
