<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use App\Model\Admin\Comment;
use Illuminate\Validation\Rule;

class CommentRequest extends FormRequest
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
            Comment::STATUS_DISABLE,
            Comment::STATUS_ENABLE,
        ];
        return [
            'name' => 'required|max:50',
            //'status' => [
            //    Rule::in($status_in),
            //],
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
        ];
    }
}
