<?php

namespace App\Http\Requests;

//use http\Env\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSubjectRequest extends FormRequest
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
                'required', 'max:255', 'min:2',
                'unique' => 'unique:subjects,name'
            ],

            'faculties.*' =>[
                'required',
                'exists' => 'exists:faculties,id'
            ],

        ];

        if ($this->method() == 'PUT') {
            $rules['name']['unique'] = Rule::unique('subjects')->ignore($this->route('subject'));
        }
//        return [
//            'name'=>'required|min:2|max:255|unique:subjects,name',
//            'faculties.*'=>'required|exists:faculties,id',
//        ];
        return $rules;
    }
}
