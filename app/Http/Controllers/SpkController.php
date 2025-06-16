<?php

namespace App\Http\Controllers;

use App\Models\Spk;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SpkController extends Controller
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

        $spks = Spk::with(['period', 'creator'])
                    ->where('period_id', $activePeriodId)
                    ->latest()
                    ->get();

        return view('spks.index', compact('spks'));
    }


    public function create()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        return view('spks.create', compact('activePeriodId'));
    }


        public function store(Request $request)
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $request->validate([
            'spk_number' => 'required|string',
            'spk_date' => 'required|date',
            'spk_file' => 'required|mimes:pdf|max:2048',
        ]);

        $filePath = $request->file('spk_file')->store('spk_files', 'public');

        Spk::create([
            'period_id' => $activePeriodId,
            'spk_number' => $request->spk_number,
            'spk_date' => $request->spk_date,
            'spk_file' => $filePath,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('spks.index')->with('success', 'SPK created successfully.');
    }


    public function edit($id)
    {
        $activePeriodId = session('active_period_id');

        // Cari SPK hanya dalam periode aktif
        $spk = Spk::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        return view('spks.edit', compact('spk', 'activePeriodId'));
    }

    public function update(Request $request, $id)
    {
        $activePeriodId = session('active_period_id');

        // Pastikan SPK yang diupdate berada dalam periode aktif
        $spk = Spk::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        $request->validate([
            'period_id' => 'required|exists:periodes,id',
            'spk_number' => 'required|string',
            'spk_date' => 'required|date',
            'spk_file' => 'nullable|mimes:pdf|max:2048',
        ]);

        $data = $request->only(['period_id', 'spk_number', 'spk_date']);

        if ($request->hasFile('spk_file')) {
            if ($spk->spk_file) {
                Storage::disk('public')->delete($spk->spk_file);
            }
            $data['spk_file'] = $request->file('spk_file')->store('spk_files', 'public');
        }

        $spk->update($data);

        return redirect()->route('spks.index')->with('success', 'SPK updated successfully.');
    }

    public function destroy($id)
    {
        $activePeriodId = session('active_period_id');

        $spk = Spk::where('id', $id)
            ->where('period_id', $activePeriodId)
            ->firstOrFail();

        if ($spk->spk_file) {
            Storage::disk('public')->delete($spk->spk_file);
        }

        $spk->delete();

        return redirect()->route('spks.index')->with('success', 'SPK deleted successfully.');
    }
}
