<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\RegisterUsersController;
use App\Http\Controllers\API\ExistingUserController;
use App\Http\Controllers\API\MedicalAppointmentController;
use App\Http\Controllers\API\MedicalRecordController;
use App\Http\Controllers\API\ServiceController;
use Cloudinary\Transformation\Rotate;

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
Route::get('/getServices', [ServiceController::class, 'getServices']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);

    Route::post('/register/dentist', [RegisterUsersController::class, 'registerDentist']);
    Route::post('/register/admin', [RegisterUsersController::class, 'registerAdmin']);
    Route::get('/get-all-users', [ExistingUserController::class, 'getAllUsers']);
    Route::get('/get-user/{id_card}', [ExistingUserController::class, 'getUserById']);
    Route::get('/get-users/{id}', [ExistingUserController::class, 'getUsersByRol']);
    Route::get('/get-user', [ExistingUserController::class, 'getUserLogged']);
    // AYUDA A QUE CADA USUARIO ACTUALICE SU INFORMACIÓN
    Route::post('/update-user', [ExistingUserController::class, 'updateLoggedInUser']);
    Route::post('/update-user/{id_card}', [ExistingUserController::class, 'updateUserById']);
    // deshabilitar y habilitar
    Route::post('/disable-user/{id_card}', [ExistingUserController::class, 'disableUser']);
    Route::post('/enable-user/{id_card}', [ExistingUserController::class, 'enableUser']);
    
    // Route::delete('/delete-user/{id}', [ExistingUserController::class, 'deleteUserById']);
    
    // creación de citas medicas
    Route::post('/createAppointDentist', [MedicalAppointmentController::class, 'createAppointmentDentist']);
    Route::post('/createAppointAdmin', [MedicalAppointmentController::class, 'createAppointmentAdmin']);
    // CONSULTAS DE CITAS MEDICAS
    Route::get('/getAppointmentsUser', [MedicalAppointmentController::class, 'getAppointmentsByUser']);
    Route::get('/getAllAppointments', [MedicalAppointmentController::class, 'getAllAppointments']);
    Route::get('/getAppointment/{id}', [MedicalAppointmentController::class, 'getAppointmentById']);
    Route::get('/getAppointments/{id_status}', [MedicalAppointmentController::class, 'getAppointmentsByStatus']);
    Route::get('/getAppointmentsByDentist/{id_card}', [MedicalAppointmentController::class, 'getAppointmentsByDentist']);
    // ACTUALIZAR CITA M.
    Route::post('/updateAppointment/{id}', [MedicalAppointmentController::class, 'updateMedicalAppointment']);
    // agendar y cancelar cita
    Route::post('/scheduleAppointment/{id}', [MedicalAppointmentController::class, 'scheduleAppointment']);
    Route::post('/cancelAppointment/{id}', [MedicalAppointmentController::class, 'cancelAppointment']);
    // agendar cita para un paciente desde admin / id de la cita 
    Route::post('/scheduleAppointment/patient/{id}', [MedicalAppointmentController::class, 'scheduleAppointmentPatient']);
    
    // crear servicio
    Route::post('/createService', [ServiceController::class, 'createService']);
    // actualizar servicio
    Route::post('/updateService/{id_serv}', [ServiceController::class, 'updateService']);
    // eliminar servicio
    Route::delete('/deleteService/{id_serv}', [ServiceController::class, 'deleteService']);

    // creación historias clínicas / uso exclusivo de admin
    Route::post('/createMedicalRecord', [MedicalRecordController::class, 'createMedicalRecord']);
    // actualizar historia clinica con cedula del paciente
    Route::post('/updateMedicalRecord/{id_card}', [MedicalRecordController::class, 'updateMedicalRecord']);


    // crear detalle de una historia clinica
    Route::post('/createRecordDetail/{id_card}', [MedicalRecordController::class, 'createRecordDetail']);
    // obtener todo el historial clinico de un usuario/ ADMIN / ODONT
    Route::get('/getMedicalRecordUser/{id_card}', [MedicalRecordController::class, 'getAllMedicalRecordByIdCard']);
    // obtener historia clínica usuario logeado
    Route::get('/getOwnMedicalRecord', [MedicalRecordController::class, 'getOwnMedicalRecord']);
});