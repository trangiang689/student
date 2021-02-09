<?php
namespace App\Repositories\Students;

interface StudentRepositoryInterface
{
//    public function getListPaginateFaculties($limit);
    public function hasScoreOfSubject($student_id, $subject_id);

    public function updateScore($student_id, $subject_id, $score);

    public function updateScores($student_id, $sync = []);

    public function addScore($student_id, $subject_id, $score);

    public function getPaginateStudent($request = []);

    public function getStudentsByScore($minScore = 0, $max = 10);
}
