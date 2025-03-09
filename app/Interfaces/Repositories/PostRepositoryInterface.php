<?php

namespace App\Interfaces\Repositories;

interface PostRepositoryInterface
{
    public function get(): array;
    public function getById(int $id): mixed;
    public function getFiltered(?string $tag = null, ?string $query = null): array;
    public function create(array $data): mixed;
    public function update(int $id, array $data): mixed;
    public function delete(int $id): bool;
    
}
