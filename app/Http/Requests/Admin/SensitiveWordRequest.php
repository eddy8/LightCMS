<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\SensitiveWord;
use Illuminate\Validation\Rule;

class SensitiveWordRequest extends FormRequest
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
            'verb' => 'required_without_all:noun,exclusive|max:100',
            'noun' => 'required_without_all:verb,exclusive|max:100',
            'exclusive' => 'required_without_all:noun,verb|max:100',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'verb.required_without_all' => '动词、名词、专有词三者必填一个',
            'noun.required_without_all' => '动词、名词、专有词三者必填一个',
            'exclusive.required_without_all' => '动词、名词、专有词三者必填一个',
        ];
    }
}
