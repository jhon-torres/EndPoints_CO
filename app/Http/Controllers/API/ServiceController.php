<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class ServiceController extends Controller
{
    // crear servicio
    public function createService(Request $request) 
    {
        $user = Auth::user(); // Obtener la instancia del modelo de usuario actualmente autenticado
        $rol_id = $user->rol_id; // Acceder a la propiedad rol_id del modelo de usuario

        if($rol_id == 1){
            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Crear el nuevo servicio
            $service = Service::create([
                'description' => $request->input('description'),
            ]);

            return response()->json(['message' => 'Servicio registrado exitosamente'], 201);
        } 
        else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // actualizar servicio
    public function updateService (int $id_serv, Request $request) 
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1) {
            $service = Service::where('id', $id_serv)->first();

            if ($service == null) {
                return response()->json(['error' => 'Servicio no encontrado'], 404);
            }

            $validator = Validator::make($request->all(), [
                'description' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $service->description = $request->input('description');
            $service->save();

            return response()->json(['message' => 'Servicio actualizados'], 200);
        } else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }

    // obtener servicios
    public function getServices (): JsonResponse
    {
        $services = Service::all();
        if (count($services) > 0) {
            return response()->json([$services], 200);
        } else {
            return response()->json(['error' => 'Ningún servicio encontrado.'], 404);
        }
    }

    // eliminar servicio
    public function deleteService (int $id_serv)
    {
        $userPrincipal = Auth::user();
        $rol_id = $userPrincipal->rol_id;

        if ($rol_id == 1){
            $service = Service::find($id_serv);
            if (isset($service)) {
                $service->delete();
                return response()->json(['message' => 'Servicio eliminado'], 200);
            }
            else {
                return response()->json(['error' => 'Servicio no encontrado'], 404);
            }
        } 
        else {
            return response()->json(['error' => 'Usuario sin privilegios'], 422);
        }
    }
}
