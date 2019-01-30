<?php
/**
 * Created by PhpStorm.
 * UserParam: dyangalih
 * Date: 2019-01-18
 * Time: 02:56
 */

namespace WebAppId\User\Requests;


interface FormRequestContract
{
    public function authorize();
    
    public function rules();
    
    public function messages();
    
    public function attributes();
}