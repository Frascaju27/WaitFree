<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\PatientStatus;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Fetch all patients with their most recent status.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatients()
    {
        // Fetch patients with their most recent status, ordered by ID in descending order
        $patients = Patient::with('mostRecentStatus')
            ->orderBy('id', 'desc')
            ->get();

        // Return the patients data as a JSON response
        return response()->json($patients);
    }

    /**
     * Check in a new patient.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(Request $request)
    {
        // Validate incoming data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'status' => 'required|string|in:Waiting,In Service,Checked Out',
            'notes' => 'nullable|string|max:1000',  // Notes are optional
        ]);

        // Create a new patient record
        $patient = Patient::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'clinic_id' => 1,  // Clinic ID is hardcoded, adjust as needed
        ]);

        // Create the associated patient status record
        PatientStatus::create([
            'patient_id' => $patient->id,
            'clinic_id' => 1,  // Clinic ID is hardcoded, adjust as needed
            'status' => $validated['status'],
            'status_changed_at' => now(),
            'notes' => $validated['notes'],  // Store notes if provided
        ]);

        // Return success response
        return response()->json(['message' => 'Patient checked in successfully!'], 200);
    }

    /**
     * Update the status of an existing patient.
     *
     * @param int $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id, Request $request)
    {
        // Find the patient status record by patient ID
        $patientStatus = PatientStatus::where('patient_id', $id)->first();

        // Check if the patient status exists
        if (!$patientStatus) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        // Validate the new status
        $validated = $request->validate([
            'status' => 'required|in:Waiting,In Service,Checked Out',
        ]);

        // Update the patient status
        $patientStatus->update([
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
        // Find the patient record by ID
        $patient = Patient::find($id);

        // Check if the patient exists
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404);
        }

        // Delete the patient record
        $patient->delete();

        // Return success message
        return response()->json(['message' => 'Patient deleted successfully']);
    }
}
