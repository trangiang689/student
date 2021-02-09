<?php

namespace App\Repositories\Faculties;

use App\Repositories\BaseRepository;

class FacultyRepository extends BaseRepository implements FacultyRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Faculty::class;
    }

    public function getProduct()
    {
        return $this->model->select('product_name')->take(5)->get();
    }

    public function getListPaginateFaculties($limit)
    {
        return $this->model->paginate($limit);
    }
}
