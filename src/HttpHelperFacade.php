<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Padosoft\HTTPClient\MethodHttpHelper;
use GuzzleHttp\Client;

/**
 * Class HttpHelper
 * HTTP Helper class
 * @package Padosoft\HTTPClient
 */
class HttpHelperFacade
{
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var HTTPClient
     */
    protected $httpclient;
    /**
     * @var \Padosoft\HTTPClient\RequestHelper
     */
    protected $requestHelper;

    /**
     * HttpHelper constructor.
     * @param HTTPClient $httpclient
     */
    public function __construct(\Padosoft\HTTPClient\HTTPClient $httpclient)
    {
        $this->httpclient = $httpclient;
        $this->requestHelper = $httpclient->requestHelper;
        $this->response = new Response();
    }

    /**
     * Send HTTP Request
     *
     * @param $method
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @param string $user
     * @param string $password
     * @param array $jsonParams
     * @param int $requesTimeout
     * @param bool $SSLVerify
     * @param array $customHeaders
     * @param string $accept
     * @param string $protocolVersion
     * @return Response
     */
    public static function sendSimpleRequest($method, $uri
                                    , array $getParams=[], array $postParams=[]
                                    , $user="", $password=""
                                    , array $jsonParams=[]
                                    , $requesTimeout=0, $SSLVerify=true
                                    , array $customHeaders=[], $accept="", $protocolVersion=""
                                    )
    {

        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);

        $response = $httpHelper->sendSimpleRequest($method, $uri
            , $getParams, $postParams
            , $user, $password
            , $jsonParams
                , $requesTimeout, $SSLVerify
                , $customHeaders, $accept, $protocolVersion);

        return $response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @return Response
     */
    public static function sendGet($uri, array $getParams=[], array $postParams=[])
    {

        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);
        $response = $httpHelper->sendGet($uri, $getParams, $postParams);

        return $response;

    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @param $user
     * @param $password
     * @return Response
     */
    public static function sendGetWithAuth($uri, array $getParams=[], array $postParams=[], $user, $password)
    {

        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);
        $response = $httpHelper->sendGetWithAuth($uri,  $getParams,  $postParams, $user, $password);

        return $response;


    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @return Response
     */
    public static function sendPost($uri, array $getParams=[], array $postParams=[])
    {
        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);
        $response = $httpHelper->sendPost($uri,  $getParams,  $postParams);

        return $response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @param $user
     * @param $password
     * @return Response
     */
    public static function sendPostWithAuth($uri, array $getParams=[], array $postParams=[], $user, $password)
    {
        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);
        $response = $httpHelper->sendPostWithAuth($uri, $getParams,  $postParams, $user, $password);

        return $response;
    }

    /**
     * @param $uri
     * @param array $jsonParams
     * @param $user
     * @param $password
     * @return Response
     */
    public static function sendPostJsonWithAuth($uri, array $jsonParams=[], $user, $password)
    {
        $requestHelper = new RequestHelper();
        $guzzle = new Client();
        $httpClient = new HTTPClient($guzzle,$requestHelper);
        $httpHelper = new HttpHelper($httpClient);
        $response = $httpHelper->sendPostJsonWithAuth($uri, $jsonParams, $user, $password);
        return $response;
    }


}
