<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;


class HTTPClient
{

    protected $httpclient;
    protected $response;
    protected $options;
    protected $log;
    

    public function __construct(Client $objguzzle, RequestHelper $requestOptions, LoggerInterface $log=null)
    {
        $this->httpclient = $objguzzle;
        $this->options = $requestOptions->options;
        $this->response = new Response();
        $this->log = $log;
    }


    public function sendRequest($method ,$uri = null )
    {
        $response = null;
        try {

            $this->setLog(LogLevel::INFO,'request '.$method.' at '.$uri,$this->options);
            $this->response->psr7response = $this->httpclient->request($method ,$uri , $this->options );
            $this->response->body = $this->response->psr7response->getBody()->getContents();
            $this->response->status_code =  $this->response->psr7response->getStatusCode();
            $this->setLog(LogLevel::INFO,'response ',['status code'=>$this->response->status_code,'body'=>$this->response->body]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ClientException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ClientException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ServerException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ServerException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\BadResponseException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\BadResponseException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ConnectException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ConnectException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\TooManyRedirectsException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\TooManyRedirectsException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\TooManyRedirectsException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\RequestException',['message'=>$e->getMessage(),'response'=>$e->getResponse(),'request'=>$e->getRequest(),'response status code'=>$e->getResponse()->getStatusCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\RequestException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\TransferException',['message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\TransferException',['trace'=>$e->getTraceAsString()]);
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\RuntimeException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\RuntimeException',['message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\RuntimeException',['trace'=>$e->getTaceAsString()]);
            throw new Exception($e->getMessage(),$e->getCode(),$e);
        }

        return $this->response;
    }
    
    private function setLog($logLevel, $message, $context = null)
    {
        if(!isset($this->log)){
            return;
        }
        if(! is_array($context)){
            throw new Exception('$context must be an array',0);
        }
        $this->log->log($logLevel, $message, $context = array());
    }
}