<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class FileUploadPostRequest extends FormRequest
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
            'files.*' => 'required|mimes:jpg,jpeg,png,gif|max:20000'
        ];
    }
    public function messages(){
        return [      
            'files.*.required' => 'Lütfen Dosya yükleyin',
            'files.*.mimes' => 'Sadece jpeg,png ve gif dosyaları yüklenebilir',
            'files.*.max' => 'Maksimum dosya yükleme limiti 20MB lütfen daha fazlasını yüklemeye çalışmayın',
        ];
    }
    //Default errorları devre dısı bırakıp kendı errorlarımızı bastırıyoruz.
    protected function failedValidation(Validator $validator) { 
        throw new HttpResponseException(response()->json(['message' => $validator->errors()->first(),
                                                          'success' => false], 422)); 
    }
}
