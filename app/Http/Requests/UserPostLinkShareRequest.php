<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UserPostLinkShareRequest extends FormRequest
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
            'write' => 'max:400',
            'link' => 'required',
        ];
    }
    public function messages(){
        return [      
            'write.max' => 'En fazla 400 karakterlik gönderi paylaşabilirsizz.',
            'link.required' => 'Bağlantı alanı eklenmeli!'
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
