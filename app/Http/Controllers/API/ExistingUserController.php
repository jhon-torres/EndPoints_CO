<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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

    // mostrar un usuario especifico por medio de la cedula
    public function getUserById(int $id_card)
    {
        $userPrincipal = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $userPrincipal->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if ($rol_id == 1) {

            $user = User::where('identity_card_user', $id_card)->first();

            if ($user == null) {
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
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // editar datos propios - usuario logeado
    public function updateLoggedInUser(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'names' => 'required|string|max:255',
            'surnames' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|numeric|digits:10',
            'address' => 'required|string|max:255',
            'image' => 'required|image',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // return response()->json($user);
        $url = $user->profile_picture_url;
        $public_id = $user->profile_picture_id;

        if (isset($user->profile_picture_id) && isset($user->profile_picture_url)) {
            Cloudinary::destroy($public_id);
            $file = $request->file('image');
            $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'users']);
            $public_id = $obj->getPublicId();
            $url = $obj->getSecurePath();
        } else {
            $file = $request->file('image');
            $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'users']);
            $public_id = $obj->getPublicId();
            $url = $obj->getSecurePath();
        }

        $user->names = $request->input('names');
        $user->surnames = $request->input('surnames');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->address = $request->input('address');
        $user->profile_picture_id = $public_id;
        $user->profile_picture_url = $url;

        // if ($user->id == 2 ){
        //     $user->profesional_description = $request->input('profesional_description');
        // }

        $user->save();

        return response()->json(['message' => 'Datos actualizados'], 200);
    }

    public function updateUserById(int $id_card, Request $request)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $user = User::where('identity_card_user', $id_card)->first();
            // return response()->json(isset($user));
            // $user = User::find($id_card, 'identity_card_user');

            if ($user == null) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'names' => 'required|string|max:255',
                'surnames' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone' => 'required|string|numeric|digits:10',
                'address' => 'required|string|max:255',
                'image' => 'required|image',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $url = $user->profile_picture_url;
            $public_id = $user->profile_picture_id;

            if (isset($user->profile_picture_id) && isset($user->profile_picture_url)) {
                Cloudinary::destroy($public_id);
                $file = $request->file('image');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'users']);
                $public_id = $obj->getPublicId();
                $url = $obj->getSecurePath();
            } else {
                $file = $request->file('image');
                $obj = Cloudinary::upload($file->getRealPath(), ['folder' => 'users']);
                $public_id = $obj->getPublicId();
                $url = $obj->getSecurePath();
            }

            $user->names = $request->input('names');
            $user->surnames = $request->input('surnames');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->address = $request->input('address');
            $user->profile_picture_id = $public_id;
            $user->profile_picture_url = $url;

            // if ($user->id == 2 ){
            //     $user->profesional_description = $request->input('profesional_description');
            // }

            $user->save();

            return response()->json(['message' => 'Datos actualizados'], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    public function disableUser(int $id_card)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $user = User::where('identity_card_user', $id_card)->first();

            if ($user == null) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user->user_state = 0; // estado deshabilitado
            $user->save();

            return response()->json(['message' => 'Usuario deshabilitado'], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    public function enableUser(int $id_card)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $user = User::where('identity_card_user', $id_card)->first();

            if ($user == null) {
                return response()->json(['error' => 'Usuario no encontrado'], 404);
            }

            $user->user_state = 1; // estado habilitado
            $user->save();

            return response()->json(['message' => 'Usuario habilitado'], 200);
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
            if (isset($user)) {
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
