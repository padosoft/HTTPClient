<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;


use Padosoft\HTTPClient\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;

class HTTPClient
{

    protected $httpclient;
    protected $response;
    protected $log;
    protected $requestHelper;
    

    public function __construct(Client $objguzzle, RequestHelper $requestHelper, LoggerInterface $log=null)
    {
        $this->httpclient = $objguzzle;
        $this->requestHelper=$requestHelper;
        $this->response = new Response();
        $this->log = $log;
    }


    public function sendRequest($method ,$uri = null )
    {
        $this->response = new Response();
        try {
            $responseStatusCode='';
            $this->setLog(LogLevel::INFO,'request '.$method.' at '.$uri,$this->requestHelper->options);
            $this->response->psr7response = $this->httpclient->request($method ,$uri, $this->requestHelper->options );
            $this->response->body = $this->response->psr7response->getBody()->getContents();
            $this->response->status_code =  $this->response->psr7response->getStatusCode();
            $this->setLog(LogLevel::INFO,'response ',['status code'=>$this->response->status_code,'body'=>$this->response->body]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ClientException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ClientException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\ServerException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ServerException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ServerException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\BadResponseException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\BadResponseException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\BadResponseException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ConnectException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ConnectException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\TooManyRedirectsException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\TooManyRedirectsException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\TooManyRedirectsException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if(null !== $e->getResponse())  {
                $responseStatusCode=$e->getResponse()->getStatusCode();
            }
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\RequestException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\RequestException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\TransferException $e) {
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\TransferException',['message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\TransferException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage(),$e->getCode(),$e);
        } catch (\RuntimeException $e) {
            $this->setLog(LogLevel::ERROR,'\RuntimeException',['message'=>$e->getMessage(),'code'=>$e->getCode()]);
            $this->setLog(LogLevel::DEBUG,'\RuntimeException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage(),$e->getCode(),$e);
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

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }
}