<?php

namespace Tests\Feature;

use App\Models\Evento;
use App\Models\Salao;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventoTest extends TestCase
{
    use RefreshDatabase;

    // ── Helpers ──────────────────────────────────────────────────────────────

    private function user(bool $admin = false): User
    {
        return User::factory()->create(['is_admin' => $admin]);
    }

    private function salao(array $attrs = []): Salao
    {
        return Salao::create(array_merge([
            'nome'       => 'Salão Teste',
            'cidade'     => 'Maputo',
            'capacidade' => 100,
            'descricao'  => 'Salão para testes.',
            'ativo'      => true,
        ], $attrs));
    }

    private function evento(array $attrs = []): Evento
    {
        $salao = $this->salao();
        $user  = $this->user();

        return Evento::create(array_merge([
            'title'       => 'Evento Teste',
            'description' => 'Descrição do evento de teste.',
            'date'        => now()->addDays(7)->toDateString(),
            'hora_inicio' => '09:00',
            'hora_fim'    => '17:00',
            'hora'        => '09:00',
            'participantes' => 50,
            'email'       => 'teste@exemplo.com',
            'tipo'        => 'Corporativo',
            'private'     => false,
            'items'       => [],
            'status'      => Evento::STATUS_PENDENTE,
            'salao_id'    => $salao->id,
            'user_id'     => $user->id,
            'city'        => $salao->cidade,
            'salon'       => $salao->nome,
        ], $attrs));
    }

    // ── Página de listagem (/eventos) ─────────────────────────────────────────

    public function test_pagina_eventos_carrega(): void
    {
        $this->get('/eventos')->assertStatus(200);
    }

    public function test_user_simples_ve_apenas_eventos_confirmados(): void
    {
        $salao = $this->salao();
        $user  = $this->user();
        $base  = [
            'description'  => 'desc',
            'hora_inicio'  => '09:00',
            'hora_fim'     => '17:00',
            'hora'         => '09:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
            'private'      => false,
            'items'        => [],
            'salao_id'     => $salao->id,
            'user_id'      => $user->id,
            'city'         => $salao->cidade,
            'salon'        => $salao->nome,
            'date'         => now()->addDays(5)->toDateString(),
        ];

        Evento::create(array_merge($base, ['title' => 'Pendente',    'status' => Evento::STATUS_PENDENTE]));
        Evento::create(array_merge($base, ['title' => 'Confirmado',  'status' => Evento::STATUS_CONFIRMADO]));
        Evento::create(array_merge($base, ['title' => 'Cancelado',   'status' => Evento::STATUS_CANCELADO]));

        $response = $this->actingAs($user)->get('/eventos');

        $response->assertStatus(200);
        $response->assertSee('Confirmado');
        $response->assertDontSee('Pendente');
        $response->assertDontSee('Cancelado');
    }

    public function test_admin_ve_todos_os_eventos(): void
    {
        $salao = $this->salao();
        $admin = $this->user(true);
        $base  = [
            'description'  => 'desc',
            'hora_inicio'  => '09:00',
            'hora_fim'     => '17:00',
            'hora'         => '09:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
            'private'      => false,
            'items'        => [],
            'salao_id'     => $salao->id,
            'user_id'      => $admin->id,
            'city'         => $salao->cidade,
            'salon'        => $salao->nome,
            'date'         => now()->addDays(5)->toDateString(),
        ];

        Evento::create(array_merge($base, ['title' => 'Pendente Admin',   'status' => Evento::STATUS_PENDENTE]));
        Evento::create(array_merge($base, ['title' => 'Confirmado Admin', 'status' => Evento::STATUS_CONFIRMADO]));

        $response = $this->actingAs($admin)->get('/eventos');

        $response->assertStatus(200);
        $response->assertSee('Pendente Admin');
        $response->assertSee('Confirmado Admin');
    }

    // ── Formulário de criação (/eventos/create) ───────────────────────────────

    public function test_guest_nao_acede_ao_formulario_de_criacao(): void
    {
        $this->get('/eventos/create')->assertRedirect('/login');
    }

    public function test_user_autenticado_acede_ao_formulario_de_criacao(): void
    {
        $this->actingAs($this->user())
             ->get('/eventos/create')
             ->assertStatus(200);
    }

    // ── Criar evento (POST /eventos) ──────────────────────────────────────────

    public function test_user_pode_criar_evento(): void
    {
        $user  = $this->user();
        $salao = $this->salao();

        $response = $this->actingAs($user)->post('/eventos', [
            'title'        => 'Evento Novo',
            'description'  => 'Descrição completa do evento.',
            'date'         => now()->addDays(10)->toDateString(),
            'hora_inicio'  => '10:00',
            'hora_fim'     => '18:00',
            'participantes'=> 80,
            'email'        => 'responsavel@teste.com',
            'tipo'         => 'Social',
            'salao_id'     => $salao->id,
            'private'      => 0,
            'items'        => [],
        ]);

        $response->assertRedirect('/meus-eventos');
        $this->assertDatabaseHas('eventos', [
            'title'    => 'Evento Novo',
            'user_id'  => $user->id,
            'salao_id' => $salao->id,
            'status'   => Evento::STATUS_PENDENTE,
        ]);
    }

    public function test_criacao_falha_sem_salao_id(): void
    {
        $user = $this->user();

        $this->actingAs($user)->post('/eventos', [
            'title'        => 'Sem Salão',
            'description'  => 'desc',
            'date'         => now()->addDays(5)->toDateString(),
            'hora_inicio'  => '10:00',
            'hora_fim'     => '18:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
        ])->assertSessionHasErrors('salao_id');
    }

    public function test_criacao_falha_com_data_no_passado(): void
    {
        $salao = $this->salao();
        $user  = $this->user();

        $this->actingAs($user)->post('/eventos', [
            'title'        => 'Evento Passado',
            'description'  => 'desc',
            'date'         => now()->subDays(1)->toDateString(),
            'hora_inicio'  => '10:00',
            'hora_fim'     => '18:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Social',
            'salao_id'     => $salao->id,
        ])->assertSessionHasErrors('date');
    }

    public function test_criacao_falha_com_conflito_de_horario(): void
    {
        $user  = $this->user();
        $salao = $this->salao();
        $data  = now()->addDays(5)->toDateString();

        // Evento já existente
        Evento::create([
            'title'        => 'Evento Existente',
            'description'  => 'desc',
            'date'         => $data,
            'hora_inicio'  => '10:00',
            'hora_fim'     => '15:00',
            'hora'         => '10:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
            'private'      => false,
            'items'        => [],
            'status'       => Evento::STATUS_CONFIRMADO,
            'salao_id'     => $salao->id,
            'user_id'      => $user->id,
            'city'         => $salao->cidade,
            'salon'        => $salao->nome,
        ]);

        // Tenta criar evento no mesmo horário
        $this->actingAs($user)->post('/eventos', [
            'title'        => 'Evento Conflito',
            'description'  => 'desc',
            'date'         => $data,
            'hora_inicio'  => '12:00',
            'hora_fim'     => '17:00',
            'participantes'=> 30,
            'email'        => 'b@b.com',
            'tipo'         => 'Social',
            'salao_id'     => $salao->id,
            'private'      => 0,
        ])->assertSessionHas('error');
    }

    // ── Detalhes (/eventos/{id}) ──────────────────────────────────────────────

    public function test_user_simples_nao_acede_a_detalhes_de_evento_alheio(): void
    {
        $evento = $this->evento();
        $outro  = $this->user();

        $this->actingAs($outro)
             ->get("/eventos/{$evento->id}")
             ->assertStatus(403);
    }

    public function test_dono_acede_aos_detalhes_do_seu_evento(): void
    {
        $user  = $this->user();
        $salao = $this->salao();

        $evento = Evento::create([
            'title'        => 'Meu Evento',
            'description'  => 'desc',
            'date'         => now()->addDays(5)->toDateString(),
            'hora_inicio'  => '09:00',
            'hora_fim'     => '17:00',
            'hora'         => '09:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
            'private'      => false,
            'items'        => [],
            'status'       => Evento::STATUS_PENDENTE,
            'salao_id'     => $salao->id,
            'user_id'      => $user->id,
            'city'         => $salao->cidade,
            'salon'        => $salao->nome,
        ]);

        $this->actingAs($user)
             ->get("/eventos/{$evento->id}")
             ->assertStatus(200)
             ->assertSee('Meu Evento');
    }

    public function test_admin_acede_a_detalhes_de_qualquer_evento(): void
    {
        $evento = $this->evento();
        $admin  = $this->user(true);

        $this->actingAs($admin)
             ->get("/eventos/{$evento->id}")
             ->assertStatus(200);
    }

    // ── Páginas estáticas ─────────────────────────────────────────────────────

    public function test_pagina_sobre_nos_carrega(): void
    {
        $this->get('/about')->assertStatus(200);
    }

    public function test_pagina_contacto_carrega(): void
    {
        $this->get('/contact')->assertStatus(200);
    }

    // ── Meus Eventos ─────────────────────────────────────────────────────────

    public function test_guest_nao_acede_a_meus_eventos(): void
    {
        $this->get('/meus-eventos')->assertRedirect('/login');
    }

    public function test_user_ve_apenas_os_seus_proprios_eventos(): void
    {
        $user1  = $this->user();
        $user2  = $this->user();
        $salao  = $this->salao();
        $base   = [
            'description'  => 'desc',
            'hora_inicio'  => '09:00',
            'hora_fim'     => '17:00',
            'hora'         => '09:00',
            'participantes'=> 50,
            'email'        => 'a@b.com',
            'tipo'         => 'Corporativo',
            'private'      => false,
            'items'        => [],
            'status'       => Evento::STATUS_PENDENTE,
            'salao_id'     => $salao->id,
            'city'         => $salao->cidade,
            'salon'        => $salao->nome,
            'date'         => now()->addDays(5)->toDateString(),
        ];

        Evento::create(array_merge($base, ['title' => 'Evento do User 1', 'user_id' => $user1->id]));
        Evento::create(array_merge($base, ['title' => 'Evento do User 2', 'user_id' => $user2->id]));

        $response = $this->actingAs($user1)->get('/meus-eventos');

        $response->assertSee('Evento do User 1');
        $response->assertDontSee('Evento do User 2');
    }
}