<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Marca;
use Tests\TestCase;

class MarcaTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test para crear una nueva Marca
     * @return void
     */
    public function testCrearMarca()
    {
        $response = $this->json('POST', 'api/crear_marca', [
            'nombre' => 'testing3',
        ]);
        $response->assertStatus(200);
    }

    /**
     * Test para editar una marca con un nombre que ya existe.
     * @return void
     */
    public function testMarcaRepetida()
    {
        $marca1 = factory(Marca::class)->create();
        $marca2 = factory(Marca::class)->create();

        $response = $this->json('PUT', 'api/editar_marca', [
            'nombre' => $marca1->nombre,
            'key' => $marca2->id_marca
        ]);
        $response->assertStatus(500)
            ->assertExactJson([
                'log' => 'La marca que ha ingresado ya existe',
            ]);
    }

    /**
     * Test para eliminar una marca.
     * @return void
     */
    public function testEliminarMarca()
    {
        $marca = factory(Marca::class)->create();
        $response = $this->json('DELETE', "api/eliminar_marca/{$marca->id_marca}");
        $response->assertStatus(200);
    }
}
