<?php
/**
 * Created by PhpStorm.
 * User: Odeen
 * Date: 2016/2/23
 * Time: 17:57
 */

namespace App\Api;

use Dingo\Api\Exception\ResourceException;

class ValidationHttpException extends ResourceException
{
    public function __construct( $errors=null, \Exception $previous=null, array $headers=[], $code=0)
    {
        //详细错误存在,返回第一个错误
        if ($errors->getMessages() && is_array($errors->getMessages())) {
            $message = null;
            $detailErrors = $errors->getMessages();
            foreach ($detailErrors as $v) {
                $message = $v[0];
                break;
            }
        }
        $message = $message ? $message : null;
        parent::__construct($message, $errors, $previous, $headers, $code);
    }
}