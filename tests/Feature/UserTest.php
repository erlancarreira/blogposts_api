<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    private User $usuario;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        \Artisan::call('passport:install');

        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
        
        $this->usuario = User::factory()->create();
        $this->token = $this->usuario->createToken('auth_token')->accessToken;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->usuario->tokens()->delete();
    }

    public function test_listar_usuarios_com_paginacao()
    {
        User::factory(3)->create();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/users');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nome',
                        'email',
                        'telefone',
                        'is_valid'
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);
    }

    public function test_visualizar_usuario_pelo_id()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/users/{$this->usuario->id}");

        $response->assertOk()
            ->assertJson([
                'id'       => $this->usuario->id,
                'nome'     => $this->usuario->nome,
                'email'    => $this->usuario->email,
                'telefone' => $this->usuario->telefone,
                'is_valid' => $this->usuario->is_valid
            ]);
    }

    public function test_nao_pode_visualizar_usuario_inexistente()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/users/99999');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'Usuário não encontrado'
            ]);
    }

    public function test_usuario_pode_atualizar_seus_proprios_dados()
    {
        $novosDados = [
            'nome'     => 'Nome Atualizado',
            'email'    => 'novo@email.com',
            'password' => 'nova_senha',
            'telefone' => '(85) 88888-8888'
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/users/{$this->usuario->id}", $novosDados);

        $response->assertOk()
            ->assertJson([
                'nome'     => $novosDados['nome'],
                'email'    => $novosDados['email'],
                'telefone' => $novosDados['telefone']
            ]);

        $this->assertDatabaseHas('users', [
            'id'       => $this->usuario->id,
            'nome'     => $novosDados['nome'],
            'email'    => $novosDados['email'],
            'telefone' => $novosDados['telefone']
        ]);
    }

    public function test_nao_pode_atualizar_email_para_um_ja_existente()
    {
        $outroUsuario = User::factory()->create();

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/users/{$this->usuario->id}", [
                'email' => $outroUsuario->email
            ]);

        $response->assertStatus(422);
    }

    public function test_usuario_pode_ser_desativado()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/users/{$this->usuario->id}", [
                'is_valid' => false
            ]);

        $response->assertOk()
            ->assertJsonPath('is_valid', false);

        $this->assertDatabaseHas('users', [
            'id' => $this->usuario->id,
            'is_valid' => false
        ]);
    }

    public function test_excluir_usuario()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/users/{$this->usuario->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('users', [
            'id' => $this->usuario->id
        ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_validacao_ao_atualizar_usuario()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/users/{$this->usuario->id}", [
                'email' => 'email_invalido',
                'telefone' => '123'
            ]);

        $response->assertUnprocessable()
            ->assertJson([
                'message' => 'O email deve ser um endereço de email válido'
            ]);
    }

    public function test_atualizacao_parcial_de_usuario()
    {
        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/users/{$this->usuario->id}", [
                'nome' => 'Apenas Nome Atualizado'
            ]);

        $response->assertOk()
            ->assertJson([
                'nome' => 'Apenas Nome Atualizado',
                'email' => $this->usuario->email,
                'telefone' => $this->usuario->telefone
            ]);
    }
}
