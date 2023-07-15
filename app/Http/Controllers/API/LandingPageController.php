<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    // retorna información de los odontólogos y servicios del consultorio
    public function infoLanding () {
        $dentists = User::where('rol_id', 2)
                          ->where('user_state', 1)
                          ->get();
        
        $Array_dentists = [];

        foreach ($dentists as $dentist) {
            $Array_dentists[] = [
                'FullName' => $dentist->names." ".$dentist->surnames,
                'UrlPicture' => $dentist->profile_picture_url,
                'ProfesionalDescription' => $dentist->profesional_description,
            ];
        }

        $services = Service::all();
        $Array_services = [];

        foreach ($services as $service) {
            $Array_services[] = [
                'description' => $service->description,
            ];
        }

        $data = [
            'Dentists' => $Array_dentists,
            'Services' => $Array_services,
        ];

        return response()->json($data);
    }
}
