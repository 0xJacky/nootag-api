<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: UserRegistrationRequest.php
 * Description:
 * Date: 2020/01/24
 * Time: 5:3 下午
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegistrationRequest extends FormRequest
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
            'name'=>['required', 'between:3,25', 'unique:users,name'],

            'email' => 'required|email|unique:users,email',

            'password' => ['required', 'min:8', 'alpha_dash']

        ];
    }
}
