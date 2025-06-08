<?php

use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\HospitalController;
use App\Http\Controllers\BookingTransactionController;
use App\Http\Controllers\MyOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//SpecialistController
Route::apiResource('specialists', SpecialistController::class);
//DoctorController
Route::apiResource('doctors', DoctorController::class);
//HospitalController
Route::apiResource('hospitals', HospitalController::class);

//post HospitalSpecialist
Route::post('hospitals/{hospital}/specialists', [HospitalController::class, 'attach']);
//delete HospitalSpecialist
Route::delete('hospitals/{hospital}/specialists/{specialist}', [HospitalController::class, 'detach']);

//BookingTransactionController
Route::apiResource('transactions', BookingTransactionController::class);
Route::patch('/transactions/{id}/status', [BookingTransactionController::class, 'updateStatus']);

//doctor-filter
Route::get('/doctors-filter', [DoctorController::class, 'filterBySpecialistAndHospital']);
//available-doctors
Route::get('/doctors/{doctorId}/available-slots', [DoctorController::class, 'availableSlots']);


Route::get('my-orders', [MyOrderController::class, 'index']);
//post
Route::post('my-orders', [MyOrderController::class, 'store']);
//show
Route::get('my-orders/{id}', [MyOrderController::class, 'show']);
