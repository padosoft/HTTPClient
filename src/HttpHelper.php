<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Psr\Log\LoggerInterface;
use Padosoft\HTTPClient\RequestHelper;
use Padosoft\HTTPClient\MethodHttpHelper;
use GuzzleHttp\Client;

class HttpHelper
{

    protected $response;
    protected $httpclient;
    protected $requestHelper;


    public function __construct(\Padosoft\HTTPClient\HTTPClient $httpclient)
    {
        $this->httpclient = $httpclient;
        $this->requestHelper = $httpclient->requestHelper;
        $this->response = new Response();


    }

    public  function sendSimpleRequest($method ,$uri ,array $getParams=[],array $postParams=[],$user="" ,$password="",array $jsonParams=[],$requesTimeout = 0, $SSLVerify=true, $customHeaders=[],  $accept="", $protocolVersion=""  )
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

    public function sendGet($uri, array $getParams=[],array $postParams=[])
    {
        $this->response =$this->sendSimpleRequest(MethodHttpHelper::GET ,$uri ,$getParams,$postParams,"" ,"",[],0, true, [],  "", "" );

        return $this->response;
    }

    public function sendGetWithAuth($uri , array $getParams=[],array $postParams=[], $user, $password)
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::GET ,$uri ,$getParams,$postParams,$user ,$password,[],0, true, [],  "", "" );
        return $this->response;
    }

    public function sendPost($uri , array $getParams=[],array $postParams=[])
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,$getParams,$postParams,"" ,"",[],0, true, [],  "", "" );
        return $this->response;
    }

    public function sendPostWithAuth($uri , array $getParams=[],array $postParams=[], $user, $password)
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,$getParams,$postParams,$user ,$password,[],0, true, [],  "", "" );
        return $this->response;
    }

    public function sendPostJsonWithAuth($uri, $user, $password, array $jsonParams=[])
    {
        $this->response = $this->sendSimpleRequest(MethodHttpHelper::POST ,$uri ,[],[],$user ,$password,$jsonParams,0, true, [],  "", "" );

        return $this->response;
    }

}