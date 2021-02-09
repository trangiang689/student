<?php
namespace App\Repositories\Users;

interface UserRepositoryInterface
{
    public function create($attributes = [], $roles =[]);
}
