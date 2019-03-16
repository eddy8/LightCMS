<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\EntityField;
use Illuminate\Validation\Rule;

class EntityFieldRequest extends FormRequest
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
            'name' => ['required', 'max:64', 'regex:/^[0-9a-zA-Z$_]+$/'],
            'entity_id' => 'required|integer|min:1',
            'form_name' => 'required|max:20',
            'order' => 'required|integer',
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
            'name.required' => '字段名称不能为空',
            'name.max' => '字段名称长度不能大于64',
            'name.regex' => '字段名称格式有误',
        ];
    }
}
