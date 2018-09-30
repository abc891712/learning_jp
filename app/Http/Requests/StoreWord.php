<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWord extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'japanese' => 'required|regex:([ぁ-んァ-ン])',
            'level'    => 'required|numeric',
            'word'     => 'required|regex:([一-龯])|regex:([ぁ-んァ-ン])',
            'chinese'  => 'required|regex:/[\x{4e00}-\x{9fa5}]+/u',
        ];
    }

    public function messages()
    {
        return [
            'japanese.regex'       => '請輸入日文',
            'japanese.required'    => '請填寫日文欄',
            'level.required'       => '請選擇級別',
            'level.numeric'        => '請輸入數字',
            'word.required'        => '請填寫漢字',
            'word.regex'           => '請輸入漢字',
            'chinese.required'     => '請填寫中文',
            'chinese.regex'        => '請輸入中文',
        ];
    }
}
