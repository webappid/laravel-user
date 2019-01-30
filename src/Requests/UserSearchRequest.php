<?php
/**
 * Created by PhpStorm.
 * User: dyangalih
 * Date: 2019-01-30
 * Time: 17:40
 */

namespace WebAppId\User\Requests;



class UserSearchRequest extends AbstractFormRequest
{
    
    function rules()
    {
        return [
            'q' => 'string|nullable|max:255'
        ];
    }
}