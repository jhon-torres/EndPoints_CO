<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medical_record;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MedicalRecordController extends Controller
{
    // crea una historia clinica / al registrarse un paciente se le genera su historial clinico
    // uso solamente en casos exclusivos

    public function createMedicalRecord(Request $request)
    {

        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) { //solo admin
            $validator = Validator::make($request->all(), [
                'identity_card_user' => 'required|string|numeric|integer|digits:10',
                'background' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                $user = User::where('identity_card_user', $request->input('identity_card_user'))->first();

                if ($user == null) {
                    return response()->json(['error' => 'Usuario no encontrado'], 404);
                } else {
                    $record = Medical_record::create([
                        'identity_card_user' => $request->input('identity_card_user'),
                        'background' => $request->input('background'),
                    ]);
                    return response()->json(['message' => 'Historia Clínica registrada exitosamente'], 201);
                }
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // se actualiza el campo de los antecedentes de la historia clinica del paciente
    public function updateMedicalRecord(int $id_card, Request $request)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 2) { // solo los doctores pueden actualizar los antecedentes en la historia clínica
            $record = Medical_record::where('identity_card_user', $id_card)->first();

            if ($record == null) {
                return response()->json(['error' => 'Historia Clínica no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'background' => 'required|string|max:255',
                // 'background' => ['required', 'string', 'regex:/^[\pL\s,.]+$/u', 'max:255'],
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $record->background = $request->input('background');
            $record->save();

            return response()->json(['message' => 'Datos actualizados'], 200);
        } else {
                    return response()->json(['error' => 'No eres Odontólogo'], 422);
                }
    }
    
}
