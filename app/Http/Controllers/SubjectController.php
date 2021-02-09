<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateSubjectRequest;
use App\Repositories\Faculties\FacultyRepositoryInterface;
use App\Repositories\Subjects\SubjectRepositoryInterface;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $faculty;
    protected $subject;

    public function __construct(FacultyRepositoryInterface $faculty, SubjectRepositoryInterface $subject)
    {
        $this->faculty = $faculty;
        $this->subject = $subject;
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
        $subjects = $this->subject->getPaginate($limit);
        $count = 1 + ($subjects->currentPage() - 1) * $limit;
        return view('admin.subjects.index', compact('subjects', 'limit', 'count'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facultiesObject = $this->faculty->getAll();
        $subject = $this->subject->getModel();
        $faculties = [];
        foreach ($facultiesObject as $facultyObject) {
            $faculties[$facultyObject->id] = $facultyObject->name;
        }
        $facultiesDisabled = [];
        $facultiesDisabled = json_encode($facultiesDisabled);
        return view('admin.subjects.create', compact('subject', 'faculties', 'facultiesDisabled'));
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateSubjectRequest $request)
    {

        $this->subject->create($request->all());
        return redirect()->route('admin.subjects.create')->with('success', sprintf('Thêm subject %s thành công', $request->get('name')));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subject = $this->subject->find($id);
        if ($subject) {
            $faculties = $this->subject->find($id)->faculty;
            return view('admin.subjects.show', compact('subject', 'faculties'));
        } else {
            return redirect()->route('admin.subjects.index')->with('warning', sprintf('Id không tồn tại'));
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

        $subject = $this->subject->find($id);
        if ($subject) {
            $facultiesDisabled = [];
            $facultiesSelected = $this->subject->find($id)->faculty;
            foreach ($facultiesSelected as $key => $facultySelected) {
                $facultiesDisabled[$key] = $facultySelected->id;
            }
            $facultiesDisabled = json_encode(array_values($facultiesDisabled));

            $facultiesObject = $this->faculty->getAll();
            $faculties = [];
            foreach ($facultiesObject as $facultyObject) {
                $faculties[$facultyObject->id] = $facultyObject->name;
            }
            return view('admin.subjects.edit', compact('subject', 'faculties', 'facultiesDisabled'));
        } else {
            return redirect()->route('admin.subjects.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CreateSubjectRequest $request, $id)
    {
        $subject = $this->subject->find($id);
        if ($subject) {
            $faculties = $request->get('faculties');
            $this->subject->update($id, $request->all());
            $this->subject->createFacultiesSubjects($id, $faculties);
            return redirect()->route('admin.subjects.edit', ['subject' => $id])->with('success', sprintf('Sửa môn học %s thành công', $request->get('name')));
        } else {
            return redirect()->route('admin.subjects.index')->with('warning', sprintf('Id không tồn tại'));
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
        $subject = $this->subject->find($id);
        if ($subject) {
            $relations = [];
            $relationsManyToMany = ['faculty','students'];
            $this->subject->delete($id, $relations, $relationsManyToMany);
            return redirect()->route('admin.subjects.index')->with('success', 'Xóa môn học thành công');
        } else {
            return redirect()->route('admin.subjects.index')->with('warning', sprintf('Id không tồn tại'));
        }
    }
}
