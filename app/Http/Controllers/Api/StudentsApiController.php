<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Repositories\Classes\ClassRepositoryInterface;
use App\Repositories\Faculties\FacultyRepositoryInterface;
use App\Repositories\Students\StudentRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentsApiController extends Controller
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
//        $data = array("name" => "Hagrid", "age" => "36");
//        $data_string = json_encode($data);

        $curl = curl_init(route('api.students.update', ['student' => 7]));

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
//                'Content-Type: application/json',
//                'Content-Length: ' . strlen($data_string))
//        );

        $result = curl_exec($curl);
        curl_close($curl);

        echo $result;
        die();

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = [];
        $student = $this->studentRepository->find($id, ['user', 'class']);
        if ($student) {
            $result['status'] = 1;
            $result['msg'] = 'OK';
            $result['data'] = $student;
        } else {
            $result['status'] = 0;
            $result['msg'] = 'This student was not found.';
            $result['data'] = [];
        }
        return json_encode($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = [];

        $student = $this->studentRepository->find($id, ['user', 'class.faculty']);
        if ($student) {
            $validate = \Validator::make(
                $request->all(),
                [
                    'full_name' => [
                        'required', 'max:255', 'min:4'
                    ],

                    'email' => [
                        'required', 'max:100', 'email',
                        'unique' => Rule::unique('users')->ignore($student->user_id),
                    ],

                    'birth_date' => [
                        'required', 'date',
                        'before:' . (date('Y') - 17) . '-1-1',
                    ],
                    'phone_number' => [
                        'required', 'max:20',
                        'regex:/^[0-9]{8,15}$/',
                        'unique' => Rule::unique('students')->ignore($id),
                    ],

                    'home_town' => [
                        'required', 'max:255', 'min:4'
                    ],

                    'class_id' => [
                        'required',
                        'exists:classes,id'
                    ],
                    'avatar' => [
                        'image'
                    ],

                    'subjects.*' => [
                        'required',
                        'exists' => 'exists:subjects,id'
                    ],

                    'scores.*' => [
                        'required', 'min:0', 'max:10',
                        'numeric'
                    ],
                ]

            );

            if ($validate->fails()) {

                $result['status'] = 0;
                $result['msg'] = 'The data entered is not correct';
                $result['data'] = $validate->errors();
            } else {
//                update User
                $this->userRepository->update($student->user_id, $request->all());
                $studentUpdate = $request->except('avatar');

//                update avatar
                if(!empty($request->file('avatar'))){
                    \File::delete(\Config::get('constants.LOCATION_AVATARS') . '/' . $student->avatar);
                    $file = $request->file('avatar');
                    $avatar_name = 'avatar_' . $student->user_id . '.' . $file->getClientOriginalExtension();
                    $file->move(\Config::get('constants.LOCATION_AVATARS'), $avatar_name);
                    $studentUpdate['avatar'] = $avatar_name;
                }

//                //update student
                $this->studentRepository->update($id, $studentUpdate);

                $result['status'] = 1;
                $result['msg'] = 'OK';
                $student = $this->studentRepository->find($id, ['user', 'class.faculty']);
                $result['data'] = $student;
            }

        } else {
            $result['status'] = 0;
            $result['msg'] = 'This student was not found.';
            $result['data'] = [];
        }
        return json_encode($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
