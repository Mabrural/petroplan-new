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

class ShipmentSummaryController extends Controller
{
    public function index(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $perPage = $request->input('per_page', 10); // default 10
        $shipments = Shipment::with(['period', 'termin', 'vessel', 'spk', 'fuel', 'creator'])
            ->where('period_id', $activePeriodId)->orderBy('id', 'asc');

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

        return view('reports.shipment-summary-report', compact(
            'shipments', 'termins', 'spks', 'vessels', 'fuels', 'statuses', 'perPage', 'totalShipments'
        ));
    }
}
