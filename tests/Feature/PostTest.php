<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
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
        $this->token = $this->usuario->createToken('token')->accessToken;
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->usuario->tokens()->delete();
    }

    public function test_listar_posts_com_paginacao()
    {
        Post::factory(3)->create([
            'author' => $this->usuario->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/posts');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'content',
                        'tags',
                        'author' => [
                            'id',
                            'nome',
                            'email'
                        ]
                    ]
                ],
                'current_page',
                'last_page',
                'per_page',
                'total'
            ]);
    }

    public function test_filtrar_posts_por_tag()
    {
        Post::factory()->create([
            'author' => $this->usuario->id,
            'tags' => ['php', 'laravel']
        ]);

        Post::factory()->create([
            'author' => $this->usuario->id,
            'tags' => ['javascript']
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/posts?tag=php');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.tags', ['php', 'laravel']);
    }

    public function test_buscar_posts_por_titulo_ou_conteudo()
    {
        Post::factory()->create([
            'author' => $this->usuario->id,
            'title' => 'Tutorial de Laravel',
            'content' => 'Conteúdo sobre Laravel'
        ]);

        Post::factory()->create([
            'author' => $this->usuario->id,
            'title' => 'Dicas de JavaScript',
            'content' => 'Conteúdo sobre JS'
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson('/api/posts?query=laravel');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.title', 'Tutorial de Laravel');
    }

    public function test_criar_novo_post()
    {
        $postData = [
            'title' => 'Meu Novo Post',
            'content' => 'Conteúdo do post teste',
            'tags' => ['teste', 'laravel'],
            'author' => $this->usuario->id
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->postJson('/api/posts', $postData);

        $response->assertCreated()
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'tags',
                'author' => [
                    'id',
                    'nome',
                    'email',
                    'telefone',
                    'is_valid'
                ]
            ])
            ->assertJson([
                'title' => $postData['title'],
                'content' => $postData['content'],
                'tags' => $postData['tags']
            ]);

        $this->assertDatabaseHas('posts', [
            'title' => $postData['title'],
            'content' => $postData['content'],
            'author' => $this->usuario->id
        ]);
    }

    public function test_visualizar_post_existente()
    {
        $post = Post::factory()->create([
            'author' => $this->usuario->id,
            'title' => 'Post de Teste',
            'content' => 'Conteúdo do post'
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->getJson("/api/posts/{$post->id}");

        $response->assertOk()
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'tags',
                'author' => [
                    'id',
                    'nome',
                    'email',
                    'telefone',
                    'is_valid'
                ]
            ])
            ->assertJson([
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content
            ]);
    }

    public function test_atualizar_post_existente()
    {
        $post = Post::factory()->create([
            'author' => $this->usuario->id,
            'title' => 'Post Original',
            'content' => 'Conteúdo original',
            'tags' => ['original']
        ]);

        $novosDados = [
            'title' => 'Título Atualizado',
            'content' => 'Conteúdo atualizado',
            'tags' => ['atualizado', 'teste']
        ];

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/posts/{$post->id}", $novosDados);

        $response->assertOk()
            ->assertJson([
                'title' => $novosDados['title'],
                'content' => $novosDados['content'],
                'tags' => $novosDados['tags']
            ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => $novosDados['title'],
            'content' => $novosDados['content']
        ]);
    }

    public function test_nao_pode_atualizar_post_de_outro_usuario()
    {
        $outroUsuario = User::factory()->create();
        $post = Post::factory()->create([
            'author' => $outroUsuario->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->putJson("/api/posts/{$post->id}", [
                'title' => 'Tentativa de atualização'
            ]);

        $response->assertForbidden();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
            'title' => 'Tentativa de atualização'
        ]);
    }

    public function test_excluir_post()
    {
        $post = Post::factory()->create([
            'author' => $this->usuario->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/posts/{$post->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id
        ]);
    }

    public function test_nao_pode_excluir_post_de_outro_usuario()
    {
        $outroUsuario = User::factory()->create();
        $post = Post::factory()->create([
            'author' => $outroUsuario->id
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$this->token}")
            ->deleteJson("/api/posts/{$post->id}");

        $response->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id
        ]);
    }
}
