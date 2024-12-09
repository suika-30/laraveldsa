<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PrescriptionController extends Controller
{
    // Create a new prescription
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'patients_id' => 'required|exists:patients,id',  // Ensuring the patient exists
            'med_id' => 'required|exists:medicines,id',       // Ensuring the medicine exists
            'qty_taken' => 'required|integer|min:1',          // Ensuring quantity is positive integer
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Find the medicine
        $medicine = Medicine::find($request->med_id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404); // 404 Not Found
        }

        // Check if there is enough stock
        if ($medicine->qty < $request->qty_taken) {
            return response()->json(['message' => 'Not enough medicine in stock'], 400); // 400 Bad Request
        }

        // Create the prescription record
        $prescription = Prescription::create([
            'patients_id' => $request->patients_id,
            'med_id' => $request->med_id,
            'prescription_date' => now(),
            'qty_taken' => $request->qty_taken,
        ]);

        // Deduct the quantity of the medicine
        $medicine->qty -= $request->qty_taken;
        $medicine->save();

        // Return success response
        return response()->json([
            'message' => 'Prescription created and medicine quantity updated successfully!',
            'prescription' => $prescription,
        ], 201);
    }

    // Update an existing prescription
    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'patients_id' => 'required|exists:patients,id',
            'med_id' => 'required|exists:medicines,id',
            'prescription_date' => 'required|date',
            'qty_taken' => 'required|integer|min:1',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the prescription by ID
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404); // 404 Not Found
        }

        // Find the medicine
        $medicine = Medicine::find($request->med_id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404); // 404 Not Found
        }

        // Check if there is enough stock (considering previous deduction)
        if ($medicine->qty + $prescription->qty_taken < $request->qty_taken) {
            return response()->json(['message' => 'Not enough medicine in stock'], 400); // 400 Bad Request
        }

        // Update prescription details
        $prescription->update($request->all());

        // Update the medicine quantity
        $medicine->qty = $medicine->qty + $prescription->qty_taken - $request->qty_taken;
        $medicine->save();

        // Return success response
        return response()->json([
            'message' => 'Prescription updated and medicine quantity adjusted successfully!',
            'prescription' => $prescription,
        ], 200);
    }

    // Delete a prescription
    public function destroy($id)
    {
        // Find the prescription by ID
        $prescription = Prescription::find($id);
        if (!$prescription) {
            return response()->json(['message' => 'Prescription not found'], 404); // 404 Not Found
        }

        // Find the corresponding medicine
        $medicine = Medicine::find($prescription->med_id);
        if ($medicine) {
            // Return the quantity back to the medicine
            $medicine->qty += $prescription->qty_taken;
            $medicine->save();
        }

        // Delete the prescription
        $prescription->delete();

        // Return success response
        return response()->json(['message' => 'Prescription deleted and medicine quantity restored'], 200);
    }
}
