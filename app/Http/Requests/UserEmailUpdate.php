<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserEmailUpdate extends FormRequest
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
            'email' => 'required|unique:users,email,email|email'
        ];
    }
    public function messages(){
        return [      
            'email.unique' => 'Bu şekilde bir email adresi kullanılmaktadır, lütfen doğru ve size ait bir email adresi giriniz.',
            'email.email' => 'Email standartlarına uygun geçerli bir email adresi giriniz.',
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
