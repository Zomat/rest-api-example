<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
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
        return [
            'email' => 'required|string',
            'password' => 'required|string'
        ];
    }

    /**
     * Throw an Exception when validation fails.
     *
     * @param Validator $validator
     * @throws HttpResponseException
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $data = [
            'message' => 'The given data was invalid.',
            'errors' => $validator->errors()
        ];

        throw new HttpResponseException(response()->json($data, 400));
    }
}
