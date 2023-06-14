<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ExistingUserController extends Controller
{
    //mostrar todos los usuarios
    public function getAllUsers(): JsonResponse
    {

        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {

            $users = User::all();
            return response()->json($users);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // mostrar un usuario especifico por medio del id
    public function getUserById(int $id)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {

            $user = User::find($id);

            if (!$user) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            return response()->json($user);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // mostrar un usuario especifico por medio del id del rol 
    public function getUsersByRol(int $id)
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {
            $users = User::where('rol_id', $id)->get();
            return response()->json($users);
        }else {
                return response()->json(['error' => 'Usuario sin privilegios'], 422);
            }
    }

    // editar datos propios - usuario logeado
    public function updateLoggedInUser(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|numeric|digits:10',
            'address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');

        $user->save();

        return response()->json(['message' => 'Datos actualizados'], 200);
    }

    public function updateUserById(int $id, Request $request)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $user = User::find($id);

            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
                'phone' => 'required|string|numeric|digits:10',
                'address' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');

            $user->save();

            return response()->json(['message' => 'Datos actualizados'], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    public function deleteUserById(int $id)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $user = User::find($id);
            if (isset($user)){
                $user->delete();
                return response()->json(['message' => 'Datos Usuario Eliminado'], 200);
            } else {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }
}
