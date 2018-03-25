<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserUsernameUpdate extends FormRequest
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
            'username' => 'required|unique:users,username'
        ];
    }
    public function messages(){
        return [      
            'username.unique' => 'Bu kullanıcı adı kullanılmaktadır, lütfen başka bir tane deneyiniz.'
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
