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

class ShipmentController extends Controller
{
    public function index()
    {
        $shipments = Shipment::with(['period', 'termin', 'vessel', 'spk', 'fuel', 'creator'])->latest()->get();
        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        return view('shipments.create', [
            'periodes' => Periode::all(),
            'termins' => Termin::all(),
            'vessels' => Vessel::all(),
            'spks' => Spk::all(),
            'fuels' => Fuel::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:periodes,id',
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

        Shipment::create($request->all() + [
            'created_by' => Auth::id()
        ]);

        return redirect()->route('shipments.index')->with('success', 'Shipment created successfully.');
    }

    public function edit(Shipment $shipment)
    {
        return view('shipments.edit', [
            'shipment' => $shipment,
            'periodes' => Periode::all(),
            'termins' => Termin::all(),
            'vessels' => Vessel::all(),
            'spks' => Spk::all(),
            'fuels' => Fuel::all(),
        ]);
    }

    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'period_id' => 'required|exists:periodes,id',
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

        $shipment->update($request->all());

        return redirect()->route('shipments.index')->with('success', 'Shipment updated successfully.');
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('shipments.index')->with('success', 'Shipment deleted successfully.');
    }

    public function getTermins($periodId)
    {
        $termins = Termin::where('period_id', $periodId)->get();

        return response()->json($termins);
    }

}

