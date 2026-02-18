<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use PHPUnit\Framework\Attributes\Test;

class UsuarioTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function un_usuario_puede_ver_el_formulario_de_login()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    #[Test]
    public function un_usuario_autenticado_puede_acceder_al_inicio()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('inicio'));

        $response->assertStatus(200);
        $response->assertSee('Módulo Planificación');
    }

    #[Test]
    public function un_usuario_no_autenticado_es_redireccionado_al_login()
    {
        $response = $this->get('/proyectos');
        $response->assertRedirect('/login');
    }


    #[Test]
    public function un_usuario_comun_no_puede_ver_el_listado_de_usuarios()
    {
        $user = User::factory()->create(); 
        $response = $this->actingAs($user)->get(route('usuarios.index'));

        $response->assertStatus(403); 
    }
}