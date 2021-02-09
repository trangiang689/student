<?php

namespace App\Repositories\Users;

use App\Repositories\BaseRepository;
use App\Repositories\Subjects\SubjectRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\User::class;
    }

    public function create($attributes = [], $roles = [])
    {
        $result = $this->model->create($attributes);
        if ($result) {
            $result->assignRole($roles);
        }

        return $result;
    }
}
