<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Medical_record;
use App\Models\Record_detail;
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

    public function createRecordDetail(int $id_card, Request $request)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 2) { // solo los doctores pueden crear los detalles en una historia clinica
            $record = Medical_record::where('identity_card_user', $id_card)->first();

            if ($record == null) {
                return response()->json(['error' => 'Historia Clínica no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:255',
                'current_illness' => 'nullable|string|max:255',
                // 'odontogram' => 'required|json'
                'odontogram' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $detail = Record_detail::create([
                'id_record' => $record->id,
                'reason' => $request->input('reason'),
                'current_illness' => $request->input('current_illness'),
                'odontogram' => $request->input('odontogram'),
            ]);

            return response()->json(['message' => 'Detalle de historia Clínica registrado exitosamente'], 201);
        } else {
            return response()->json(['error' => 'No eres Odontólogo'], 422);
        }
    }

    public function updateRecordDetail(int $id_detail, Request $request)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 2) { // solo los doctores pueden crear los detalles en una historia clinica
            $record_detail = Record_detail::where('id', $id_detail)->first();

            if ($record_detail == null) {
                return response()->json(['error' => 'Detalle de historia clínica no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:255',
                'current_illness' => 'nullable|string|max:255',
                // 'odontogram' => 'required|json'
                'odontogram' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $record_detail->reason = $request->input('reason');
            $record_detail->current_illness = $request->input('current_illness');
            $record_detail->odontogram = $request->input('odontogram');
            $record_detail->save();

            return response()->json(['message' => 'Detalle de historia Clínica actualizado exitosamente'], 200);
        } else {
            return response()->json(['error' => 'No eres Odontólogo'], 422);
        }
    }

    public function getAllMedicalRecordByIdCard(int $id_card)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1 || $rol_id == 2) {
            $user = User::where('identity_card_user', $id_card)->first();
            if ($user == null) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $record = Medical_record::where('identity_card_user', $id_card)->first();
            if ($record == null) {
                return response()->json(['error' => 'Historia clínica no encontrado'], 404);
            }

            $id_record = $record->id;
            $user_card = $user->identity_card_user;
            $full_name = $user->names . " " . $user->surnames;
            $backgroud = $record->background;

            $details = Record_detail::where('id_record', $id_record)->get();

            $data = [
                'IdMedicalRecord' => $id_record,
                'IdCardUser' => $user_card,
                'FullName' => $full_name,
                'background' => $backgroud,
                'DetailsRecord' => $details
            ];

            return response()->json([$data], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    public function getOwnMedicalRecord()
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado

        if ($user == null) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $record = Medical_record::where('identity_card_user', $user->identity_card_user)->first();
        if ($record == null) {
            return response()->json(['error' => 'Historia clínica no encontrado'], 404);
        }

        $id_record = $record->id;
        $user_card = $user->identity_card_user;
        $full_name = $user->names . " " . $user->surnames;
        $backgroud = $record->background;

        $details = Record_detail::where('id_record', $id_record)->get();

        $data = [
            'IdMedicalRecord' => $id_record,
            'IdCardUser' => $user_card,
            'FullName' => $full_name,
            'background' => $backgroud,
            'DetailsRecord' => $details
        ];

        return response()->json([$data], 200);
    }
}
