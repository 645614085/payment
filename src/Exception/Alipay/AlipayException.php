<?php
/**
 * Created by PhpStorm.
 * User: zhangzetao
 * Date: 2018/12/15
 * Time: 03:08
 */

namespace ZZT\Payment\Exception\Alipay;

use Throwable;

class AlipayException extends \Exception
{
    const ALIPAY_EXCEPTION = 2001; //支付宝异常

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}