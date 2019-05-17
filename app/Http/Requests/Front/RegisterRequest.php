<?php
/**
 * Date: 2019/4/1 Time: 16:49
 *
 * @author  Eddy <cumtsjh@163.com>
 * @version v1.0.0
 */

namespace App\Http\Requests\Front;

use App\Foundation\Regexp;
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
            'name' => [
                'required',
                'regex:/' . Regexp::USERNAME . '/',
                'unique:users,name'
            ],
            'phone' => [
                'required',
                'regex:/' . Regexp::PHONE . '/',
                'unique:users,phone'
            ],
            'repeat_password' => 'required|same:password',
            'password' => [
                'required',
                'regex:/' . Regexp::PASSWORD . '/'
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
            'name.required' => '用户名不能为空',
            'name.regex' => '用户名格式有误',
            'name.unique' => '用户名已存在',
            'phone.required' => '手机号不能为空',
            'phone.regex' => '手机号格式有误',
            'phone.unique' => '手机号已存在',
            'password.required' => '密码不能为空',
            'password.regex' => '密码6到18位，不能为纯数字或纯字母',
            'repeat_password.same' => '两次输出密码不一致',
        ];
    }
}
