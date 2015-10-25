<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class ToGameRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->isMethod('get');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'studentId' => 'required|exists:students,id',
            //'studentId' => 'exists'
            //'studentId' => 'regex:/^a\d{7}$/',

        ];
    }

    /**
     * Set custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'studentId.required' => 'Please input your student id.',
            'studentId.exists' => 'This id does not exist.',
        ];
    }
}
