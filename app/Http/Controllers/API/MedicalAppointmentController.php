<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medical_appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

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
}
