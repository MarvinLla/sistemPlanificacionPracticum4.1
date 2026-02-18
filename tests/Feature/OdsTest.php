<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\ODS; 
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OdsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_usuario_puede_ver_el_listado_de_ods()
    {
        // 1. Desactivamos el manejo de excepciones de Laravel para ver errores reales si fallara
        $this->withoutExceptionHandling();

        // 2. Creamos un usuario
        $user = User::factory()->create();

     
        \Illuminate\Support\Facades\Gate::before(function () {
            return true;
        });

        ODS::create([
            'nombreObjetivo' => 'ODS Test Informativo',
            'descripcion' => 'Prueba de funcionamiento SIPeIP',
            'metasAsociadas' => "Meta de Prueba A"
        ]);

        $response = $this->actingAs($user)->get(route('ODS.index'));

        $response->assertStatus(200);
        $response->assertSee('ODS Test Informativo');
    }

    /** @test */
    public function el_metodo_color_meta_retorna_verde_al_cien_por_ciento()
    {
       
        $ods = new ODS();
        $color = $ods->getColorMeta(100);
        $this->assertEquals('#10b981', $color);
    }
}