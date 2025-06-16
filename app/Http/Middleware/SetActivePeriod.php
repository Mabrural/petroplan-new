<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Periode;

class SetActivePeriod
{
    public function handle($request, Closure $next)
    {
        $allPeriods = Periode::orderBy('year', 'desc')->get();

        View::share('allPeriods', $allPeriods);

        $activePeriodId = Session::get('active_period_id');
        $activePeriod = $activePeriodId ? Periode::find($activePeriodId) : null;

        View::share('activePeriodId', $activePeriodId);
        View::share('activePeriod', $activePeriod);

        return $next($request);
    }
}
