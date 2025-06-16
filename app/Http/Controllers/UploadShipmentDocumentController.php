<?php

namespace App\Http\Controllers;

use App\Models\UploadShipmentDocument;
use App\Models\Shipment;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadShipmentDocumentController extends Controller
{
    public function create()
    {
        return view('upload-shipment-documents.create', [
            'shipments' => \App\Models\Shipment::all(),
            'documentTypes' => \App\Models\DocumentType::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'document_type_id' => 'required|exists:document_types,id',
            'attachments.*' => 'required|file|mimes:pdf,jpg,jpeg,png,heic|max:20480' // 20MB
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->store('shipment_documents', 'public');

                UploadShipmentDocument::create([
                    'shipment_id' => $validated['shipment_id'],
                    'document_type_id' => $validated['document_type_id'],
                    'attachment' => $filename,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('upload-shipment-documents.index')
            ->with('success', count($request->file('attachments')).' documents uploaded successfully.');
    }

    public function index()
    {
        $documents = UploadShipmentDocument::with(['shipment', 'documentType'])->latest()->get();
        return view('upload-shipment-documents.index', compact('documents'));
    }

    public function destroy($id)
    {
        $document = UploadShipmentDocument::findOrFail($id);
        
        // Hapus file fisik hanya jika force delete
        if (Storage::disk('public')->exists($document->attachment)) {
            Storage::disk('public')->delete($document->attachment);
        }
        
        $document->forceDelete(); // Untuk benar-benar menghapus
        
        return redirect()->back()->with('success', 'Document permanently deleted');
    }
}
