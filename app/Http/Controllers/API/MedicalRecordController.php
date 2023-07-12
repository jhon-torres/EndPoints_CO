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

    
}
