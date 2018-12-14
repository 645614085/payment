<?php
/**
 *
 * @version: 1.0.0.0
 * @datetime: 2018/12/14 11:54
 * @author: ZhangZeTao
 * @copyright: ec
 */

namespace ZZT\Payment\Exception;


use Throwable;

class HttpClientException extends \Exception {

    const UN_SUPPORT_FUNCTION   =   10001;
    const INIT_FAILED           =   10002;
    const REQUEST_FAILED           =   10003;

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

}