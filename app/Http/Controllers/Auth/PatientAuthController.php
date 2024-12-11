<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class PatientAuthController
{
    public function editProfile()
    {
        return view('patient.edit-profile');
    }


    public function updateProfile(Request $request)
    {
        $patient = Auth::guard('patient')->user();

        // Validate the input data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $patient->id,
            'phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
        ]);

        // Update the patient's profile
        $patient->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'notes' => $request->notes,
        ]);

        return redirect()->route('patient.dashboard')->with('success', 'Profile updated successfully!');
    }

}
