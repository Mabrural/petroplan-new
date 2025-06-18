<?php

namespace App\Http\Controllers;

use App\Models\Fuel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FuelController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $fuelsQuery = Fuel::with('creator')->orderBy('id', 'asc');

        if ($search) {
            $fuelsQuery->where('fuel_type', 'like', '%' . $search . '%');
        }

        $fuels = $fuelsQuery->paginate($perPage)->withQueryString();
        $totalFuels = $fuels->total();

        return view('fuel.index', compact('fuels', 'search', 'perPage', 'totalFuels'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fuel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'fuel_type' => 'required|string|max:255',
        ]);

        Fuel::create([
            'fuel_type' => $request->fuel_type,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('fuels.index')->with('success', 'Fuel created successfully.');
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
        $fuel = Fuel::find($id);

        if (!$fuel) {
            return redirect()->route('fuels.index')->with('error', 'Fuel not found.');
        }

        return view('fuel.edit', compact('fuel'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $fuel = Fuel::find($id);

        if (!$fuel) {
            return redirect()->route('fuels.index')->with('error', 'Fuel not found.');
        }

        $request->validate([
            'fuel_type' => 'required|string|max:255',
        ]);

        $fuel->update(['fuel_type' => $request->fuel_type]);

        return redirect()->route('fuels.index')->with('success', 'Fuel updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $fuel = Fuel::find($id);

        if (!$fuel) {
            return redirect()->route('fuels.index')->with('error', 'Fuel not found.');
        }

        $fuel->delete();

        return redirect()->route('fuels.index')->with('success', 'Fuel deleted successfully.');
    }

}
