<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\Entity;
use Illuminate\Validation\Rule;

class EntityRequest extends FormRequest
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
            'name' => 'required|max:50',
            'table_name' => ['required', 'max:64', 'regex:/^[0-9a-zA-Z$_]+$/'],
            'description' => 'max:255',
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
            'name.required' => '名称不能为空',
            'name.max' => '名称长度不能大于50',
            'table_name.regex' => '数据库表名不合规范',
        ];
    }
}
