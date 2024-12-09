<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    // Validate patient data
    private function validatePatientData(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'sex' => 'required|in:Male,Female',
            'address' => 'required|string|max:255',
            'ailment' => 'required|string|max:255',
        ]);
    }

    // Create a new patient
    public function store(Request $request)
    {
        // Validate input
        $validator = $this->validatePatientData($request);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Create the patient with a unique patient_id
        $patient = Patient::create([
            'patient_id' => Patient::max('patient_id') + 1, // Generate unique patient_id
            'name' => $request->name,
            'birthdate' => $request->birthdate,
            'sex' => $request->sex,
            'address' => $request->address,
            'ailment' => $request->ailment,
            'date_registered' => now(),
        ]);

        // Return success response
        return response()->json([
            'message' => 'Patient created successfully!',
            'patient' => $patient,
        ], 201);
    }

    // Update an existing patient
    public function update(Request $request, $id)
    {
        // Validate input
        $validator = $this->validatePatientData($request);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Find the patient by ID
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404); // 404 Not Found
        }

        // Update patient details
        $patient->update($request->all());

        // Return success response
        return response()->json([
            'message' => 'Patient updated successfully!',
            'patient' => $patient,
        ], 200);
    }

    // Delete a patient
    public function destroy($id)
    {
        // Find the patient by ID
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found'], 404); // 404 Not Found
        }

        // Delete the patient
        $patient->delete();

        // Return success response
        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }
}
