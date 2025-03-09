<?php   

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\Repositories\UserRepositoryInterface;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface 
{
    use ApiResponser;
    
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function get(): array
    {
        $users = $this->model->get();
        return $this->paginate(collect($users));
    }

    public function getById(int $id): mixed
    {
        return $this->model->find($id);
    }

    public function create(array $data): mixed
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->model->create($data);
        
    }

    public function update(int $id, array $data): mixed
    {
        $user = $this->model->find($id);

        if (!$user) {
            return null;
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);
        
        return $user;
    }

    public function delete(int $id): bool 
    {
        $user = $this->model->find($id);

        if (!$user) {
            return false;
        }

        return $user->delete();
    }
}
