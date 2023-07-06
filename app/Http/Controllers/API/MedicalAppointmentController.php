<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medical_appointment;
use DragonCode\Contracts\Cashier\Http\Response;
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

    // consulta de citas médicas según usuario
    public function getAppointmentsByUser(): JsonResponse
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {
            $medical_appointments = Medical_appointment::all();
        } elseif ($rol_id == 2) {
            $medical_appointments = Medical_appointment::where('identity_card_user', $user->identity_card_user)->get();
        } elseif ($rol_id == 3) {
            $medical_appointments = Medical_appointment::where('id_patient', $user->identity_card_user)
                ->orWhere('id_status', 1)
                ->get();
        } else {
            $medical_appointments = null;
        }
        if (!empty($medical_appointments[0])) {
            return response()->json([$medical_appointments], 200);
        }
        // return response()->json(['message' => 'No se encontraron citas médicas'], 404);
        return response()->json(['message' => 'No se encontraron citas médicas']);
    }

    // consulta de todas las citas médica
    public function getAllAppointments(): JsonResponse
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {
            $medical_appointments = Medical_appointment::all();
            return response()->json([$medical_appointments], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // consulta de cita médica por id
    public function getAppointmentById(int $id)
    {
        $medical_appointment = Medical_appointment::where('id', $id)->first();

        if ($medical_appointment == null) {
            return response()->json(['error' => 'Cita médica no encontrada'], 404);
        }

        return response()->json([$medical_appointment], 200);
    }

    // consulta de citas medicas por status / 1 = disponible / 2 = no disponible
    public function getAppointmentsByStatus(int $id_status)
    {
        $medical_appointments = Medical_appointment::where('id_status', $id_status)->get();
        if (!empty($medical_appointments[0])) {
            return response()->json([$medical_appointments], 200);
        }
        // else {
        //     return response()->json(['error' => 'Cita médica no encontrada'], 404);
        // }
    }

    // consulta de citas medicas por odontologo
    public function getAppointmentsByDentist(int $id_card)
    {
        $medical_appointments = Medical_appointment::where('identity_card_user', $id_card)->get();
        if (!empty($medical_appointments[0])) {
            return response()->json([$medical_appointments], 200);
        }
    }

    // Actualizar datos de la cita medica
    public function updateMedicalAppointment(int $id, Request $request)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 2 || $rol_id == 1) { // doctor y admin
            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i',
                'identity_card_user' => 'exists:users,identity_card_user',
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

                $appointment = Medical_appointment::where('id', $id)->first();
                if ($appointment == null) {
                    return response()->json(['error' => 'Cita médica no encontrada'], 404);
                }

                if ($rol_id == 1) { // solo el admin puede cambiar de odontologo en una cita
                    $appointment->identity_card_user = $request->input('identity_card_user');
                }
                $appointment->date = $request->input('date');
                $appointment->start_time = $request->input('start_time');
                $appointment->end_time = $request->input('end_time');
                $appointment->save();

                return response()->json(['message' => 'Datos actualizados'], 200);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    //agendar cita
    public function scheduleAppointment(int $id)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 3) {
            $appointment = Medical_appointment::where('id', $id)->first();

            if ($appointment == null) {
                return response()->json(['error' => 'Cita médica no encontrada'], 404);
            }

            $appointment->id_status = 2; // no disponible
            $appointment->id_patient = $userPrincipal->identity_card_user;
            $appointment->save();

            return response()->json(['message' => 'Cita médica agendada'], 200);
        } else {
            return response()->json(['error' => 'El usuario no es Paciente'], 422);
        }
    }

    // agendar cita desde admin para un paciente especifico
    public function scheduleAppointmentPatient(int $id, Request $request)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario
        
        if ($rol_id == 1) { // admin
            $validator = Validator::make($request->all(), [
                'identity_card_user' => 'required|exists:users,identity_card_user',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $appointment = Medical_appointment::where('id', $id)->first();

                if ($appointment == null) {
                    return response()->json(['error' => 'Cita médica no encontrada'], 404);
                }

                $appointment->id_status = 2; // no disponible
                $appointment->id_patient = $request->input('identity_card_user');
                $appointment->save();

                return response()->json(['message' => 'Cita médica agendada'], 200);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    //cancelar cita
    public function cancelAppointment(int $id)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 3) {
            $appointment = Medical_appointment::where('id', $id)->first();

            if ($appointment == null) {
                return response()->json(['error' => 'Cita médica no encontrada'], 404);
            }

            $appointment->id_status = 1; // disponible
            $appointment->id_patient = null;
            $appointment->save();

            return response()->json(['message' => 'Cita médica cancelada'], 200);
        } else {
            return response()->json(['error' => 'Un paciente puede cancelar su cita'], 422);
        }
    }
}
