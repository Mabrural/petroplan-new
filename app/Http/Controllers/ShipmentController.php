<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Periode;
use App\Models\Termin;
use App\Models\Vessel;
use App\Models\Spk;
use App\Models\Fuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentType;
use App\Models\UploadShipmentDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $perPage = $request->input('per_page', 10); // default 10
        $shipments = Shipment::with(['period', 'termin', 'vessel', 'spk', 'fuel', 'creator'])
            ->where('period_id', $activePeriodId);

        if ($request->filled('termin_id')) {
            $shipments->where('termin_id', $request->termin_id);
        }

        if ($request->filled('spk_id')) {
            $shipments->where('spk_id', $request->spk_id);
        }

        if ($request->filled('vessel_id')) {
            $shipments->where('vessel_id', $request->vessel_id);
        }

        if ($request->filled('fuel_id')) {
            $shipments->where('fuel_id', $request->fuel_id);
        }

        if ($request->filled('status_shipment')) {
            $shipments->where('status_shipment', $request->status_shipment);
        }

        $shipments = $shipments->latest()->paginate($perPage)->withQueryString();

        $totalShipments = $shipments->total();


        $termins = Termin::where('period_id', $activePeriodId)->get();
        $spks = Spk::where('period_id', $activePeriodId)->get();
        $vessels = Vessel::all();
        $fuels = Fuel::all();
        $statuses = ['in_progress', 'cancelled', 'completed', 'filling_completed'];

        return view('shipments.index', compact(
            'shipments', 'termins', 'spks', 'vessels', 'fuels', 'statuses', 'perPage', 'totalShipments'
        ));
    }

    public function create()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        // Next shipment number
        $shipmentCount = Shipment::where('period_id', $activePeriodId)->count();
        $nextShipmentNumber = $shipmentCount + 1;

        // Get related data
        $termins = Termin::where('period_id', $activePeriodId)->get();
        $spks = Spk::where('period_id', $activePeriodId)->orderBy('created_at', 'desc')->get();
        $vessels = Vessel::all();
        $fuels = Fuel::all();

        // Determine the most recent entries
        $latestTerminId = $termins->last()?->id;
        $latestSpkId = $spks->first()?->id;

        return view('shipments.create', compact(
            'termins',
            'spks',
            'vessels',
            'fuels',
            'activePeriodId',
            'nextShipmentNumber',
            'latestTerminId',
            'latestSpkId'
        ));
    }




    public function store(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $request->validate([
            'termin_id' => 'required|exists:termins,id',
            'shipment_number' => 'required|string',
            'vessel_id' => 'required|exists:vessels,id',
            'spk_id' => 'required|exists:spks,id',
            'location' => 'required|string',
            'fuel_id' => 'required|exists:fuels,id',
            'volume' => 'required|integer',
            'p' => 'nullable|integer',
            'a' => 'nullable|integer',
            'b' => 'nullable|integer',
            'completion_date' => 'required|date',
            'lo' => 'nullable|string',
            'status_shipment' => 'required|in:in_progress,cancelled,completed,filling_completed',
        ]);

        Shipment::create([
            'period_id' => $activePeriodId,
            'termin_id' => $request->termin_id,
            'shipment_number' => $request->shipment_number,
            'vessel_id' => $request->vessel_id,
            'spk_id' => $request->spk_id,
            'location' => $request->location,
            'fuel_id' => $request->fuel_id,
            'volume' => $request->volume,
            'p' => $request->p,
            'a' => $request->a,
            'b' => $request->b,
            'completion_date' => $request->completion_date,
            'lo' => $request->lo,
            'status_shipment' => $request->status_shipment,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully.');
    }


    public function edit($id)
    {
        $activePeriodId = session('active_period_id');

        $shipment = Shipment::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        // Format tanggal di controller menggunakan strtotime
        $shipment->completion_date = date('Y-m-d', strtotime($shipment->completion_date));

        $termins = Termin::where('period_id', $activePeriodId)->get();
        $spks = Spk::where('period_id', $activePeriodId)->orderBy('id', 'desc')->get();

        return view('shipments.edit', [
            'shipment' => $shipment,
            'termins' => $termins,
            'spks' => $spks,
            'vessels' => Vessel::all(),
            'fuels' => Fuel::all(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $activePeriodId = session('active_period_id');

        $shipment = Shipment::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $validated = $request->validate([
            'termin_id' => 'required|exists:termins,id',
            'shipment_number' => 'required|string',
            'vessel_id' => 'required|exists:vessels,id',
            'spk_id' => 'required|exists:spks,id',
            'location' => 'required|string',
            'fuel_id' => 'required|exists:fuels,id',
            'volume' => 'required|integer',
            'p' => 'nullable|integer',
            'a' => 'nullable|integer',
            'b' => 'nullable|integer',
            'completion_date' => 'required|date',
            'lo' => 'nullable|string',
            'status_shipment' => 'required|in:in_progress,cancelled,completed,filling_completed',
        ]);

        $shipment->update($validated);

        return redirect()->route('shipments.index')->with('success', 'Shipment updated successfully.');
    }


    public function destroy($id)
    {
        $activePeriodId = session('active_period_id');

        $shipment = Shipment::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $shipment->delete();

        return redirect()->route('shipments.index')->with('success', 'Shipment deleted successfully.');
    }


    public function getTermins($periodId)
    {
        $termins = Termin::where('period_id', $periodId)->get();

        return response()->json($termins);
    }

    public function showDetails($id)
    {
        $activePeriodId = session('active_period_id');
        
        $shipment = Shipment::with(['period', 'termin', 'vessel', 'spk', 'fuel', 'creator'])
            ->where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        return view('shipments.details', compact('shipment'));
    }

    public function uploadDocuments($id)
    {
        $shipment = Shipment::with(['termin', 'vessel', 'spk', 'fuel'])->findOrFail($id);

        $activePeriodId = session('active_period_id');
        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        // Hanya ambil documentTypes yang dibuat dalam periode ini (opsional, jika dibutuhkan filter)
        $documentTypes = DocumentType::all();

        // Ambil dokumen yang sudah diupload dalam konteks shipment ini
        $uploadedDocuments = UploadShipmentDocument::where('shipment_id', $id)
            ->where('period_id', $activePeriodId)
            ->pluck('attachment', 'document_type_id');

        return view('shipments.uploads', compact('shipment', 'documentTypes', 'uploadedDocuments'));
    }


    public function storeUploadedDocument(Request $request, $id)
    {
        $shipment = Shipment::findOrFail($id);
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $request->validate([
            'document_type_id' => 'required|exists:document_types,id',
            'attachment.*' => 'required|file|max:5120|mimes:png,jpg,jpeg,pdf',
        ]);

        foreach ($request->file('attachment') as $file) {
            // Ambil nama asli dan beri timestamp agar unik
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();
            $safeName = Str::slug($originalName); // Biar rapi dan aman
            $filename = $safeName . '-' . now()->format('YmdHis') . '.' . $extension;

            // Simpan file dengan nama yang sudah diformat
            $path = $file->storeAs('shipment_documents', $filename, 'public');

            UploadShipmentDocument::create([
                'shipment_id' => $shipment->id,
                'document_type_id' => $request->document_type_id,
                'period_id' => $activePeriodId,
                'attachment' => $path,
                'created_by' => auth()->id(),
            ]);
        }

        return back()->with('success', 'Documents uploaded successfully.');
    }
    // public function storeUploadedDocument(Request $request, $id)
    // {
    //     $shipment = Shipment::findOrFail($id);
    //     $activePeriodId = session('active_period_id');

    //     if (!$activePeriodId) {
    //         return redirect()->route('set.period')->with('error', 'Please select a period first.');
    //     }

    //     $request->validate([
    //         'document_type_id' => 'required|exists:document_types,id',
    //         'attachment.*' => 'required|file|max:5120|mimes:png,jpg,jpeg,pdf',
    //     ]);

    //     // Check total size of all files
    //     $totalSize = 0;
    //     foreach ($request->file('attachment') as $file) {
    //         $totalSize += $file->getSize();
    //     }

    //     if ($totalSize > 100 * 1024 * 1024) { // 100MB
    //         return back()->with('error', 'Total upload size exceeds 100MB limit');
    //     }

    //     foreach ($request->file('attachment') as $file) {
    //         // Generate unique filename
    //         $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    //         $extension = $file->getClientOriginalExtension();
    //         $safeName = Str::slug($originalName);
    //         $filename = $safeName . '-' . now()->format('YmdHis') . '.' . $extension;

    //         // Store file
    //         $path = $file->storeAs('shipment_documents', $filename, 'public');

    //         UploadShipmentDocument::create([
    //             'shipment_id' => $shipment->id,
    //             'document_type_id' => $request->document_type_id,
    //             'period_id' => $activePeriodId,
    //             'attachment' => $path,
    //             'created_by' => auth()->id(),
    //         ]);
    //     }

    //     return back()->with('success', 'Documents uploaded successfully.');
    // }


    public function destroyUploadedDocument($id, $documentId)
    {
        $document = UploadShipmentDocument::where('shipment_id', $id)
            ->where('id', $documentId)
            ->firstOrFail();

        // Hapus file dari storage
        if ($document->attachment && Storage::disk('public')->exists($document->attachment)) {
            Storage::disk('public')->delete($document->attachment);
        }

        $document->delete();

        return back()->with('success', 'Document deleted successfully.');
    }

}

