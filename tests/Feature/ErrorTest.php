<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ErrorTest extends TestCase
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

    public function test_rota_nao_encontrada()
    {
        $response = $this->getJson('/api/rota-inexistente');

        $response->assertNotFound()
            ->assertJson([
                'message' => 'URL não encontrada.'
            ]);
    }

    public function test_acesso_nao_autorizado()
    {
        $response = $this->getJson('/api/posts');

        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Não autenticado.'
            ]);
    }

    public function test_token_invalido()
    {
        $response = $this->withHeader('Authorization', 'Bearer token_invalido')
            ->getJson('/api/posts');

        $response->assertUnauthorized()
            ->assertJson([
                'message' => 'Não autenticado.'
            ]);
    }

    public function test_validacao_de_campos_obrigatorios()
    {
        $response = $this->postJson('/api/auth/register', []);

        $response->assertUnprocessable()
            ->assertJsonFragment([
                'message' => 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.'
            ]);
    }

    public function test_metodo_http_nao_permitido()
    {
        $response = $this->putJson('/api/auth/login', [
            'email' => 'teste@email.com',
            'password' => '123456'
        ]);

        $response->assertMethodNotAllowed()
            ->assertJson([
                'message' => 'O método HTTP não é permitido para esta rota.'
            ]);
    }

    public function test_erro_de_validacao_formato_invalido()
    {
        $response = $this->postJson('/api/auth/register', [
            'nome' => 'João Silva',
            'email' => 'email_invalido',
            'password' => '123',
            'telefone' => '123'
        ]);

        $response->assertUnprocessable()
            ->assertJson([
                'message' => 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.'
            ]);
            //->assertJsonValidationErrors(['email', 'password', 'telefone']);
    }

    protected function tearDown(): void
    {
        User::query()->forceDelete();
        parent::tearDown();
    }
}
