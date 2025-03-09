<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Interfaces\Repositories\PostRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\PostIndexRequest;

class PostController extends Controller
{
    use ApiResponser;

    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index(PostIndexRequest $request): JsonResponse
    {
        $posts = $this->postRepository->getFiltered(
            $request->query('tag'),
            $request->query('query')
        );

        return $this->successResponse(
            $posts
        );
    }

    public function store(PostStoreRequest $request): JsonResponse
    {
        $post = $this->postRepository->create($request->validated());

        return $this->successResponse(
            $post,
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $post = $this->postRepository->getById($id);

        if (!$post) {
            return $this->errorResponse('Post não encontrado', 404);
        }

        return $this->successResponse($post);
    }

    public function update(PostUpdateRequest $request, int $id): JsonResponse
    {
        $post = $this->postRepository->update($id, $request->validated());

        if (!$post) {
            return $this->errorResponse('Post não encontrado', 404);
        }

        return $this->successResponse($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->postRepository->delete($id);

        if (!$deleted) {
            return $this->errorResponse('Post não encontrado', 404);
        }

        return $this->successResponse(null, 204);
    }
}
