<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateClassRequest;
use App\Models\Classes;
use App\Repositories\Classes\ClassRepositoryInterface;
use App\Repositories\Faculties\FacultyRepositoryInterface;
use App\Repositories\Students\StudentRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;

class ClassController extends Controller
{
    protected $class;
    protected $faculty;
    protected $studentRepository;
    protected $userRepository;
    public function __construct(ClassRepositoryInterface $class, FacultyRepositoryInterface $faculty, StudentRepositoryInterface $student, UserRepositoryInterface $user)
    {
        $this->class = $class;
        $this->faculty = $faculty;
        $this->studentRepository = $student;
        $this->userRepository = $user;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->has('limit') || $request->get('limit') <= 0){
            $request->merge(['limit' => 3]);
        }
        $limit = $request->get('limit');
        $classes = $this->class->getListPaginateClasses($limit);
        $count = 1 + ($classes->currentPage()-1)*$limit;
        return  view('admin.classes.index', compact('classes', 'limit', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facultiesObject= $this->faculty->getAll();
        $faculties = [];
        foreach ($facultiesObject as $facultyObject){
            $faculties[$facultyObject->id] = $facultyObject->name;
        }
        $class = $this->class->newModel();
        return view('admin.classes.create', compact('faculties', 'class'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateClassRequest $request)
    {
            $this->class->create($request->all());
            return redirect()->route('admin.classes.create')->with('success', sprintf('Thêm lớp %s thành công', $request->get('name')));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $class = $this->class->find($id);
        if ($class) {
            $class = $this->class->find($id);
            return view('admin.classes.show',compact('class'));
        }else{
            return redirect()->route('admin.classes.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $class = $this->class->find($id);
        if ($class) {
            $facultiesObject= $this->faculty->getAll();
            $faculties = [];
            foreach ($facultiesObject as $facultyObject){
                $faculties[$facultyObject->id] = $facultyObject->name;
            }
            return view('admin.classes.edit', compact('class', 'faculties'));
        }else{
            return redirect()->route('admin.classes.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateClassRequest $request, $id)
    {
        $class = $this->class->find($id);
        if ($class) {
            $this->class->update($id, $request->all());
            return redirect()->route('admin.classes.edit', ['class' => $id])->with('success', sprintf('Sửa lớp %s thành công', $request->get('name')));
        }else{
            return redirect()->route('admin.classes.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $class = $this->class->find($id);
        if ($class) {
            $students = $class->students;
            foreach ($students as $student){
                $user_id = $student->user_id;
                $relations = [];
                $relationsManyToMany = ['subjects'];
                $this->studentRepository->delete($student->id, $relations, $relationsManyToMany);
                $this->userRepository->delete($user_id, [], []);
            }
            $relations = ['students'];
            $relationsManyToMany = [];
            $this->class->delete($id, $relations, $relationsManyToMany);
            return redirect()->route('admin.classes.index')->with('success', 'Xóa lớp thành công');
        }else{
            return redirect()->route('admin.classes.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }
}
