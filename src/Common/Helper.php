<?php
/**
 * Created by PhpStorm.
 * User: zhangzetao
 * Date: 2018/12/15
 * Time: 03:25
 */

namespace ZZT\Payment\Common;

use ZZT\Payment\Exception\Alipay\AlipayException;

class Helper
{

    /**
     * 根据私钥生成签名
     * @param $privateKey
     * @param $data
     * @param string $signType
     * @return string
     * @throws AlipayException
     */
    public static function generateSign($privateKey, $data, $signType = 'RSA2')
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        $res = openssl_get_privatekey($privateKey);
        if (!$res) {
            throw new AlipayException('PRIVATE_KEY_ERROR', AlipayException::ALIPAY_EXCEPTION);
        }
        ksort($data);
        $str_sign = '';
        $count = count($data);
        $i = 0;
        foreach ($data as $k => $v) {
            $i++;
            if ($v && substr($v, 0, 1) != '@') {
                if ($i === $count) {
                    $str_sign .= $k . '=' . $v;
                } else {
                    $str_sign .= $k . '=' . $v . '&';
                }
            }
        }
        if (strtoupper($signType) === "RSA2") {
            openssl_sign($str_sign, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($str_sign, $sign, $res);
        }
        openssl_free_key($res);
        $sign = base64_encode($sign);
        return $sign;
    }


    /**
     * 校验签名是否正确
     * @param $data
     * @param $sign
     * @param $publicKey
     * @param bool $asyn
     * @param string $signType
     * @return bool
     * @throws AlipayException
     *
     */
    public static function checkSign($data, $sign, $publicKey, $asyn = false, $signType = 'RSA2'){
        $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
            wordwrap($publicKey, 64, "\n", true) .
            "\n-----END PUBLIC KEY-----";
        $res = openssl_get_publickey($publicKey);
        if (!$res) {
            throw new AlipayException('PRIVATE_KEY_ERROR', AlipayException::ALIPAY_EXCEPTION);
        } else {
            $str = '';
            $count = count($data);
            $i = 0;
            //判断是否回调
            if ($asyn) {
                ksort($data);
                $count = count($data);
                $i = 0;
                foreach ($data as $k => $v) {
                    $i++;
                    if ($i === $count) {
                        $str .= $k . '=' . urldecode($v);
                    } else {
                        $str .= $k . '=' . urldecode($v) . '&';
                    }
                }
            } else {
                $str = json_encode($data, JSON_UNESCAPED_UNICODE);
            }
            if (strtoupper($signType) == "RSA2") {
                $result = (bool) openssl_verify($str, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
            } else {
                $result = (bool) openssl_verify($str, base64_decode($sign), $res);
            }
            openssl_free_key($res);
        }
        return $result;
    }

}