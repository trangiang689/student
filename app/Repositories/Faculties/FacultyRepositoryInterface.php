<?php
namespace App\Repositories\Faculties;

interface FacultyRepositoryInterface
{
    //ví dụ: lấy 5 sản phầm đầu tiên
    public function getProduct();
    public function getListPaginateFaculties($limit);
}
