<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class MedicalRecordTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_crear_historia_clinica_adm(): void
    {
        $user = User::factory()->make([
            'rol_id' => 1
        ]);
        $Cedula_id = [
            "identity_card_user" => "1314253678",
        ];
        $request = $this->actingAs($user)->post(sprintf('/api/createMedicalRecord'), $Cedula_id);
        $request->assertStatus(201);
    }

    public function test_actualizar_historia_clinica(): void
    {
        $user = User::where('identity_card_user', 1314253678)->first();
        $background = [
            "background" => "Hemofilia, diabetes, cáncer, alergias."
        ];
        $request = $this->actingAs($user)->post(sprintf('/api/updateMedicalRecord/1314253678'), $background);
        $request->assertStatus(200);
    }

    public function test_crear_detalle_historia_clinica(): void
    {
        $user = User::where('identity_card_user', 1314253678)->first();
        $odontograma = [
            46 =>[
                "part_up" => "diagnostico"
            ]
        ];
        $details = [
            "reason" => "Control 1",
            "current_illness" => "Caries",
            "odontogram" => json_encode($odontograma)
        ];
        $request = $this->actingAs($user)->post(sprintf('/api/createRecordDetail/1314253678'), $details);
        $request->assertStatus(201);
    }

    public function test_obtener_historia_clinica() : void 
    {
        $user = User::where('identity_card_user', 1314253678)->first();
        $request = $this->actingAs($user)->get('/api/getMedicalRecordUser/1314253678');
        $request->assertStatus(200);
    }
}
