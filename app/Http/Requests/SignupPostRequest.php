<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class SignupPostRequest extends FormRequest
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
            'email' => 'required|unique:users,email|email',
            'username' => 'required|unique:users,username',
            'firstname' => 'required',
            'lastname' => 'required',
            'password' => ['required','min:6',
            'regex:/[0-9]([0-9]|-(?!-))+/']

        ];
    }
    public function messages(){
        return [      
            'username.unique' => 'Baska bir username kullanınız.',
            'email.unique' => 'Bu şekilde bir email adresi kullanılmaktadır lütfen doğru ve size ait bir email adresi giriniz.',
            'email.email' => 'Email standartlarına uygun geçerli bir email adresi giriniz.',
            'password.min' => 'Şifreniz en az 6 karakterli olmalıdır.',
            'password.regex' => 'Şifreniz özel karakter içeremez.'
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
