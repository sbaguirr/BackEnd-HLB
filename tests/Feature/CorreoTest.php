<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Correo;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\Organizacion;

class CorreoTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCrearCorreo()
    {
        $orga=factory(Organizacion::class)->create();
        $depa= factory(Departamento::class)->create(['id_organizacion' => $orga->id_organizacion]);
        $emple = factory(Empleado::class)->create(['id_departamento' => $depa->id_departamento]);
        $mail = factory(Correo::class)->make(['cedula' => $emple->cedula]);
        $response = $this->json('POST', 'api/correos', [
            'correo' =>  $mail ->correo,
            'contrasena' => $mail->contrasena,
            'cedula'=> $mail->cedula
            ]);
        $response->assertStatus(200);
    }
}
