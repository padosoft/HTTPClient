<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use Psr\Log\LoggerInterface;
use Padosoft\HTTPClient\RequestHelper;

class HttpHelper
{

    protected $response;

    public function sendSimpleRequest($method ,$uri = null, $arrQueryGet, $formParamsPost,$user ,$password,$requesTimeout, $SSLVerify, $customHeaders="",  $accept="", $protocolVersion="" ,LoggerInterface $log=null )
    {

        $requestHelper = new RequestHelper();
        $requestHelper->setQuery($arrQueryGet);
        $requestHelper->setFormParams($formParamsPost);
        $requestHelper->setAuth([$user,$password]);
        $requestHelper->setTimeout($requesTimeout);
        $requestHelper->setTimeout($requesTimeout);
        $requestHelper->setVerify($SSLVerify);
        $customHeaders['accept'] = $accept;
        $requestHelper->setHeaders($customHeaders);
        $requestHelper->setVersion($protocolVersion);

        $this->response = new Response();
        $this->response = $this->httpclient->request($method, $uri, $requestHelper->options);

        return $this->response;

    }

}