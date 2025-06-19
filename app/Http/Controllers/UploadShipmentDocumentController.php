<?php

namespace App\Http\Controllers;

use App\Models\UploadShipmentDocument;
use App\Models\Termin;
use App\Models\Shipment;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadShipmentDocumentController extends Controller
{
    public function index(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $perPage = $request->input('per_page', 10);
        
        $documents = UploadShipmentDocument::with(['shipment', 'documentType', 'period', 'creator'])
                        ->where('period_id', $activePeriodId);

        // Add filters
        if ($request->filled('shipment_id')) {
            $documents->where('shipment_id', $request->shipment_id);
        }

        if ($request->filled('document_type_id')) {
            $documents->where('document_type_id', $request->document_type_id);
        }

        $documents = $documents->latest()->paginate($perPage)->withQueryString();
        $totalDocuments = $documents->total();

        // Get filter options
        $shipments = Shipment::where('period_id', $activePeriodId)->get();
        $documentTypes = DocumentType::all();

        return view('upload-shipment-documents.index', compact(
            'documents', 'shipments', 'documentTypes', 'perPage', 'totalDocuments'
        ));
    }


    public function create()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        // Hanya ambil shipment dalam periode aktif
        $shipments = \App\Models\Shipment::where('period_id', $activePeriodId)->get();

        return view('upload-shipment-documents.create', [
            'activePeriodId' => $activePeriodId,
            'shipments' => $shipments,
            'documentTypes' => \App\Models\DocumentType::all(),
        ]);
    }


   public function store(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $validated = $request->validate([
            'shipment_id' => 'required|exists:shipments,id',
            'document_type_id' => 'required|exists:document_types,id',
            'attachments.*' => 'required|file|mimes:pdf,jpg,jpeg,png,heic|max:20480', // 20MB
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $filename = $file->store('shipment_documents', 'public');

                UploadShipmentDocument::create([
                    'period_id' => $activePeriodId,
                    'shipment_id' => $validated['shipment_id'],
                    'document_type_id' => $validated['document_type_id'],
                    'attachment' => $filename,
                    'created_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('upload-shipment-documents.index')
            ->with('success', count($request->file('attachments')) . ' documents uploaded successfully.');
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

    public function getShipments($periodId)
    {
        $shipments = Shipment::where('period_id', $periodId)->get();
        return response()->json($shipments);
    }

}
