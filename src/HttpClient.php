<?php
/**
 *
 * @version: 1.0.0.0
 * @datetime: 2018/12/14 11:40
 * @author: ZhangZeTao
 * @copyright: ec
 */

namespace ZZT\Payment;


use ZZT\Payment\Exception\HttpClientException;

class HttpClient {

    private $conn;

    private $headers = [];

    private $url;

    private $requestBody;

    private $method;

    private $cookie;


    private static $_instance;

    /**
     * HttpClient constructor.
     * @throws HttpClientException
     */
    private function __construct() {
        $this->method = 'GET';
        $this->init();
    }

    public static function getInstance(){
        if (self::$_instance == null){
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * 初始化链接
     * @throws HttpClientException
     */
    public function init(){
        if (!function_exists('curl_exec')){
            throw new HttpClientException("服务器不支持CURL功能",HttpClientException::UN_SUPPORT_FUNCTION);
        }
        $this->conn = curl_init();
        if ($this->conn === false){
            throw new HttpClientException("初始化失败",HttpClientException::INIT_FAILED);
        }

    }

    /**
     * 执行请求，获取执行结果
     * @return mixed
     * @throws HttpClientException
     */
    public function exec(){

        curl_setopt($this->conn, CURLOPT_RETURNTRANSFER, true);//要求结果为字符串且输出到屏幕上

        if (strtolower($this->method!='post')){
            curl_setopt($this->conn, CURLOPT_URL, $this->url.'?'.$this->requestBody);
        }else{
            curl_setopt($this->conn, CURLOPT_URL, $this->url);
        }

        /**
         * 设置请求方式
         */
        if (strtolower($this->method) == 'post'){
            curl_setopt($this->conn, CURLOPT_POST, true);
            curl_setopt($this->conn, CURLOPT_POSTFIELDS, $this->requestBody);
        }

        /**
         * 设置cookie
         */
        if (!is_null($this->cookie)){
            curl_setopt($this->conn, CURLOPT_COOKIE, $this->cookie);
        }

        /**
         * 设置头部信息
         */
        if (!empty($this->headers)){
            curl_setopt($this->conn, CURLOPT_HTTPHEADER, $this->headers);
        }

        curl_setopt($this->conn, CURLOPT_HEADER, true); //是否response


        $response = curl_exec($this->conn);

        if ($error = curl_error($this->conn)){
            throw new HttpClientException("请求失败：".$error,HttpClientException::REQUEST_FAILED);
        }

        return $response;
    }

    public function forward(){
        header("location:".$this->getUrl().'?'.$this->requestBody);
    }

    /**
     * @return mixed
     */
    public function getConn() {
        return $this->conn;
    }

    /**
     * @return mixed
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return $this
     *
     */
    public function setHeaders(array $headers) {
        $this->headers = $headers;
        return $this;
    }

    public function addHeaders(array $headers) {
        $this->headers = array_merge($this->headers,$headers);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     *
     */
    public function setUrl($url) {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRequestBody() {
        return $this->requestBody;
    }

    /**
     * @param $requestBody
     * @return $this
     */
    public function setRequestBody(array $requestBody) {
        $this->requestBody = '';
        foreach ($requestBody as $key=>$value){
            $this->requestBody .= '&'.$key.'='.urlencode($value);
        }
        $this->requestBody = ltrim($this->requestBody,'&');
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method) {
        $this->method = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCookie() {
        return $this->cookie;
    }

    /**
     * @param $cookie
     * @return $this
     */
    public function setCookie($cookie) {
        $this->cookie = $cookie;
        return $this;
    }



}