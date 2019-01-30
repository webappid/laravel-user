<?php
/**
 * Created by PhpStorm.
 * UserParam: dyangalih
 * Date: 2019-01-18
 * Time: 02:59
 */

namespace WebAppId\User\Requests;


use Illuminate\Foundation\Http\FormRequest;

abstract class AbstractFormRequest extends FormRequest implements FormRequestContract
{
    
    public function authorize()
    {
        return true;
    }
    
    abstract function rules();
    
    public function messages()
    {
        return [];
    }
    
    public function attributes()
    {
        return [];
    }
}