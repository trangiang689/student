<?php

namespace App\Repositories\Students;

use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
    public function getModel()
    {
        return \App\Models\Students::class;
    }

    public function hasScoreOfSubject($student_id, $subject_id)
    {
        $result = $this->model->find($student_id)->subjects->find($subject_id);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    public function updateScore($student_id, $subject_id, $score)
    {
        $student = $this->model->find($student_id);
        if ($student) {
            $student->subjects()->updateExistingPivot($subject_id, ['score' => $score]);
            return true;
        } else {
            return false;
        }
    }

    public function updateScores($student_id, $sync = [])
    {
        $student = $this->model->find($student_id);
        if ($student) {
            $student->subjects()->sync($sync);
            return true;
        } else {
            return false;
        }
    }

    public function addScore($student_id, $subject_id, $score)
    {
        $student = $this->model->find($student_id);
        if ($student) {
            $student->subjects()->attach($subject_id, ['score' => $score]);
            return true;
        } else {
            return false;
        }
    }

    public function getPaginateStudent($request = [])
    {
        $rs = $this->model;

        $limit = $request->get('limit');

        if (!empty($request->get('yearoldstart'))) {
            $today = Carbon::now('Asia/Ho_Chi_Minh');
            $yearOldStart = $today->subYear((int)$request->get('yearoldstart'))->year;
            $rs = $rs->whereYear('birth_date', '<=', $yearOldStart);
        }

        if (!empty($request->get('yearoldend'))) {
            $today = Carbon::now('Asia/Ho_Chi_Minh');
            $yearOldEnd = $today->subYear((int)$request->get('yearoldend'))->year;
            $rs = $rs->whereYear('birth_date', '>=', $yearOldEnd);
        }

        if (!empty($request->get('scorestart'))) {
            $GLOBALS['scoreStart'] = 'AVG(scores.score) >= ' . (float)$request->get('scorestart');
            $rs = $rs->whereExists(function ($query) {
                $query->select(DB::raw('scores.student_id'))
                    ->from('scores')->groupBy('scores.student_id')
                    ->havingRaw($GLOBALS['scoreStart'])
                    ->whereRaw('scores.student_id = students.id');
            });
        }

        if (!empty($request->get('scoreend'))) {
            $GLOBALS['scoreEnd'] = 'AVG(scores.score) <= ' . (float)$request->get('scoreend');
            $rs = $rs->whereExists(function ($query) {
                $query->select(DB::raw('scores.student_id'))
                    ->from('scores')->groupBy('scores.student_id')
                    ->havingRaw($GLOBALS['scoreEnd'])
                    ->whereRaw('scores.student_id = students.id');
            });
        }

        if (!empty($request->get('phonenumbertype'))) {
            $rs = $rs->where(function ($query) use ($request) {
                foreach (\Config::get('constants.PHONE_NUMBERS')[$request->get('phonenumbertype')] as $phoneNumberStar) {
                    $query->orWhere('phone_number', 'like', $phoneNumberStar . '%');
                }
            });
        }

        if (!empty($request->get('completedcourse')) && in_array($request->get('completedcourse'), ['yes', 'no'])) {
            if ($request->get('completedcourse') == 'yes') {
                $rs = $rs->whereHas('class.faculty.subject',
                    function ($query) {
                        $query->whereRaw('subjects.id not in (select scores.subject_id from scores where scores.student_id = students.id)');
                    }, '=', 0);
            } else {
                $rs = $rs->whereHas('class.faculty.subject',
                    function ($query) {
                        $query->whereRaw('subjects.id not in (select scores.subject_id from scores where scores.student_id = students.id)');
                    }, '>', 0);
            }
        }

        $rs = $rs->with(['user', 'class.faculty', 'subjects'])->paginate($limit);
        return $rs;
    }


    public function getStudentsByScore($minScore = 0, $maxScore = 10)
    {
        $rs = $this->model;

        $GLOBALS['minScore'] = 'AVG(scores.score) >= ' . (float)$minScore;
        $rs = $rs->whereExists(function ($query) {
            $query->select(DB::raw('scores.student_id'))
                ->from('scores')->groupBy('scores.student_id')
                ->havingRaw($GLOBALS['minScore'])
                ->whereRaw('scores.student_id = students.id');
        });

        $GLOBALS['maxScore'] = 'AVG(scores.score) <= ' . (float)$maxScore;
        $rs = $rs->whereExists(function ($query) {
            $query->select(DB::raw('scores.student_id'))
                ->from('scores')->groupBy('scores.student_id')
                ->havingRaw($GLOBALS['maxScore'])
                ->whereRaw('scores.student_id = students.id');
        });

        $rs = $rs->whereHas('class.faculty.subject',
            function ($query) {
                $query->whereRaw('subjects.id not in (select scores.subject_id from scores where scores.student_id = students.id)');
            }, '=', 0);

        return $rs->get();
    }

}
