<?php

namespace App\Http\Controllers;

use App\Models\Termin;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TerminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $termins = Termin::with('period')
                    ->where('period_id', $activePeriodId)
                    ->latest()
                    ->get();

        return view('termins.index', compact('termins'));
    }


    public function create()
    {
        $periodes = Periode::all();
        return view('termins.create', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'period_id' => 'required|exists:periodes,id',
            'termin_number' => 'required|integer|min:1',
        ]);

        Termin::create([
            'period_id' => $request->period_id,
            'termin_number' => $request->termin_number,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('termins.index')->with('success', 'Termin created successfully.');
    }

    public function edit(Termin $termin)
    {
        $periodes = Periode::all();
        return view('termins.edit', compact('termin', 'periodes'));
    }

    public function update(Request $request, Termin $termin)
    {
        $request->validate([
            'period_id' => 'required|exists:periodes,id',
            'termin_number' => 'required|integer|min:1',
        ]);

        $termin->update([
            'period_id' => $request->period_id,
            'termin_number' => $request->termin_number,
        ]);

        return redirect()->route('termins.index')->with('success', 'Termin updated successfully.');
    }

    public function destroy(Termin $termin)
    {
        $termin->delete();
        return redirect()->route('termins.index')->with('success', 'Termin deleted successfully.');
    }
}
