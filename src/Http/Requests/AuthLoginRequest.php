<?php

namespace Konekt\BearerAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
    public function rules()
    {
        return [
            'client_id' => 'required',
            'client_secret' => 'required|min:7',
        ];
    }

    public function getClientId(): string
    {
        return $this->get('client_id');
    }

    public function getClientSecret(): string
    {
        return $this->get('client_secret');
    }

    public function authorize()
    {
        return true;
    }
}
