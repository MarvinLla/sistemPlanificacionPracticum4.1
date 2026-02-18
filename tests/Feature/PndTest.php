<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PndObjetivo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class PndTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usuario_autenticado_puede_ver_el_listado_pnd()
    {
        // 1. Saltamos restricciones de seguridad para el test
        $user = User::factory()->create();
        \Illuminate\Support\Facades\Gate::before(fn () => true);

        // 2. Creamos un objetivo PND real
        PndObjetivo::create([
            'eje' => 'Eje Social',
            'nombre_objetivo' => 'Reducir la pobreza extrema',
            'descripcion' => 'Objetivo nacional de prueba'
        ]);

        // 3. Petición a la ruta
        $response = $this->actingAs($user)->get(route('pnd.index'));

        // 4. Verificaciones
        $response->assertStatus(200);
        $response->assertSee('Eje Social');
        $response->assertSee('Reducir la pobreza extrema');
    }

    #[Test]
    public function el_estado_atributo_cambia_segun_el_cumplimiento()
    {
      
        $pnd = new PndObjetivo();
        
        // Simulamos que el cálculo de cumplimiento da 100
        // Como calcularCumplimiento depende de relaciones, 
        // aquí validamos la lógica de getEstadoAtributo()
        
        // Si el porcentaje es 0 (por defecto al no tener proyectos)
        $this->assertEquals('Pendiente', $pnd->getEstadoAtributo());
    }
}