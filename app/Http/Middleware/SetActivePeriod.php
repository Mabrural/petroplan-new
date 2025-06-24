<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Periode;
use Illuminate\Support\Facades\Auth;

class SetActivePeriod
{
    public function handle($request, Closure $next)
    {
        // Share data periode ke semua view
        $allPeriods = \App\Models\Periode::orderBy('year', 'desc')->get();
        \View::share('allPeriods', $allPeriods);

        // Hanya jalankan pengecekan jika user sudah login
        if (Auth::check()) {
            $activePeriodId = session('active_period_id');
            $activePeriod = $activePeriodId ? \App\Models\Periode::find($activePeriodId) : null;

            \View::share('activePeriodId', $activePeriodId);
            \View::share('activePeriod', $activePeriod);

            // Cegah akses ke halaman lain jika belum memilih periode
            $uri = $request->path(); // e.g. "select-period", "set-period"

            if (!$activePeriodId && !in_array($uri, ['select-period', 'set-period'])) {
                return redirect()->route('select.period');
            }
        }

        return $next($request);
    }
}
