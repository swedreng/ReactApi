<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserUpdate extends FormRequest
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
            'email' => 'required','email|email', // buraya bi ara bak .. düzelt
            'username' => 'required','username',
            'firstname' => 'required',
            'lastname' => 'required'
        ];
    }
    public function messages(){
        return [      
            'username.unique' => 'Baska bir username kullanınız.',
            'email.unique' => 'Bu şekilde bir email adresi kullanılmaktadır lütfen doğru ve size ait bir email adresi giriniz.',
            'email.email' => 'Email standartlarına uygun geçerli bir email adresi giriniz.',
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
