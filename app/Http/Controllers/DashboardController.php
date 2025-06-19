<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Spk;
use App\Models\Termin;
use App\Models\Vessel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $activePeriodId = session('active_period_id');

        if (!$activePeriodId) {
            return redirect()->route('set.period')->with('error', 'Please select a period first.');
        }

        // Data ringkasan
        $totalVessels = Vessel::count();
        $totalSPK = Spk::where('period_id', $activePeriodId)->count();
        $totalTermin = Termin::where('period_id', $activePeriodId)->count();
        $totalShipment = Shipment::where('period_id', $activePeriodId)->count();

        // Statistik pengguna online hanya dummy (contoh)
        $onlineUsers = 17;

        return view('dashboard.index', compact(
            'totalVessels',
            'totalSPK',
            'totalTermin',
            'totalShipment',
            'onlineUsers'
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
}
