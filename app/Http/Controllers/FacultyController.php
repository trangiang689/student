<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFacultyRequest;
use App\Models\Faculty;
use App\Repositories\Classes\ClassRepositoryInterface;
use App\Repositories\Faculties\FacultyRepositoryInterface;
use App\Repositories\Students\StudentRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    protected $faculty;
    protected $studentRepository;
    protected $userRepository;
    protected $classRepository;

    public function __construct(FacultyRepositoryInterface $faculty, StudentRepositoryInterface $student, UserRepositoryInterface $user, ClassRepositoryInterface $class)
    {
        $this->faculty = $faculty;
        $this->studentRepository = $student;
        $this->userRepository = $user;
        $this->classRepository = $class;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->has('limit') || $request->get('limit') <= 0) {
            $request->merge(['limit' => 3]);
        }
        $limit = $request->get('limit');

        $faculties = $this->faculty->getListPaginateFaculties($limit);
        $count = 1 + ($faculties->currentPage() - 1) * $limit;
        return view('admin.faculties.index', compact('faculties', 'limit', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $faculty = $this->faculty->newModel();
        return view('admin.faculties.create', compact('faculty'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateFacultyRequest $request)
    {
        if ($request->has('en.name')) {
            $request->merge(['slug' => $this->faculty->makeSlug($request->get('en')['name'])]);
        } else {
            if ($request->has('vi.name')) {
                $request->merge(['slug' => $this->faculty->makeSlug($request->get('vi')['name'])]);
            } else {
                $request->merge(['slug' => '']);
            }
        }

        $this->faculty->create($request->all());
        return redirect()->route('admin.faculties.create')->with('success', sprintf('Thêm khoa %s thành công', $request->get('name')));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $faculty = $this->faculty->find($id);
        if (!$faculty) {
            $faculty = $this->faculty->findSlug($id);
        }
        if ($faculty) {
            $subjects = $faculty->subject;
            return view('admin.faculties.show', compact('faculty', 'subjects'));
        } else {
            return redirect()->route('admin.faculties.index')->with('warning', sprintf('Trang không tồn tại'));
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
        $faculty = $this->faculty->find($id);
        if (!$faculty) {
            $faculty = $this->faculty->findSlug($id);
        }
        if ($faculty) {
            return view('admin.faculties.edit', compact('faculty'));
        } else {
            return redirect()->route('admin.faculties.index')->with('warning', sprintf('Trang không tồn tại'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateFacultyRequest $request, $id)
    {
        $faculty = $this->faculty->find($id);
        if ($faculty) {
            $request->merge(['slug' => $this->faculty->makeSlug($request->get('name'))]);
            $this->faculty->update($id, $request->all());
            return redirect()->route('admin.faculties.edit', ['faculty' => $id])->with('success', sprintf('Sửa khoa %s thành công', $request->get('name')));
        } else {
            return redirect()->route('admin.faculties.index')->with('warning', sprintf('Tham số không đúng không tồn tại'));
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
        $faculty = $this->faculty->find($id);
        if ($faculty) {
            $classes = $faculty->classes;
            foreach ($classes as $class) {
                $students = $class->students;
                foreach ($students as $student) {
                    $user_id = $student->user_id;
                    $relationsManyToMany = ['subjects'];
                    $this->studentRepository->delete($student->id, [], $relationsManyToMany);
                    $this->userRepository->delete($user_id, [], []);
                }
                $relations = ['students'];
                $relationsManyToMany = [];
                $this->classRepository->delete($id, $relations, $relationsManyToMany);
            }
            $relations = ['classes'];
            $relationsManyToMany = ['subject'];
            $this->faculty->delete($id, $relations, $relationsManyToMany);
            return redirect()->route('admin.faculties.index')->with('success', 'Xóa khoa thành công');
        } else {
            return redirect()->route('admin.faculties.index')->with('warning', sprintf('Tham số không đúng không tồn tại'));
        }
    }
}
