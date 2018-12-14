<?php
/**
 *
 * @version: 1.0.0.0
 * @datetime: 2018/12/14 20:35
 * @author: ZhangZeTao
 * @copyright: ec
 */

namespace ZZT\Payment\Alipay;


class AlipayConfig {

    private $appId; //支付宝应用id
    private $privateKey; //私钥
    private $publicKey; //公钥
    private $signType; //签名方式RSA
    private $notifyUrl
   private $returnUrl;
   private $appAuthToken;
   private $sandBox = false;


   public function __construct(
       $appId,
       $privateKey,
       $publicKey,
       $signType,
       $notifyUrl,
       $returnUrl,
       $appAuthToken
   ) {
       $this->appId = $appId;
       $this->privateKey = $privateKey;
       $this->publicKey = $publicKey;
       $this->signType = $signType;
       $this->notifyUrl = $notifyUrl;
       $this->returnUrl = $returnUrl;
       $this->appAuthToken = $appAuthToken;
   }


   public function setOptions(array $options) {
       foreach ($options as $key => $option) {
           $this->key = $option;
       }
   }



     /**
     * @return mixed
     */
    public function getAppId() {
        return $this->appId;
    }

    /**
     * @param mixed $appId
     */
    public function setAppId($appId) {
        $this->appId = $appId;
    }

    /**
     * @return mixed
     */
    public function getPrivateKey() {
        return $this->privateKey;
    }

    /**
     * @param mixed $privateKey
     */
    public function setPrivateKey($privateKey) {
        $this->privateKey = $privateKey;
    }

    /**
     * @return mixed
     */
    public function getPublicKey() {
        return $this->publicKey;
    }

    /**
     * @param mixed $publicKey
     */
    public function setPublicKey($publicKey) {
        $this->publicKey = $publicKey;
    }


    /**
     * @return mixed
     */
    public function getSignType() {
        return $this->signType;
    }


    /**
     * @param mixed $signType
     */
    public function setSignType($signType) {
        $this->signType = $signType;
    }


    /**
     * @return mixed
     */
    public function getNotifyUrl() {
        return $this->notifyUrl;
    }

    /**
     * @param mixed $notifyUrl
     */
    public function setNotifyUrl($notifyUrl) {
        $this->notifyUrl = $notifyUrl;
    }

    /**
     * @return mixed
     */
    public function getReturnUrl() {
        return $this->returnUrl;
    }

    /**
     * @param mixed $returnUrl
     */
    public function setReturnUrl($returnUrl) {
        $this->returnUrl = $returnUrl;
    }

    /**
     * @return mixed
     */
    public function getAppAuthToken() {
        return $this->appAuthToken;
    }

    /**
     * @param mixed $appAuthToken
     */
    public function setAppAuthToken($appAuthToken) {
        $this->appAuthToken = $appAuthToken;
    }

    /**
     * @return bool
     */
    public function isSandBox() {
        return $this->sandBox;
    }

    /**
     * @param bool $sandBox
     */
    public function setSandBox($sandBox) {
        $this->sandBox = $sandBox;
    }


}