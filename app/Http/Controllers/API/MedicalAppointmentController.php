<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medical_appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class MedicalAppointmentController extends Controller
{
    // crear una cita medica desde un Dentista
    public function createAppointmentDentist(Request $request)
    {

        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 2) { // doctor
            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                // 'identity_card_user' => 'exists:users,identity_card_user',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {

                $existingAppointment = Medical_appointment::where('date', $request->input('date'))
                    ->where('start_time', $request->input('start_time'))
                    ->where('identity_card_user', $user->identity_card_user)
                    ->exists();

                if ($existingAppointment) {
                    return response()->json(['error' => 'Ya existe una cita con la misma fecha, hora de inicio y dentista.'], 422);
                }

                $appointment = Medical_appointment::create([
                    'date' => $request->input('date'),
                    'start_time' => $request->input('start_time'),
                    'end_time' => $request->input('end_time'),
                    'id_status' => 1, // estado disponible
                    'identity_card_user' => $user->identity_card_user,
                ]);
                // Retornar una respuesta exitosa
                return response()->json(['message' => 'Cita registrada exitosamente'], 201);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // crear una cita medica desde un Admin
    public function createAppointmentAdmin(Request $request)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) { // admin
            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'identity_card_user' => 'required|exists:users,identity_card_user',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $existingAppointment = Medical_appointment::where('date', $request->input('date'))
                    ->where('start_time', $request->input('start_time'))
                    ->where('identity_card_user', $request->input('identity_card_user'))
                    ->exists();

                if ($existingAppointment) {
                    return response()->json(['error' => 'Ya existe una cita con la misma fecha, hora de inicio y dentista.'], 422);
                }

                $appointment = Medical_appointment::create([
                    'date' => $request->input('date'),
                    'start_time' => $request->input('start_time'),
                    'end_time' => $request->input('end_time'),
                    'id_status' => 1, // estado disponible
                    'identity_card_user' => $request->input('identity_card_user'),
                ]);
                // Retornar una respuesta exitosa
                return response()->json(['message' => 'Cita registrada exitosamente'], 201);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // consulta de todas las citas médica
    public function getAllAppointments (): JsonResponse 
    {
        $medical_appointments = Medical_appointment::all();
        return response()->json([$medical_appointments], 200);
    }

    // consulta de cita médica por id
    public function getAppointmentById (int $id){
        $medical_appointment = Medical_appointment::where('id', $id)->first();

        if ($medical_appointment == null) {
            return response()->json(['error' => 'Cita médica no encontrada'], 404);
        }
    
        return response()->json([$medical_appointment], 200);
    }

    // consulta de citas medicas por status / 1 = disponible / 2 = no disponible
    public function getAppointmentsByStatus (int $id_status) {
        $medical_appointments = Medical_appointment::where('id_status', $id_status)->get();
        if (!empty($medical_appointments[0])){
            return response()->json([$medical_appointments], 200);
        } 
        // else {
        //     return response()->json(['error' => 'Cita médica no encontrada'], 404);
        // }
    }

}
