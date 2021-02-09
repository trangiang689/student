<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateClassRequest extends FormRequest
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
            'name' => [
                'required', 'max:255', 'min:3',
                'unique' => 'unique:classes,name'
            ],

            'faculty_id' =>[
                'required',
                'exists' => 'exists:faculties,id'
            ]

        ];

        if ($this->method() == 'PUT') {
            $rules['name']['unique'] = Rule::unique('classes')->ignore($this->route('class'));
        }

        return $rules;
    }
}
