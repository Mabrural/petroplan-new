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

    // Get shipments data with relationships
    $shipments = Shipment::where('period_id', $activePeriodId)
        ->whereNotNull('completion_date')
        ->with(['vessel', 'fuel'])
        ->orderBy('completion_date')
        ->get();

    // Organize data by vessel
    $vesselData = [];
    $colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
        '#858796', '#5a5c69', '#3a3b45', '#2e59d9', '#17a673'
    ];
    
    foreach ($shipments as $shipment) {
        $vesselId = $shipment->vessel_id;
        $vesselName = $shipment->vessel->vessel_name ?? 'Unknown Vessel';
        $fuelType = $shipment->fuel->fuel_type ?? 'Unknown Fuel';
        $date = \Carbon\Carbon::parse($shipment->completion_date);
        
        if (!isset($vesselData[$vesselId])) {
            $colorIndex = count($vesselData) % count($colors);
            $vesselData[$vesselId] = [
                'name' => $vesselName,
                'fuel_type' => $fuelType,
                'color' => $colors[$colorIndex],
                'refills' => [],
                'total_volume' => 0,
                'avg_days_between' => null,
                'next_predicted' => null
            ];
        }
        
        $vesselData[$vesselId]['refills'][] = [
            'date' => $date,
            'date_str' => $date->format('Y-m-d'),
            'volume' => $shipment->volume,
            'location' => $shipment->location
        ];
        $vesselData[$vesselId]['total_volume'] += $shipment->volume;
    }

    // Calculate average days between refills and predict next refill
    foreach ($vesselData as $vesselId => $data) {
        $refills = $data['refills'];
        $count = count($refills);
        
        if ($count > 1) {
            $totalDays = 0;
            for ($i = 1; $i < $count; $i++) {
                $totalDays += $refills[$i]['date']->diffInDays($refills[$i-1]['date']);
            }
            
            $avgDays = $totalDays / ($count - 1);
            $vesselData[$vesselId]['avg_days_between'] = round($avgDays);
            
            // Predict next refill date (last date + average days)
            $lastDate = end($refills)['date'];
            $nextPredicted = $lastDate->copy()->addDays($avgDays);
            $vesselData[$vesselId]['next_predicted'] = $nextPredicted;
        }
    }

    // Prepare data for the main chart
    $chartData = [
        'labels' => [],
        'datasets' => []
    ];

    foreach ($vesselData as $vesselId => $data) {
        $chartData['datasets'][] = [
            'label' => $data['name'],
            'data' => array_map(function($refill) {
                return [
                    'x' => $refill['date_str'],
                    'y' => $refill['volume']
                ];
            }, $data['refills']),
            'backgroundColor' => $data['color'],
            'borderColor' => $data['color'],
            'borderWidth' => 2
        ];
    }

    return view('reports.vessel-fuel-refill-tracking', [
        'vesselData' => $vesselData,
        'chartData' => $chartData
    ]);
}

}
