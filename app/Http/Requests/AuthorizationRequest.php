<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: AuthorizationRequest.php
 * Description:
 * Date: 2020/01/24
 * Time: 5:3 下午
 */

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AuthorizationRequest extends FormRequest
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
            'password' => 'required|string|min:6',
            'username' => 'required|string',
        ];
    }
}
