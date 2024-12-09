<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

// User Routes (access user data)
Route::get('/user', function (Request $request) {
    return $request->user();
});

// Get specific user by ID
Route::get('/users/{id}', [UserController::class, 'index']);

// Patient Routes (CRUD operations)
Route::resource('patients', PatientController::class);

// Medicine Routes (CRUD operations)
Route::resource('medicines', MedicineController::class);

// Prescription Routes (CRUD operations)
Route::resource('prescriptions', PrescriptionController::class);