<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VesselController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vessels = Vessel::latest()->paginate(10);
        return view('vessels.index', compact('vessels'));
    }

    public function create()
    {
        return view('vessels.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vessel_name' => 'required|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('vessels', 'public');
        }

        $validated['created_by'] = Auth::id();

        Vessel::create($validated);

        return redirect()->route('vessels.index')->with('success', 'Vessel successfully created.');
    }

    public function edit($id)
    {
        $vessel = Vessel::find($id);

        if (!$vessel) {
            return redirect()->route('vessels.index')->with('error', 'Vessel not found.');
        }

        return view('vessels.edit', compact('vessel'));
    }


    public function update(Request $request, $id)
    {
        $vessel = Vessel::find($id);

        if (!$vessel) {
            return redirect()->route('vessels.index')->with('error', 'Vessel not found.');
        }

        $validated = $request->validate([
            'vessel_name' => 'required|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($vessel->image && Storage::disk('public')->exists($vessel->image)) {
                Storage::disk('public')->delete($vessel->image);
            }
            $validated['image'] = $request->file('image')->store('vessels', 'public');
        }

        $vessel->update($validated);

        return redirect()->route('vessels.index')->with('success', 'Vessel updated successfully.');
    }


    public function destroy($id)
    {
        $vessel = Vessel::find($id);

        if (!$vessel) {
            return redirect()->route('vessels.index')->with('error', 'Vessel not found.');
        }

        try {
            // Hapus file image jika ada
            if ($vessel->image && Storage::disk('public')->exists($vessel->image)) {
                Storage::disk('public')->delete($vessel->image);
            }

            $vessel->delete();

            return redirect()->route('vessels.index')->with('success', 'Vessel deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('vessels.index')->with('error', 'Failed to delete vessel: ' . $e->getMessage());
        }
    }

}
