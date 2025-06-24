<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Spk;
use App\Models\Termin;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Periode;

class DashboardController extends Controller
{
    public function index()
    {
        $activePeriodId = session('active_period_id');

        // Ambil semua periode untuk modal
        $allPeriods = Periode::orderBy('year', 'desc')->get();

        if (!$activePeriodId) {
            // Default value (kosong) untuk menghindari error
            $totalVessels = 0;
            $totalSPK = 0;
            $totalTermin = 0;
            $totalShipment = 0;
            $onlineUsers = 0;

            return view('dashboard.index', compact(
                'totalVessels',
                'totalSPK',
                'totalTermin',
                'totalShipment',
                'onlineUsers',
                'allPeriods'
            ));
        }

        // Data ringkasan jika periodenya sudah dipilih
        $totalVessels = Vessel::count();
        $totalSPK = Spk::where('period_id', $activePeriodId)->count();
        $totalTermin = Termin::where('period_id', $activePeriodId)->count();
        $totalShipment = Shipment::where('period_id', $activePeriodId)->count();
        $onlineUsers = 17;

        return view('dashboard.index', compact(
            'totalVessels',
            'totalSPK',
            'totalTermin',
            'totalShipment',
            'onlineUsers',
            'allPeriods'
        ));
    }

    public function vesselActivityChart()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        $activityData = Shipment::select('vessel_id', DB::raw('COUNT(*) as shipment_count'))
            ->where('period_id', $activePeriodId)
            ->groupBy('vessel_id')
            ->with('vessel')
            ->get();

        $labels = $activityData->pluck('vessel.vessel_name');
        $counts = $activityData->pluck('shipment_count');

        return view('reports.vessel-activity-chart', compact('labels', 'counts'));
    }

    public function fuelUsageAnalysis()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        // Group volume per vessel
        $usageData = Shipment::select('vessel_id', DB::raw('SUM(volume) as total_volume'))
            ->where('period_id', $activePeriodId)
            ->groupBy('vessel_id')
            ->with('vessel')
            ->get();

        $labels = $usageData->pluck('vessel.vessel_name');
        $volumes = $usageData->pluck('total_volume');

        return view('reports.fuel-usage-analysis', compact('labels', 'volumes'));
    }

    public function fuelUsageTrend()
{
    $activePeriodId = session('active_period_id');

    if (!$activePeriodId) {
        return redirect()->route('set.period')->with('error', 'Please select a period first.');
    }

    // Ambil data berdasarkan tanggal completion
    $shipments = Shipment::where('period_id', $activePeriodId)
        ->whereNotNull('completion_date')
        ->with('vessel')
        ->orderBy('completion_date')
        ->get();

    $grouped = [];

    foreach ($shipments as $shipment) {
        $vesselName = $shipment->vessel->vessel_name ?? 'Unknown Vessel';
        $date = \Carbon\Carbon::parse($shipment->completion_date)->toDateString();

        $grouped[$vesselName][$date] = ($grouped[$vesselName][$date] ?? 0) + $shipment->volume;
    }

    $labels = collect($shipments)->pluck('completion_date')->unique()->sort()->values()->map(fn($d) => \Carbon\Carbon::parse($d)->toDateString());

    $datasets = [];
    foreach ($grouped as $vessel => $dateVolumes) {
        $data = [];
        foreach ($labels as $labelDate) {
            $data[] = $dateVolumes[$labelDate] ?? 0;
        }

        $datasets[] = [
            'label' => $vessel,
            'data' => $data,
            'borderColor' => 'rgba(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ',1)',
            'backgroundColor' => 'rgba(' . rand(0,255) . ',' . rand(0,255) . ',' . rand(0,255) . ',0.2)',
            'fill' => true,
        ];
    }

    return view('reports.fuel-usage-trend', [
        'labels' => $labels,
        'datasets' => $datasets,
    ]);
}

}
