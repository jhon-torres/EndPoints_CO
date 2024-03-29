<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\Medical_appointment;
use Illuminate\Support\Facades\Http;

class ReminderController extends Controller
{
    public function reminderDevice () {
        $now = Carbon::now()->format('Y-m-d');
            $dateTwoDaysFromNow = Carbon::now()->addDays(2)->format('Y-m-d');

            $appointmentsTwoDaysFromNow = Medical_appointment::where('id_status', 2)->whereBetween('date', [$now, $dateTwoDaysFromNow])->get();
            // echo $dateTwoDaysFromNow. " ". $appointmentsTwoDaysFromNow;

            foreach ($appointmentsTwoDaysFromNow as $appointment) {
                // echo "Dentista: " . $appointment;

                // obtener token del odontologo
                $dentist = User::where('identity_card_user', $appointment->identity_card_user)->first();
                $tokenDevice_dentist = $dentist->remember_token;

                // obtener token del paciente
                $patient = User::where('identity_card_user', $appointment->id_patient)->first();
                $tokenDevice_patient = $patient->remember_token;

                if (isset($tokenDevice_dentist)) {

                    $title = 'Cita Odontológica';
                    $body = 'Usted debe atender la cita con día: ' . $appointment->date . " con hora: " . $appointment->start_time;
                    $deviceToken = $tokenDevice_dentist; // aqui token del dispositivo EXPO
        
                    // Ejemplo de envío de notificación utilizando una solicitud HTTP
                    $response = Http::post('https://exp.host/--/api/v2/push/send', [
                        'title' => $title,
                        'body' => $body,
                        'to' => $deviceToken,
                    ]);
        
                    // Procesar la respuesta de la solicitud y manejar errores si es necesario
                    if ($response->successful()) {
                        // echo 'Notificación enviada a odontólogo correctamente.';
                        return response()->json(['message' => 'Notificación enviada correctamente.']);
                    } else {
                        // echo 'Error al enviar la notificación.';
                        return response()->json(['message' => 'Error al enviar la notificación.'], 500);
                    }
                }

                if (isset($tokenDevice_patient)) {

                    $title = 'Cita Odontológica';
                    $body = 'Usted debe asistir a la cita con día: ' . $appointment->date . " con hora: " . $appointment->start_time;
                    $deviceToken = $tokenDevice_patient; // aqui token del dispositivo EXPO
        
                    // Ejemplo de envío de notificación utilizando una solicitud HTTP
                    $response = Http::post('https://exp.host/--/api/v2/push/send', [
                        'title' => $title,
                        'body' => $body,
                        'to' => $deviceToken,
                    ]);
        
                    // Procesar la respuesta de la solicitud y manejar errores si es necesario
                    if ($response->successful()) {
                        // echo 'Notificación enviada a paciente correctamente.';
                        return response()->json(['message' => 'Notificación enviada correctamente.']);
                    } else {
                        // echo 'Error al enviar la notificación.';
                        return response()->json(['message' => 'Error al enviar la notificación.'], 500);
                    }
                }

            }
    }
}
