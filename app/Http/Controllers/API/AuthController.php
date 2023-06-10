<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Reset_code_password;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendCodeResetPassword;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Completa los campos correctamente'], 422);
        } else {
            $user = User::where('email', $request->input("email"))->first();

            if ($user) {
                if (Hash::check($request['password'], $user->password)) {
                    $token = $user->createToken('auth_token')->plainTextToken;
                    return response()->json(['token' => $token], 200);
                } else {
                    return response()->json(['error' => 'Credenciales incorrectas'], 401);
                }
            } else {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return [
            'message' => 'Has cerrado sesión exitosamente'
        ];
    }

    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->get();
        if (count($user) > 0) {
            $data = $request->validate([
                'email' => 'required|email',
            ]);

            // Borra los viejos registros que pueda tener el usuario.
            Reset_code_password::where('email', $request->email)->delete();

            // Genera codigo
            $data['code'] = mt_rand(100000, 999999);

            // Crea nuevo codigo junto al email
            $codeData = Reset_code_password::create($data);

            // Envia un correo al usuario con el código
            Mail::to($request->email)->send(new SendCodeResetPassword($codeData->code));

            return response(['message' => 'Revisa la bandeja de entrada de tu correo'], 200);
        } else {
            return response()->json(['error' => 'Usuario no encontrado con dicho email'], 404);
        }
    }

    public function checkCode(Request $request)
    {
        $passwordReset = Reset_code_password::firstWhere('code', $request->code);

        if (isset($passwordReset)) {
            if ($passwordReset->isExpire()) {
                return response()->json(['error' => 'Código expirado'], 422);
            }
            return response()->json(['message' => 'Código válido'], 200);
        } else {
            return response()->json(['message' => 'Código no válido'], 404);
        }

    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|numeric|integer|digits:6',
            'password' => 'required|string|min:8',
        ]);

        // encuentra el registro del código en la BDD
        $reg_code = Reset_code_password::firstWhere('code', $request->code);

        if (isset($reg_code)) {
            //recupera el usuario mediante el email
            $user = User::firstWhere('email', $reg_code->email);

            // variable boolean para verificar que la contraseña coincide con la confirmacion
            $password_confirm = $request->input('password') == $request->input('password_confirm');

            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                if (!$password_confirm) {
                    return response()->json(['error' => 'Contraseña no coincide con la confirmación'], 422);
                }

                // Actualizar el campo password
                $user->password = Hash::make($request->input('password'));

                $user->save();

                // Borra el registro del codigo usado.
                Reset_code_password::where('email', $reg_code->email)->delete();

                return response()->json(['message' => 'Contraseña actualizada'], 200);
            }
        } else {
            return response()->json(['message' => 'Código no válido'], 404);
        }
    }
}
