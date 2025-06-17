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
    public function index(Request $request)
    {
        $activePeriodId = session('active_period_id');
        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $terminsQuery = Termin::with(['period', 'creator'])
            ->where('period_id', $activePeriodId)
            ->orderBy('termin_number', 'asc');

        if ($search) {
            $terminsQuery->where('termin_number', 'like', '%' . $search . '%');
        }

        $termins = $terminsQuery->paginate($perPage)->appends($request->query());
        $totalTermins = $termins->total();

        return view('termins.index', compact('termins', 'totalTermins', 'perPage', 'search'));
    }



    public function create()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        return view('termins.create', compact('activePeriodId'));
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

    public function edit($id)
    {
        $activePeriodId = session('active_period_id');

        // Cari termin hanya dalam periode aktif
        $termin = Termin::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $allPeriods = Periode::all();

        return view('termins.edit', compact('termin', 'allPeriods'));
    }

    public function update(Request $request, $id)
    {
        $activePeriodId = session('active_period_id');

        $termin = Termin::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $validated = $request->validate([
            'termin_number' => 'required|integer|min:1',
            // validasi lainnya jika perlu
        ]);

        $termin->update($validated);

        return redirect()->route('termins.index')->with('success', 'Termin updated successfully.');
    }



    public function destroy($id)
    {
        $activePeriodId = session('active_period_id');

        // Hanya hapus termin yang sesuai dengan periode aktif
        $termin = Termin::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $termin->delete();

        return redirect()->route('termins.index')->with('success', 'Termin deleted successfully.');
    }

}
