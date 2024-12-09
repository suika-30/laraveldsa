<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicineController extends Controller
{
    // Create a new medicine
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Anti-hypertensive,Antithrombotic,Lipid Modifying Agent,Oral Hypoglycemic Agent',
            'dosage' => 'required|string|max:255',
            'qty' => 'required|integer|min:0', // Must be a non-negative integer
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Create the medicine
        $medicine = Medicine::create([
            'name' => $request->name,
            'category' => $request->category,
            'dosage' => $request->dosage,
            'qty' => $request->qty,
        ]);

        // Return success response
        return response()->json([
            'message' => 'Medicine created successfully!',
            'medicine' => $medicine,
        ], 201);
    }

    // Update an existing medicine
    public function update(Request $request, $id)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|in:Anti-hypertensive,Antithrombotic,Lipid Modifying Agent,Oral Hypoglycemic Agent',
            'dosage' => 'required|string|max:255',
            'qty' => 'required|integer|min:0',
        ]);

        // If validation fails, return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422); // 422 Unprocessable Entity
        }

        // Find the medicine by ID
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404); // 404 Not Found
        }

        // Update medicine details
        $medicine->update($request->all());

        // Return success response
        return response()->json([
            'message' => 'Medicine updated successfully!',
            'medicine' => $medicine,
        ], 200);
    }

    // Delete a medicine
    public function destroy($id)
    {
        // Find the medicine by ID
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404); // 404 Not Found
        }

        // Delete the medicine
        $medicine->delete();

        // Return success response
        return response()->json(['message' => 'Medicine deleted successfully'], 200);
    }
}
