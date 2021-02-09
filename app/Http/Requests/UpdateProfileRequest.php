<?php

namespace App\Http\Requests;

use App\Models\Students;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
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
                'unique' => Rule::unique('users')->ignore(Auth::id()),
            ],

            'birth_date' => [
                'required', 'date',
                'before:' . (date('Y') - 17) . '-1-1',
            ],

            'phone_number' => [
                'required', 'max:20',
                'regex:/^[0-9]{8,15}$/',
                'unique' => 'unique:students,phone_number'
            ],

            'home_town' => [
                'required', 'max:255', 'min:4'
            ],

            'avatar' => [
                'image'
            ],

        ];

        if (Auth::user()->student) {
            $rules['phone_number']['unique'] = Rule::unique('students')->ignore(Auth::user()->student->id);
        }
        if (Auth::user()->admin) {
            $rules['phone_number']['unique'] = Rule::unique('admins')->ignore(Auth::user()->admin->id);
            $rules['address'] = ['required', 'max:255', 'min:4'];
        }
        return $rules;
    }
}
