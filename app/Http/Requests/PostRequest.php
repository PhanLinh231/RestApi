<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
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
            'content' => 'bail|string|max:1000',
            'country' => "bail|string",
            'city' => 'bail|string',
            'village' => 'bail|string',
            'start_date' => 'bail|date',
            'end_date' => 'bail|date',
            'status' => 'bail|string'
        ];
    }
}
