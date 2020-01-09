<?php

namespace App\Http\Requests\Admin;

use App\Rules\JsonStr;
use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\Config;
use Illuminate\Validation\Rule;

class ConfigRequest extends FormRequest
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
        $rules = [
            'name' => 'required|max:50',
            'key' => ['required', 'max:100', 'regex:/^[\w]+$/'],
            'value' => 'max:2048',
            'type' => [
                Rule::in(array_keys(Config::$types)),
            ],
        ];
        if ((int) request()->input('type') === Config::TYPE_JSON) {
            $rules['value'] = [
                'required',
                'max:2048',
                new JsonStr()
            ];
        }

        return $rules;
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
            'key.regex' => '标识符非法'
        ];
    }
}
