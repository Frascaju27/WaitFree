<?php

namespace App\Http\Controllers;

use App\Models\ClinicWaitTime;
use App\Models\PatientStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the clinic dashboard with wait time information.
     *
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
     */
    public function showDashboard(): View|Factory|Application
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Get clinic's wait time record, or set defaults if not available
        $waitTime = ClinicWaitTime::where('clinic_id', $clinic->id)->first();

        // Set default values if no wait time record exists
        $fixedWaitTime = 10;
        $fixed = true;
        $timePerPatient = 15;
        $estimatedWait = 0;

        // If a wait time record exists, use its values
        if ($waitTime) {
            $statusCount = PatientStatus::where('status', '!=', 'Checked Out')->count();
            $fixedWaitTime = $waitTime->fixed_wait_time;
            $fixed = $waitTime->fixed_wait;
            $timePerPatient = $waitTime->time_per_patient;
            $estimatedWait = $statusCount * $timePerPatient;
        }

        return view('clinic.dashboard', [
            'fixed_wait_time' => $fixedWaitTime,
            'fixed' => $fixed,
            'time_per_patient' => $timePerPatient,
            'estimated_wait' => $estimatedWait,
        ]);
    }
}
