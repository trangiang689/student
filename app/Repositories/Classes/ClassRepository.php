<?php
namespace App\Repositories\Classes;

use App\Repositories\BaseRepository;
use App\Repositories\Classes\ClassRepositoryInterface;

class ClassRepository extends BaseRepository implements ClassRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Classes::class;
    }
    public function getListPaginateClasses($limit = 2){
        return $this->model->paginate($limit);
    }
}
