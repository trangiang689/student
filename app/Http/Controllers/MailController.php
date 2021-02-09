<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeEmail;
use App\Jobs\SendEmail;
use App\Mail\TestMail;
use App\Repositories\Students\StudentRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    protected $studentRepository;

    public function __construct(StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
    }

    public function sendMailExpulsion()
    {
        $students = $this->studentRepository->getStudentsByScore(0, 4.999);
        $fromUser = Auth::user();
        if (!empty($students)) {
            foreach ($students as $student) {
                if ($student->user) {
                    $this->dispatch(new SendEmail($fromUser, $student->user));
                }
            }
        }
        return redirect()->back()->with('success', 'Send mails success');
    }


    public function index(){
        return  view('admin.mails.index');
    }
}
