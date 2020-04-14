<?php
/**
 * Copyright (c) 2020 ibeta.me
 * User: Jacky
 * Class: SocialAuthorizationRequest.php
 * Description:
 * Date: 2020/01/24
 * Time: 5:3 下午
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SocialAuthorizationRequest extends FormRequest
{
    private $code;
    private $access_token;
    private $openid;

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
            'code' => 'required_without:access_token|string',
            'access_token' => 'required_without:code|string',
        ];

        if (!$this->code) {
            //$rules['openid'] = 'required|string';
        }

        return $rules;
    }
}
