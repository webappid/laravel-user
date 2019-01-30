<?php

namespace WebAppId\User\Requests;

class AddUserRequest extends AbstractFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'status_id' => 'required|int',
            'roles' => 'required|array'
        ];
    }
}
