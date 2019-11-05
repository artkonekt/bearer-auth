<?php
/**
 * Contains the AuthTokenRequest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 *
 * @since       2019-09-20
 */

namespace Konekt\BearerAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthTokenRequest extends FormRequest
{
    public function rules()
    {
        return [
            'refresh_token' => 'required|min:127',
        ];
    }

    public function getRefreshToken(): string
    {
        return $this->get('refresh_token');
    }

    public function authorize()
    {
        return true;
    }
}
