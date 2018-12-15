<?php
/**
 * Created by PhpStorm.
 * User: zhangzetao
 * Date: 2018/12/15
 * Time: 03:01
 */

namespace ZZT\Payment\Alipay;


use ZZT\Payment\Common\Helper;
use ZZT\Payment\Exception\Alipay\AlipayException;
use ZZT\Payment\HttpClient;

class AlipayApi
{
    private $alipayConfig;

    private $method;//接口地址

    private $requestBaseBody;

    /**
     * AlipayApi constructor.
     * @param AlipayConfig $alipayConfig
     * @throws AlipayException
     */
    public function __construct(AlipayConfig $alipayConfig)
    {
        $this->alipayConfig = $alipayConfig;
        $this->checkConfig();
    }


    /**
     * @throws AlipayException
     */
    public function checkConfig(){

        if (empty($this->alipayConfig->getAppId())){
            throw new AlipayException("appId不能为null",AlipayException::ALIPAY_EXCEPTION);
        }

        if (empty($this->alipayConfig->getPrivateKey())){
            throw new AlipayException("appId不能为null",AlipayException::ALIPAY_EXCEPTION);
        }

        if (empty($this->alipayConfig->getPublicKey())){
            throw new AlipayException("appId不能为null",AlipayException::ALIPAY_EXCEPTION);
        }

        if (empty($this->alipayConfig->getSignType())){
            throw new AlipayException("appId不能为null",AlipayException::ALIPAY_EXCEPTION);
        }
    }

    //设置支付宝的支付请求
    private function setRequestBaseBody($method){
        $this->method = $method;
        return array_filter([
            'app_id'    =>      $this->alipayConfig->getAppId(),
            'method'    =>      $this->method,
            'charset'   =>      $this->alipayConfig->getCharset(),
            'sign_type' =>      $this->alipayConfig->getSignType(),
            'timestamp' =>      $this->alipayConfig->getTimestamp(),
            'version'   =>      $this->alipayConfig->getVersion(),
            'notify_url'=>      $this->alipayConfig->getNotifyUrl(),
            'app_auth_token'=>  $this->alipayConfig->getAppAuthToken()
        ]);
    }

    /**
     * @param $orderParams
     * @return mixed
     * @throws AlipayException
     * @throws \ZZT\Payment\Exception\HttpClientException
     *
     */
    public function createOrder($orderParams){
        $requestBody  =  $this->setRequestBaseBody('alipay.trade.page.pay');
        $bizContent['out_trade_no'] = $orderParams['out_trade_no'];
        $bizContent['subject']      = $orderParams['subject'];
        $bizContent['total_amount'] = $orderParams['total_amount'];
        //$bizContent['buyer_id']     = $orderParams['buyer_id'];
        $bizContent['product_code'] = 'FAST_INSTANT_TRADE_PAY';
        $requestBody['biz_content'] = json_encode($bizContent);

        $sign = Helper::generateSign(
            $this->alipayConfig->getPrivateKey(),
            $requestBody,
            $this->alipayConfig->getSignType()
        );

//        var_dump($check);
//
//        var_dump($this->alipayConfig->getPrivateKey());
//        var_dump($requestBody);
//        var_dump($this->alipayConfig->getSignType());
//        exit;
       $requestBody['sign'] = $sign;
       HttpClient::getInstance()->setUrl($this->alipayConfig->getUrl())
            ->setRequestBody($requestBody)
            ->forward();
    }


}