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

    private $url;//接口地址

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

        if ($this->alipayConfig->isSandBox()){//沙箱环境
            $this->url = 'https://openapi.alipaydev.com/gateway.do';
        }else{//正式环境
            $this->url = 'https://openapi.alipay.com/gateway.do';
        }

    }

    //设置支付宝的支付请求
    private function setRequestBaseBody(){
        return array_filter([
            'app_id'    =>      $this->alipayConfig->getAppId(),
            'method'    =>      $this->url,
            'charset'   =>      $this->alipayConfig->getCharset(),
            'sign_type' =>      $this->alipayConfig->getSignType(),
            'timestamp' =>      $this->alipayConfig->getTimestamp(),
            'version'   =>      $this->alipayConfig->getVersion(),
            'notify_url'=>      $this->alipayConfig->getNotifyUrl(),
            'app_auth_token'=>  $this->alipayConfig->getAppAuthToken()
        ]);
    }

    //创建订单
    public function createOrder($orderParams){
        $requestBody  =  $this->setRequestBaseBody();
        $bizContent['out_trade_no'] = $orderParams['out_trade_no'];
        $bizContent['scene']        = $orderParams['scene'];
        $bizContent['auth_code']    = $orderParams['auth_code'];
        $bizContent['subject']      = $orderParams['subject'];
        $bizContent['total_amount'] = $orderParams['total_amount'];
        $requestBody['biz_content'] = json_encode($orderParams);
        $requestBody['sign'] = Helper::generateSign(
            $this->alipayConfig->getPrivateKey(),
            $requestBody,
            $this->alipayConfig->getSignType()
        );

        $response = HttpClient::getInstance()->setConn($this->url)
            ->setRequestBody($requestBody)
            ->exec();
        var_dump($response);
    }


}