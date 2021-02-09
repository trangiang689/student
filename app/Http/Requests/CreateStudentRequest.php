<?php

namespace App\Http\Requests;

use App\Models\Students;
use App\Repositories\Students\StudentRepository;
use App\Repositories\Students\StudentRepositoryInterface;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'full_name' => [
                'required', 'max:255', 'min:4'
            ],

            'email' => [
                'required', 'max:100', 'email',
                'unique' => 'unique:users,email'
            ],

            'birth_date' => [
                'required', 'date',
                'before:'.(date('Y') - 17).'-1-1',
            ],
            'phone_number' => [
                'required', 'max:20',
                'regex:/^[0-9]{8,15}$/',
                'unique' => 'unique:students,phone_number'
            ],

            'home_town' => [
                'required', 'max:255', 'min:4'
            ],

            'class_id' =>[
                'required',
                'exists:classes,id'
            ],
            'avatar' => [
                'required',
                'image'
            ],

            'subjects.*' =>[
                'required',
                'exists' => 'exists:subjects,id'
            ],

            'scores.*' =>[
                'required','min:0', 'max:10',
                'numeric'
            ],

        ];

        if ($this->method() == 'PUT'){
            $rules['phone_number']['unique'] = Rule::unique('students')->ignore($this->route('student'));

            $user_id = Students::find($this->route('student'))->user->id;
            $rules['email']['unique'] = Rule::unique('users')->ignore($user_id);

            $rules['avatar'] = ['image'];
        }

        return $rules;
    }
}
