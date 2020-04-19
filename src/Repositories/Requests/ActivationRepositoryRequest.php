<?php
/**
 * Created by LazyCrud - @DyanGalih <dyan.galih@gmail.com>
 */

namespace WebAppId\User\Repositories\Requests;

/**
 * @author: Dyan Galih<dyan.galih@gmail.com>
 * Date: 11:32:27
 * Time: 2020/04/19
 * Class ActivationRepositoryRequest
 * @package App\Repositories\Requests
 */
class ActivationRepositoryRequest
{
    
    /**
     * @var int
     */
    public $user_id;
                
    /**
     * @var string
     */
    public $key;
                
    /**
     * @var string
     */
    public $status;
                
    /**
     * @var string
     */
    public $valid_until;
                
}
