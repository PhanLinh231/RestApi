<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUploadRequest extends FormRequest
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
        //images
        $rules = [
            'photo' => 'array',
            'photo.*' => 'image|mimes:jpeg,bmp,png|max:2000',
            'video' => 'array',
            'video.*' => 'file|mimetypes:video/mp4',
            'file' => 'array',
            'file.*' => 'mimes:csv,txt,xlx,xls,pdf|max:2048',
        ];
        return $rules;
    }
}
