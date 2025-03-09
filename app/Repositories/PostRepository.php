<?php

namespace App\Repositories;

use App\Models\Post;
use App\Interfaces\Repositories\PostRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Auth\Access\AuthorizationException;

class PostRepository implements PostRepositoryInterface
{
    use ApiResponser;
    
    protected $model;

    public function __construct(Post $model)
    {
        $this->model = $model;
    }

    public function get(): array
    {
        $posts = $this->model->with('author')->get();
        return $this->paginate(collect($posts));
    }

    public function getById(int $id): mixed
    {
        return $this->model->with('author')->find($id);
    }

    public function getFiltered(?string $tag = null, ?string $query = null): array
    {
        $posts = $this->model->with('author');

        if ($tag) {
            $posts->whereJsonContains('tags', $tag);
        }

        if ($query) {
            $posts->where(function($q) use ($query) {
                $q->where('title', 'like', "%$query%")
                  ->orWhere('content', 'like', "%$query%");
            });
        }

        $results = $posts->get();
        return $this->paginate(collect($results));
    }

    public function create(array $data): mixed
    {
        $post = $this->model->create($data);
        return $this->getById($post->id);
    }

    public function update(int $id, array $data): mixed
    {
        $post = $this->model->find($id);

        if (!$post) {    
            return null;
        }

        if ($post->author !== auth()->id()) {
            throw new AuthorizationException('Você não tem permissão para editar este post.');
        }

        $post->update($data);

        return $this->getById($post->id);
    }

    public function delete(int $id): bool
    {
        $post = $this->model->find($id);

        if (!$post) {
            return false;
        }

        if ($post->author !== auth()->id()) {
            throw new AuthorizationException('Você não tem permissão para excluir este post.');
        }

        return $post->delete();
    }

    
}
