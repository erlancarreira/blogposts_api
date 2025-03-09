<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar timezone para garantir consistência nos testes
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * Retorna um token de autenticação Bearer para os testes
     */
    protected function getAuthToken(array $userData = []): string
    {
        $user = \App\Models\User::factory()->create($userData);
        return $user->createToken('auth_token')->accessToken;
    }

    /**
     * Define o token de autenticação Bearer para a requisição
     */
    protected function withAuthToken(string $token): self
    {
        return $this->withHeader('Authorization', "Bearer {$token}");
    }
}
