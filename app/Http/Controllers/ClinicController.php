<?php

namespace App\Http\Controllers;

use App\Models\ClinicWaitTime;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicController
{
    /**
     * Show the clinic profile edit page.
     *
     * @return \Illuminate\View\View
     */
    public function editProfile()
    {
        return view('clinic.edit-profile');
    }

    /**
     * Update the authenticated user's profile information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Validate the incoming request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
        ]);

        // Update the user record
        $user = auth()->user();
        $user->update($validatedData);

        // Redirect back with a success message
        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Set the waiting time and clinic parameters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWaitingTime(Request $request)
    {
        $user = Auth::user();
        $clinic = $user->clinic;

        if (!$clinic) {
            return response()->json(['message' => 'Clinic not found'], 404);
        }

        // Validate incoming data
        $validated = $request->validate([
            'fixed_wait_time' => 'required|integer|min:0',
            'waiting_time' => 'required|integer|min:0',
            'fixed_wait' => 'required|boolean',  // Ensure it’s a boolean
        ]);

        try {
            // Convert the 'fixed_wait' field to a boolean (true/false)
            $fixedWait = filter_var($validated['fixed_wait'], FILTER_VALIDATE_BOOLEAN);

            // Store or update the clinic wait time record
            $waitTime = ClinicWaitTime::updateOrCreate(
                ['clinic_id' => $clinic->id],
                [
                    'fixed_wait_time' => $validated['fixed_wait_time'],
                    'time_per_patient' => $validated['waiting_time'],
                    'fixed_wait' => $fixedWait,
                ]
            );

            // Return a JSON response to the AJAX request
            return response()->json([
                'message' => 'Wait times updated successfully',
                'data' => $waitTime
            ]);

        } catch (\Exception $e) {
            // Return error response if there's an exception
            return response()->json(['message' => 'An error occurred', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the status of a patient.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        // Find the patient by ID
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        // Validate the status
        $validated = $request->validate([
            'status' => 'required|in:waiting,in_service,checked_out',
        ]);

        // Update the patient's most recent status
        $patient->most_recent_status()->update([
            'status' => $validated['status'],
        ]);

        // Return success message
        return response()->json(['message' => 'Patient status updated successfully']);
    }

    /**
     * Delete a patient by ID.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePatient($id)
    {
        // Find the patient by ID
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        // Delete the patient record
        $patient->delete();

        // Return success message
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
