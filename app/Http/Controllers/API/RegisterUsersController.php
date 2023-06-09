<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class RegisterUsersController extends Controller
{
    public function registerPatient(Request $request)
    {
        $rol = 3;
        $validator = Validator::make($request->all(), [
            'identity_card_user' => 'required|string|numeric|integer|digits:10',
            'names' => 'required|string|max:255',
            'surnames' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|numeric|digits:10',
            'address' => 'required|string|max:255',
        ]);

        // variable boolean para verificar que la contraseña coincide con la confirmacion
        $password_confirm = $request->input('password') == $request->input('password_confirm');

        // Si la validación falla, devuelve un mensaje de error
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            if (!$password_confirm) {
                return response()->json(['error' => 'Contraseña no coincide con la confirmación'], 422);
            }
            // Crear el nuevo usuario
            $user = User::create([
                'identity_card_user' => $request->input('identity_card_user'),
                'rol_id' => $rol,
                'names' => $request->input('names'),
                'surnames' => $request->input('surnames'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
                'phone' => $request->input('phone'),
                'address' => $request->input('address'),
            ]);
            // Retornar una respuesta exitosa
            return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
        }
    }

    public function registerDentist(Request $request)
    {   
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1){ //solo admin
            $rol = 2;
            $validator = Validator::make($request->all(), [
                'identity_card_user' => 'required|string|numeric|integer|digits:10',
                'names' => 'required|string|max:255',
                'surnames' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|numeric|digits:10',
                'address' => 'required|string|max:255',
            ]);
    
            // variable boolean para verificar que la contraseña coincide con la confirmacion
            $password_confirm = $request->input('password') == $request->input('password_confirm');
    
            // Si la validación falla, devuelve un mensaje de error
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                if (!$password_confirm) {
                    return response()->json(['error' => 'Contraseña no coincide con la confirmación'], 422);
                }
                // Crear el nuevo usuario
                $user = User::create([
                    'identity_card_user' => $request->input('identity_card_user'),
                    'rol_id' => $rol,
                    'names' => $request->input('names'),
                    'surnames' => $request->input('surnames'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
                // Retornar una respuesta exitosa
                return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    public function registerAdmin(Request $request)
    {   
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1){ //solo admin
            $rol = 1;
            $validator = Validator::make($request->all(), [
                'identity_card_user' => 'required|string|numeric|integer|digits:10',
                'names' => 'required|string|max:255',
                'surnames' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|numeric|digits:10',
                'address' => 'required|string|max:255',
            ]);
    
            // variable boolean para verificar que la contraseña coincide con la confirmacion
            $password_confirm = $request->input('password') == $request->input('password_confirm');
    
            // Si la validación falla, devuelve un mensaje de error
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            } else {
                if (!$password_confirm) {
                    return response()->json(['error' => 'Contraseña no coincide con la confirmación'], 422);
                }
                // Crear el nuevo usuario
                $user = User::create([
                    'identity_card_user' => $request->input('identity_card_user'),
                    'rol_id' => $rol,
                    'names' => $request->input('names'),
                    'surnames' => $request->input('surnames'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                ]);
                // Retornar una respuesta exitosa
                return response()->json(['message' => 'Usuario registrado exitosamente'], 201);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }
}
