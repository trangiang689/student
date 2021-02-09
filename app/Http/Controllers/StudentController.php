<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStudentRequest;
use App\Models\Faculty;
use App\Models\Students;
use App\Models\User;
use App\Repositories\Classes\ClassRepositoryInterface;
use App\Repositories\Faculties\FacultyRepositoryInterface;
use App\Repositories\Students\StudentRepositoryInterface;
use App\Repositories\Subjects\SubjectRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentRepoSitory;
    protected $userRepoSitory;
    protected $facultyRepoSitory;
    protected $classRepoSitory;
    protected $subjectRepoSitory;
    protected $studentModel;

    public function __construct(StudentRepositoryInterface $student, FacultyRepositoryInterface $faculty, SubjectRepositoryInterface $subject,
                                ClassRepositoryInterface $class, UserRepositoryInterface $user)
    {
        $this->facultyRepoSitory = $faculty;
        $this->classRepoSitory = $class;
        $this->studentRepoSitory = $student;
        $this->userRepoSitory = $user;
        $this->subjectRepoSitory = $subject;
        $this->studentModel = $this->studentRepoSitory->newModel();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->has('limit') || $request->get('limit') <= 0) {
            $request->merge(['limit' => collect(\Config::get('constants.PER_PAGES'))->first()]);
        }else{
            $request->merge(['limit' => (int)$request->get('limit')]);
        }

        if (!$request->has('phonenumbertype') || !array_key_exists($request->get('phonenumbertype'), \Config::get('constants.PHONE_NUMBERS'))) {
            $request->merge(['phonenumbertype' => null]);
        }

        if (!$request->has('yearoldstart') || (int)$request->get('yearoldstart') < 0) {
            $request->merge(['yearoldstart' => null]);
        }

        if (!$request->has('yearoldend') || (int)$request->get('yearoldend') < 0) {
            $request->merge(['yearoldend' => null]);
        }

        if (!$request->has('scorestart') || (float)$request->get('scorestart') < 0 || (float)$request->get('scorestart') > 10) {
            $request->merge(['scorestart' => null]);
        }

        if ((float)$request->get('scoreend') < 0 || (float)$request->get('scoreend') > 10) {
            $request->merge(['scoreend' => null]);
        }

        $limit = $request->get('limit');
        $students = $this->studentRepoSitory->getPaginateStudent($request);
        $count = 1 + ($students->currentPage() - 1) * $limit;


        $facultiesObject = $this->facultyRepoSitory->getALL();
        $faculties = [];
        foreach ($facultiesObject as $facultyObject) {
            $faculties[$facultyObject->id] = $facultyObject->name;
        }

        $classes = [];
        foreach ($faculties as $faculty_id => $faculty_name) {
            if (!empty($this->facultyRepoSitory->find($faculty_id,)->classes->toArray())) {
                $classes[$faculty_id] = $this->facultyRepoSitory->find($faculty_id)->classes;
            }
        }

        return view('admin.students.index', compact('students', 'limit', 'count', 'request', 'faculties', 'classes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $studentModel = $this->studentModel;
        $facultiesObject = $this->facultyRepoSitory->getAll();
        $faculties = [];
        foreach ($facultiesObject as $facultyObject) {
            $faculties[$facultyObject->id] = $facultyObject->name;
        }
        $classes = array();
        foreach ($faculties as $faculty_id => $faculty_name) {
            if (!empty($this->facultyRepoSitory->find($faculty_id)->classes->toArray())) {
                $classes[$faculty_id] = $this->facultyRepoSitory->find($faculty_id)->classes;
            }
        }
        return view('admin.students.create', compact('studentModel', 'faculties', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateStudentRequest $request)
    {
//        create user for student
        $user = [
            'name' => $request->get('full_name'),
            'email' => $request->get('email'),
            'password' => bcrypt('student')
        ];

        $this->userRepoSitory->create($user, ['student']);
        $user_id = $this->userRepoSitory->newModel()->where('email', $request->get('email'))->first()->id;
        $request->merge(['user_id' => $user_id]);

//        upload avatar
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $avatar_name = 'avatar_' . $user_id . '.' . $file->getClientOriginalExtension();
            $file->move(\Config::get('constants.LOCATION_AVATARS'), $avatar_name);
        }
        $student = $request->except('avatar');
        $student['avatar'] = $avatar_name;
        $this->studentModel->create($student);
        return redirect()->route('admin.students.create')->with('success', sprintf('Thêm student %s thành công', $request->get('full_name')));


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $student = $this->studentRepoSitory->find($id);
        if ($student) {
            $scores = $student->subjects;
            return view('admin.students.show', compact('student', 'scores'));
        } else {
            return redirect()->route('admin.students.index')->with('warning', sprintf('Trang không tồn tại'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $student = $this->studentRepoSitory->find($id);
        if ($student) {
            $subjectsDisabled = [];
            $subjectsSelected = $student->subjects;

            foreach ($subjectsSelected as $key => $subjectSelected) {
                $subjectsDisabled[$subjectSelected->id] = $subjectSelected->pivot->score;
            }
            $facultiesObject = $this->facultyRepoSitory->getALL();
            $subjectsObject = $this->subjectRepoSitory->getAll();
            $faculties = [];
            $subjects = [];
            foreach ($facultiesObject as $facultyObject) {
                $faculties[$facultyObject->id] = $facultyObject->name;
            }

            foreach ($subjectsObject as $subjectObject) {
                $subjects[$subjectObject->id] = $subjectObject->name;
            }

            $studentModel = $this->studentModel;
            $classes = array();
            foreach ($faculties as $faculty_id => $faculty_name) {
                if (!empty($this->facultyRepoSitory->find($faculty_id,)->classes->toArray())) {
                    $classes[$faculty_id] = $this->facultyRepoSitory->find($faculty_id)->classes;
                }
            }
            return view('admin.students.edit', compact('student', 'studentModel', 'faculties', 'classes', 'subjects', 'subjectsDisabled'));
        } else {
            return redirect()->route('admin.students.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateStudentRequest $request, $id)
    {
        $student = $this->studentRepoSitory->find($id);
        if ($student) {
            //update User
            $request->merge(['name' => $request->get('full_name')]);
            $this->userRepoSitory->update($student->user_id, $request->all());

            //update avatar
            $studentUpdate = $request->except('avatar');
            if ($request->hasFile('avatar')) {
                \File::delete(\Config::get('constants.LOCATION_AVATARS') . '/' . $student->avatar);
                $file = $request->file('avatar');
                $avatar_name = 'avatar_' . $student->user_id . '.' . $file->getClientOriginalExtension();
                $file->move(\Config::get('constants.LOCATION_AVATARS'), $avatar_name);
                $studentUpdate['avatar'] = $avatar_name;
            }

            //update student
            $this->studentRepoSitory->update($id, $studentUpdate);


            // Update Scores

            $sync = [];
            if (!empty($request->get('subjects'))) {
                foreach ($request->get('subjects') as $key => $subject) {
                    $sync[$subject] = ['score' => $request->get('scores')[$key]];
                }
            }
            $this->studentRepoSitory->updateScores($id, $sync);
            return redirect()->route('admin.students.edit', ['student' => $id])->with('success', sprintf('Sửa học sinh %s thành công', $request->get('name')));
        } else {
            return redirect()->route('admin.students.index')->with('warning', sprintf('Id không tồn tại'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $student = $this->studentRepoSitory->find($id);
        if ($student) {
            $user_id = $student->user_id;
            $relations = [];
            $relationsManyToMany = ['subjects'];
            $this->studentRepoSitory->delete($id, $relations, $relationsManyToMany);
            $this->userRepoSitory->delete($user_id, [], []);
            return redirect()->route('admin.students.index')->with('success', 'Xóa students thành công');
        } else {
            return redirect()->route('admin.students.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }
}
