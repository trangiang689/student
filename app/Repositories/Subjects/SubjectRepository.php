<?php

namespace App\Repositories\Subjects;

use App\Repositories\BaseRepository;
use App\Repositories\Subjects\SubjectRepositoryInterface;

class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Subject::class;
    }
    public function createFacultiesSubjects($id, $faculties = []){
        $this->model->find($id)->faculty()->detach();
        if(!empty($faculties)){
            foreach ($faculties as $faculty){
                $facultySubject = $this->model->find($id)->faculty()->find($faculty);
                if (empty($facultySubject)){
                    $this->model->find($id)->faculty()->attach($faculty);
                }
            }
        }
    }
}
