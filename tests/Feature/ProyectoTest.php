<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Proyecto;
use App\Models\User;
use App\Models\PndObjetivo;
use App\Models\Entidad;
use App\Models\Programa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Illuminate\Support\Facades\Gate;

class ProyectoTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usuario_puede_ver_la_lista_de_proyectos_con_presupuesto()
    {
        // 1. Autenticación y bypass de seguridad para el test
        $user = User::factory()->create();
        Gate::before(fn () => true);

        // 2. Crear Entidad con campos obligatorios
        $entidad = Entidad::create([
            'nombre'      => 'Ministerio de Salud',
            'tipo'        => 'Pública',
            'responsable' => 'Juan Pérez',
            'siglas'      => 'MSP'
        ]);
        
        // 3. Crear Objetivo PND
        $pnd = PndObjetivo::create([
            'eje'             => 'Eje Económico',
            'nombre_objetivo' => 'Crecimiento Sostenible',
            'descripcion'     => 'Descripción de prueba PND'
        ]);

        // 4. Crear Programa
        $programa = Programa::create([
            'nombre'          => 'Programa de Infraestructura',
            'pnd_objetivo_id' => $pnd->id,
            'codigo'          => 'PROG-001'
        ]);

        // 5. Crear Proyecto con la integridad requerida por la BD
        Proyecto::create([
            'nombre'                 => 'Construcción Hospital Norte',
            'entidad_id'             => $entidad->id,
            'programa_id'            => $programa->id,
            'pnd_objetivo_id'        => $pnd->id,
            'presupuesto_estimado'   => 500000.00,
            'estado'                 => 'Aprobado',
            'ubicacion_provincia'    => 'Pichincha',
            'beneficiarios_directos' => 1500,
            'responsable'            => 'Admin Test',
            'objetivos'              => 'Construir un hospital de segundo nivel.',
            'descripcion'            => 'Proyecto de salud para la zona norte.',
            'beneficio'              => 'Acceso a salud para la comunidad.',
            'fecha_inicio'           => '2026-01-01',
            'fecha_final'            => '2026-12-31',
            'correo_contacto'        => 'contacto@sistema.gob.ec',
            'telefono_contacto'      => '022345678'
        ]);

        // 6. Ejecución: Simular visita a la ruta index
        $response = $this->actingAs($user)->get(route('proyectos.index'));

        // 7. Verificaciones
        $response->assertStatus(200);
        $response->assertSee('Construcción Hospital Norte');
        // Verificamos que el presupuesto aparezca
        $response->assertSee('500'); 
    }

    #[Test]
    public function el_presupuesto_restante_se_calcula_correctamente()
    {
        $proyecto = new Proyecto(['presupuesto_estimado' => 1000]);
        
        
        // Si no hay gastos, el restante debería ser igual al estimado
        $this->assertEquals(1000, $proyecto->presupuestoRestante());
    }
}