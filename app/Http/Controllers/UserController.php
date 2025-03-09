<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponser;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(): JsonResponse
    {
        $users = $this->userRepository->get();

        return $this->successResponse($users);
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        $user = $this->userRepository->create($request->validated());

        return $this->successResponse(
            $user,
            201
        );
    }

    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->getById($id);

        if (!$user) {
            return $this->errorResponse('Usuário não encontrado', 404);
        }

        return $this->successResponse($user);
    }

    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $user = $this->userRepository->update($id, $request->validated());

        if (!$user) {
            return $this->errorResponse('Usuário não encontrado', 404);
        }

        return $this->successResponse($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $isDeleted = $this->userRepository->delete($id);

        if (!$isDeleted) {
            return $this->errorResponse('Usuário não encontrado', 404);
        }

        return $this->successResponse(
            null,
            204
        );
    }
}
