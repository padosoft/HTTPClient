<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Padosoft\HTTPClient\MethodHttpHelper;

/**
 * Class HttpHelper
 * HTTP Helper class
 * @package Padosoft\HTTPClient
 */
class HttpHelper
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
    public  function sendSimpleRequest($method, $uri
                                    , array $getParams=[], array $postParams=[]
                                    , $user="", $password=""
                                    , array $jsonParams=[]
                                    , $requesTimeout=0, $SSLVerify=true
                                    , array $customHeaders=[], $accept="", $protocolVersion=""
                                    )
    {

        if(!empty($getParams)) {
            $this->requestHelper->setQuery($getParams);
        }
        if(!empty($postParams)) {
            $this->requestHelper->setFormParams($postParams);
        }
        if(!empty($user)) {
            $this->requestHelper->setAuth($user,$password);
        }
        if(!empty($requesTimeout)) {
            $this->requestHelper->setTimeout($requesTimeout);
        }
        if(!empty($SSLVerify)) {
            $this->requestHelper->setVerify($SSLVerify);
        }
        if(!empty($accept)) {
            $this->requestHelper['accept'] = $accept;
        }
        if(!empty($customHeaders)) {
            $this->requestHelper->setHeaders($customHeaders);
        }
        if(!empty($protocolVersion)) {
            $this->requestHelper->setVerify($protocolVersion);
        }
        if(!empty($jsonParams)) {
            $this->requestHelper->setJson($jsonParams);
        }

        $this->response = $this->httpclient->sendRequest($method, $uri);

        return $this->response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @return Response
     */
    public function sendGet($uri, array $getParams=[], array $postParams=[])
    {
        $this->response =$this->sendSimpleRequest(MethodHttpHelper::GET ,$uri ,$getParams,$postParams,"" ,"",[],0, true, [],  "", "" );
        return $this->response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @param $user
     * @param $password
     * @return Response
     */
    public function sendGetWithAuth($uri, array $getParams=[], array $postParams=[], $user, $password)
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::GET ,$uri ,$getParams,$postParams,$user ,$password,[],0, true, [],  "", "" );
        return $this->response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @return Response
     */
    public function sendPost($uri, array $getParams=[], array $postParams=[])
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,$getParams,$postParams,"" ,"",[],0, true, [],  "", "" );
        return $this->response;
    }

    /**
     * @param $uri
     * @param array $getParams
     * @param array $postParams
     * @param $user
     * @param $password
     * @return Response
     */
    public function sendPostWithAuth($uri, array $getParams=[], array $postParams=[], $user, $password)
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,$getParams,$postParams,$user ,$password,[],0, true, [],  "", "" );
        return $this->response;
    }

    /**
     * @param $uri
     * @param array $jsonParams
     * @param $user
     * @param $password
     * @return Response
     */
    public function sendPostJsonWithAuth($uri, array $jsonParams=[], $user, $password)
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,[],[],$user ,$password,$jsonParams,0, true, [],  "", "" );
        return $this->response;
    }

}
