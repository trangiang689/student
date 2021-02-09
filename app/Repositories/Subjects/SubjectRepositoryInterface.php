<?php
namespace App\Repositories\Subjects;

interface SubjectRepositoryInterface
{
//    public function getListPaginateFaculties($limit);
    public function createFacultiesSubjects($id, $faculties = []);
}
