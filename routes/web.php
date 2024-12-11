<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\PatientAuthController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PatientTrafficController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::view('/', 'welcome');
Route::view('about', 'welcome')->name('about');
Route::view('contact', 'welcome')->name('contact');

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

// Clinic Routes
Route::view('findClinic', 'clinic.index')->name('findClinic');
Route::get('/profile/edit', [ClinicController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile/edit', [ClinicController::class, 'update'])->name('profile.update');

// Patient Routes (Authenticated)
Route::middleware('auth')->group(function () {
    Route::get('patient/edit-profile', [PatientAuthController::class, 'editProfile'])->name('patient.edit-profile');
    Route::put('patient/update', [PatientAuthController::class, 'updateProfile'])->name('patient.update');
});

// API Routes for Patients and Traffic Management
Route::prefix('api')->group(function () {
    Route::get('/patients', [PatientController::class, 'getPatients']);
    Route::get('/patient-traffic', [PatientTrafficController::class, 'getPatientTraffic']);
    Route::post('/check-in', [PatientController::class, 'checkIn']);
    Route::post('/set-waiting-time', [ClinicController::class, 'setWaitingTime']);
    Route::put('/patients/{id}/status', [PatientController::class, 'updateStatus']);
    Route::delete('/patients/{id}', [PatientController::class, 'deletePatient']);
});
