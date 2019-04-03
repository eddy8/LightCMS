<?php
/**
 * Date: 2019/4/1 Time: 16:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Requests\Front;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'phone' => [
                'required',
                'regex:/^(?:\+?86)?1(?:3\d{3}|5[^4\D]\d{2}|8\d{3}|7(?:[01356789]\d{2}|4(?:0\d|1[0-2]|9\d))|9[189]\d{2}|6[567]\d{2}|4(?:[14]0\d{3}|[68]\d{4}|[579]\d{2}))\d{6}$/'
            ],
            'repeat_password' => 'required|same:password',
            'password' => [
                'required',
                'regex:/^(?![0-9]+$)(?![a-zA-Z]+$)[\w\x21-\x7e]{6,18}$/'
            ],
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
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式有误',
            'password.required' => '密码不能为空',
            'password.regex' => '密码6到18位，不能为纯数字或纯字母',
            'repeat_password.same' => '两次输出密码不一致',
        ];
    }
}