<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodes = Periode::orderByDesc('year')->get();
        return view('period.index', compact('periodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('period.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'year'       => 'required|integer|digits:4',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'is_active'  => 'true',
        ]);

        $validated['created_by'] = Auth::id();

        Periode::create($validated);

        return redirect()->route('period-list.index')->with('success', 'Period created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $periode = Periode::findOrFail($id);
        return view('period.edit', compact('periode'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'year'       => 'required|integer|digits:4',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $periode = Periode::findOrFail($id);
        $periode->update($validated);

        return redirect()->route('period-list.index')->with('success', 'Period successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->delete();

        return redirect()->route('period-list.index')->with('success', 'Period successfully deleted.');
    }

    public function activate($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->is_active = true;
        $periode->save();

        return redirect()->back()->with('success', $periode->name . ' has been activated.');
    }

    public function deactivate($id)
    {
        $periode = Periode::findOrFail($id);
        $periode->is_active = false;
        $periode->save();

        return redirect()->back()->with('success', $periode->name . ' has been deactivated.');
    }
}
