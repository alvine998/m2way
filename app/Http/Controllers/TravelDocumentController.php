<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TravelDocument;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TravelDocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = TravelDocument::with(['customer', 'uploader']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            });
        }

        if ($type = $request->input('type')) {
            $query->where('document_type', $type);
        }

        $documents = $query->latest()->paginate(15)->withQueryString();
        return view('travel-documents.index', compact('documents'))->with('pageTitle', 'Travel Documents');
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        return view('travel-documents.create', compact('customers'))->with('pageTitle', 'Add Document');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'document_type' => 'required|in:passport,ktp,visa,vaccination,insurance,ticket,hotel,other',
            'document_number' => 'nullable|string|max:50',
            'issuing_country' => 'nullable|string|max:50',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'file' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
            'notes' => 'nullable|string',
        ]);

        $documentData = collect($validated)->except(['file'])->toArray();
        $documentData['uploaded_by'] = auth()->id();

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'travel_documents/' . $validated['customer_id'] . '/' . $fileName;

            $file->storeAs('public/travel_documents/' . $validated['customer_id'], $fileName);

            $documentData['file_path'] = $filePath;
            $documentData['file_name'] = $file->getClientOriginalName();
            $documentData['file_type'] = $file->getMimeType();
            $documentData['file_size'] = $file->getSize();
        }

        $doc = TravelDocument::create($documentData);

        ActivityLogger::log('created', 'Dokumen ' . $doc->document_number . ' (' . $doc->document_type . ') berhasil dibuat.', $doc);

        return redirect()->route('travel-documents.index')->with('success', 'Document added successfully.');
    }

    public function edit(TravelDocument $travelDocument)
    {
        $customers = Customer::orderBy('name')->get();
        return view('travel-documents.edit', compact('travelDocument', 'customers'))->with('pageTitle', 'Edit Document');
    }

    public function update(Request $request, TravelDocument $travelDocument)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'document_type' => 'required|in:passport,ktp,visa,vaccination,insurance,ticket,hotel,other',
            'document_number' => 'nullable|string|max:50',
            'issuing_country' => 'nullable|string|max:50',
            'issue_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'file' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,pdf',
            'notes' => 'nullable|string',
        ]);

        $documentData = collect($validated)->except(['file'])->toArray();

        if ($request->hasFile('file')) {
            // Delete old file
            if ($travelDocument->file_path) {
                $oldPath = storage_path('app/public/' . $travelDocument->file_path);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'travel_documents/' . $validated['customer_id'] . '/' . $fileName;

            $file->storeAs('public/travel_documents/' . $validated['customer_id'], $fileName);

            $documentData['file_path'] = $filePath;
            $documentData['file_name'] = $file->getClientOriginalName();
            $documentData['file_type'] = $file->getMimeType();
            $documentData['file_size'] = $file->getSize();
        }

        $travelDocument->update($documentData);

        ActivityLogger::log('updated', 'Dokumen ' . $travelDocument->document_number . ' berhasil diubah.', $travelDocument);

        return redirect()->route('travel-documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(TravelDocument $travelDocument)
    {
        if ($travelDocument->file_path) {
            $filePath = storage_path('app/public/' . $travelDocument->file_path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $travelDocument->delete();
        ActivityLogger::log('deleted', 'Dokumen ' . $travelDocument->document_number . ' berhasil dihapus.');
        return redirect()->route('travel-documents.index')->with('success', 'Document deleted successfully.');
    }

    public function download(TravelDocument $travelDocument)
    {
        if (!$travelDocument->file_path) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $travelDocument->file_path);

        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $travelDocument->file_name);
    }
}
