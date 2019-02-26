<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\AdminUser;
use Illuminate\Validation\Rule;

class AdminUserRequest extends FormRequest
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
            AdminUser::STATUS_DISABLE,
            AdminUser::STATUS_ENABLE,
        ];
        return [
            'name' => 'required|max:50',
            'password' => $this->method() == 'PUT' ? '' : 'required|min:6|max:18',
            'status' => [
                Rule::in($status_in),
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
            'password.required' => '密码不能为空',
        ];
    }
}
