<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Students;
use App\Repositories\Admins\AdminRepositoryInterface;
use App\Repositories\Students\StudentRepositoryInterface;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MongoDB\Driver\Session;

class HomeController extends Controller
{
    protected $adminRepository;
    protected $userRepository;
    protected $studentRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AdminRepositoryInterface $adminRepository, StudentRepositoryInterface $studentRepository, UserRepositoryInterface $userRepository)
    {
        $this->middleware('auth');
        $this->adminRepository = $adminRepository;
        $this->studentRepository = $studentRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user()->admin;
        if (!$user) {
            $user = Auth::user()->student;
        }
        return view('admin.account.profile', compact('user'));
    }


    public function update(UpdateProfileRequest $request)
    {
        /*-------------------------------- update User ----------------------------------------*/
        $user = Auth::user();
        $request->merge(['name' => $request->get('full_name')]);
        $this->userRepository->update($user->id, $request->all());

        $userUpdate = $request->except('avatar', 'class_id');

        /*-------------------------------- update avatar ----------------------------------------*/
        if (!empty($request->file('avatar'))) {
            $oldAvatarName = $user->admin ? $user->admin->avatar : $user->student->avatar;
            \File::delete(\Config::get('constants.LOCATION_AVATARS') . '/' . $oldAvatarName);
            $file = $request->file('avatar');
            $avatar_name = 'avatar_' . $user->id . '.' . $file->getClientOriginalExtension();
            $file->move(\Config::get('constants.LOCATION_AVATARS'), $avatar_name);
            $userUpdate['avatar'] = $avatar_name;
        }

        /*-------------------------------- update profile ----------------------------------------*/
        if ($user->admin) {
            $this->adminRepository->update($user->admin->id, $userUpdate);
        }
        if ($user->student) {
            $this->studentRepository->update($user->student->id, $userUpdate);
        }

        return redirect()->route('admin.home')->with('success', 'Update profile success');

    }

    public function editScores()
    {
        $student = Auth::user()->student;
        if ($student) {
            $count = 1;
            $completedSubjects = $student->subjects;
            $completedSubjectIDs = [];
            foreach ($completedSubjects as $key => $completedSubject) {
                $completedSubjectIDs[] = $completedSubject->id;
            }
            $unfinishedSubjects = $student->class->faculty->subject->whereNotIn('id', array_values($completedSubjectIDs));
            return view('admin.account.scores', compact('student', 'completedSubjects', 'count', 'unfinishedSubjects'));
        } else {
            return redirect()->route('admin.home')->with('warning', 'Fail');
        }
    }

    public function UpdateScore(Request $request)
    {
        $result = [];
        $student = Auth::user()->student;
        if ($student && $request->has('subjectid')) {
            $subjectID = $request->get('subjectid');
            $completedSubjects = $student->subjects;
            $completedSubjectIDs = [];

            foreach ($completedSubjects as $key => $completedSubject) {
                $completedSubjectIDs[] = $completedSubject->id;
            }

            $unfinishedSubjects = $student->class->faculty->subject->whereNotIn('id', array_values($completedSubjectIDs));
            $validateSubjectID = false;
            foreach ($unfinishedSubjects as $key => $unfinishedSubject) {
                if ($subjectID == $unfinishedSubject->id) {
                    $validateSubjectID = true;
                    break;
                }
            }

            if ($validateSubjectID) {
                $this->studentRepository->addScore($student->id, $subjectID, 0);
                $result['status'] = 1;
                $result['msg'] = 'OK';
                $result['data'] = [
                    'scoresavg' => $this->studentRepository->newModel()->find(Auth::user()->student->id)->subjects->avg('pivot.score'),
                    'count' => $this->studentRepository->newModel()->find(Auth::user()->student->id)->subjects->count(),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            } else {
                $result['status'] = 0;
                $result['msg'] = 'You cannot update the score for this subject';
                $result['data'] = [];
            }
        } else {
            $result['status'] = 0;
            $result['msg'] = 'The parameter passed is not correct';
            $result['data'] = [];
        }
        return json_encode($result);
    }

    public function changeLanguage(Request $request, $language)
    {
        $request->session()->put('lang', $language);
        return redirect()->back();
    }

}
