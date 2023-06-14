<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterUsersController;
use App\Http\Controllers\API\ExistingUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/patient', [RegisterUsersController::class, 'registerPatient']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgot']);
Route::post('/check-code', [AuthController::class, 'checkCode']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::post('/register/dentist', [RegisterUsersController::class, 'registerDentist']);
    Route::post('/register/admin', [RegisterUsersController::class, 'registerAdmin']);
    Route::get('/get-all-users', [ExistingUserController::class, 'getAllUsers']);
    Route::get('/get-user/{id}', [ExistingUserController::class, 'getUserById']);
    Route::get('/get-users/{id}', [ExistingUserController::class, 'getUsersByRol']);
    // AYUDA A QUE CADA USUARIO ACTUALICE SU INFORMACIÓN
    Route::post('/update-user', [ExistingUserController::class, 'updateLoggedInUser']);
    Route::post('/update-user/{id}', [ExistingUserController::class, 'updateUserById']);
    Route::delete('/delete-user/{id}', [ExistingUserController::class, 'deleteUserById']);
});