<?php

namespace App\Http\Requests\Admin;

use App\Foundation\Regexp;
use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\User;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $status_in = [
            User::STATUS_DISABLE,
            User::STATUS_ENABLE,
        ];
        $rule = [
            'phone' => [
                'required',
                'regex:/' . Regexp::PHONE . '/',
            ],
            'status' => [
                Rule::in($status_in),
            ],
        ];
        if ($this->method() == 'POST') {
            $rule['password'] = ['required', 'regex:/' . Regexp::PASSWORD . '/'];
        }

        return $rule;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式有误',
            'password.regex' => '密码格式有误',
        ];
    }
}
