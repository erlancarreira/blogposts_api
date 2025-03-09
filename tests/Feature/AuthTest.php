<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        \Artisan::call('passport:install');
        
        $this->withHeaders([
            'Accept' => 'application/json'
        ]);
    }

    public function test_usuario_pode_fazer_login_com_credenciais_validas()
    {
        $user = User::factory()->create();

        $token = $user->createToken('token')->accessToken;

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'senha123'
        ]);        

        $response->assertOk()
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'nome',
                    'email',
                    'telefone',
                    'is_valid'
                ],
                'token'
            ])
            ->assertJson([
                'message' => 'Login realizado com sucesso'
            ]);

        $this->assertNotEmpty($token);
    }


    public function test_usuario_nao_pode_fazer_login_com_senha_invalida()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email'    => $user->email,
            'password' => 'senha_errada'
        ]);

        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Credenciais inválidas. Verifique seu email e senha'
            ]);
    }

    public function test_usuario_pode_se_registrar_com_dados_validos()
    {
        $userData = [
            'nome'     => 'João Silva',
            'email'    => 'joao@email.com',
            'password' => 'senha123',
            'telefone' => '(85) 99999-9999'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertCreated()
            ->assertJsonStructure([
                'message',
                'user' => [
                    'id',
                    'nome',
                    'email',
                    'telefone',
                    'is_valid'
                ],
                'token'
            ])
            ->assertJson([
                'message' => 'Usuário registrado com sucesso.',
                'user' => [
                    'nome'     => $userData['nome'],
                    'email'    => $userData['email'],
                    'telefone' => $userData['telefone'],
                    'is_valid' => true
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email'    => $userData['email'],
            'nome'     => $userData['nome'],
            'telefone' => $userData['telefone']
        ]);
    }


    public function test_usuario_nao_pode_se_registrar_com_email_duplicado()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/register', [
            'nome'     => 'João Silva',
            'email'    => $user->email,
            'password' => 'senha123',
            'telefone' => '(85) 99999-9999'
        ]);

        $response->assertStatus(422);

        $response->assertJson([
            'message' => 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.',
        ]);
    }

    public function test_usuario_pode_fazer_logout()
    {
        $user = User::factory()->create();
        $token = $user->createToken('token')->accessToken;

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson([
                'message' => 'Logout realizado com sucesso.'
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
