<?php

namespace App\Http\Controllers;

use App\Models\ClinicWaitTime;
use App\Models\PatientStatus;
use Illuminate\Support\Facades\Auth;

class PatientTrafficController extends Controller
{
    /**
     * Fetch patient traffic counts and estimated wait time for the clinic.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientTraffic()
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        // Default values for patient counts
        $statusCounts = PatientStatus::where('clinic_id', $clinic->id)
            ->selectRaw('
                sum(status = "waiting") as waiting,
                sum(status = "In Service") as in_service,
                sum(status = "Checked Out") as checked_out
            ')
            ->first();

        // Get wait time details or set default values
        $waitTime = ClinicWaitTime::where('clinic_id', $clinic->id)->first();
        $timePerPatient = $waitTime ? $waitTime->time_per_patient : 0;
        $estimatedWait = 0;

        // If wait time is set, calculate the estimated wait time
        if ($waitTime) {
            $activeStatusCount = PatientStatus::where('clinic_id', $clinic->id)
                ->where('status', '!=', 'Checked Out')
                ->count();

            $estimatedWait = $activeStatusCount * $timePerPatient;
        }

        // Return the data as JSON
        return response()->json([
            'waiting' => $statusCounts->waiting ?? 0,
            'in_service' => $statusCounts->in_service ?? 0,
            'checked_out' => $statusCounts->checked_out ?? 0,
            'estimated_wait' => $estimatedWait,
        ]);
    }
}
