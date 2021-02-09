<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateFacultyRequest extends FormRequest
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
        if ($this->method() == 'POST') {
            $rules = [
                'vi.name' => [
                    'required', 'max:255', 'min:4',
                    'unique' => 'unique:faculty_translations,name'
                ],

                'en.name' => [
                    'required', 'max:255', 'min:4',
                    'unique' => 'unique:faculty_translations,name'
                ],

                'vi.description' => [
                    'required', 'max:65535', 'min:4',
                ],

                'en.description' => [
                    'required', 'max:65535', 'min:4',
                ],

            ];
        }

        if ($this->method() == 'PUT') {
            $rules['name']['unique'] = Rule::unique('faculty_translations')->ignore($this->route('faculty'), 'faculty_id');
        }
        return $rules;
    }
}
