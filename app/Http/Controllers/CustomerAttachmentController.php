<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAttachment;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerAttachmentController extends Controller
{
    public function store(Request $request, Customer $customer)
    {
        $request->validate([
            'file' => 'required|file|max:20480|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx',
            'description' => 'nullable|string|max:255',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = 'customer_attachments/' . $customer->id . '/' . $fileName;

        $file->storeAs('public/customer_attachments/' . $customer->id, $fileName);

        $attachment = CustomerAttachment::create([
            'customer_id' => $customer->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $filePath,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'description' => $request->input('description'),
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLogger::log('attachment_uploaded', 'Lampiran ' . $file->getClientOriginalName() . ' berhasil diunggah untuk pelanggan.', $attachment);

        return redirect()->route('customers.show', $customer)->with('success', 'File uploaded successfully.');
    }

    public function destroy(Customer $customer, CustomerAttachment $attachment)
    {
        if ($attachment->customer_id !== $customer->id) {
            abort(404);
        }

        ActivityLogger::log('attachment_deleted', 'Lampiran ' . $attachment->file_name . ' berhasil dihapus.');

        $filePath = storage_path('app/public/' . $attachment->file_path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $attachment->delete();

        return redirect()->route('customers.show', $customer)->with('success', 'File deleted successfully.');
    }

    public function download(Customer $customer, CustomerAttachment $attachment)
    {
        if ($attachment->customer_id !== $customer->id) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $attachment->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $attachment->file_name);
    }
}
