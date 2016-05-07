<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\HTTPClient;


use Padosoft\HTTPClient\Response;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use GuzzleHttp\Client;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;

/**
 * Class HTTPClient
 * Perform HTTP Request using Guzzle.
 * @package Padosoft\HTTPClient
 */
class HTTPClient
{
    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpclient;
    /**
     * @var \Padosoft\HTTPClient\Response
     */
    protected $response;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $log;
    /**
     * @var \Padosoft\HTTPClient\RequestHelper
     */
    protected $requestHelper;

    /**
     * HTTPClient constructor.
     * @param Client $objguzzle
     * @param RequestHelper $requestHelper
     * @param LoggerInterface|null $log
     */
    public function __construct(Client $objguzzle, RequestHelper $requestHelper, LoggerInterface $log=null)
    {
        $this->httpclient = $objguzzle;
        $this->requestHelper=$requestHelper;
        $this->response = new Response();
        $this->log = $log;
    }

    /**
     * Send a HTTP Request and return Response or thrown Exception.
     *
     * @param string $method HTTP verb
     * @param string  null $uri
     * @return \Padosoft\HTTPClient\Response
     * @throws Exception
     * @throws \Exception
     */
    public function sendRequest($method ,$uri = null)
    {

        $this->reset();

        try {
            //log request
            $this->setLog(LogLevel::INFO, 'Try to send Request: Method: '.$method.' uri: '.$uri, $this->requestHelper->options);

            //send request
            $this->response->psr7response = $this->httpclient->request($method, $uri, $this->requestHelper->options );

            //log headers
            $this->setLog(LogLevel::INFO, 'Request send with Headers: '.$this->getHeadersString($this->response->psr7response), $this->requestHelper->options);

            // To retrive body, do not use ->getContents()
            // it turns out that this gets the “remaining contents of the body as a string”
            // and since the middleware already accessed the body (for example in order to log it),
            // there isn’t any content remaining to retrieve.
            // The correct way to access the response content is to cast the body to a string.
            // See: https://michaelstivala.com/logging-guzzle-requests/
            $this->response->body = (string) $this->response->psr7response->getBody();

            //log status code
            $this->response->status_code =  $this->response->psr7response->getStatusCode();

            //log response
            $this->setLog(LogLevel::INFO,'Response ',['status code'=>$this->response->status_code,'body'=>$this->response->body]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ClientException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ClientException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);

        } catch (\GuzzleHttp\Exception\ServerException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ServerException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ServerException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\BadResponseException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\BadResponseException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);

        } catch (\GuzzleHttp\Exception\ConnectException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\ConnectException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\ConnectException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);

        } catch (\GuzzleHttp\Exception\TooManyRedirectsException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
            $this->setLog(LogLevel::ERROR,'\GuzzleHttp\Exception\TooManyRedirectsException',['message'=>$e->getMessage(),'path request'=>$e->getRequest()->getUri()->getPath(),'method'=>$e->getRequest()->getMethod(),'response status code'=>$responseStatusCode]);
            $this->setLog(LogLevel::DEBUG,'\GuzzleHttp\Exception\TooManyRedirectsException',['trace'=>$e->getTraceAsString()]);
            throw new \Exception($e->getMessage().' '.$responseStatusCode,$e->getCode(),$e);

        } catch (\GuzzleHttp\Exception\RequestException $e) {

            $responseStatusCode = $this->getResponseStatusCodeFromException($e);
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

    /**
     * @param string $logLevel
     * @param string $message
     * @param null Array $context
     * @throws Exception
     */
    private function setLog($logLevel, $message, $context = null)
    {
        if(!isset($this->log)){
            return;
        }
        if(! is_array($context)){
            throw new Exception('$context must be an array',0);
        }
        $this->log->log($logLevel, $message, $context);
    }

    /**
     * @param $property
     * @return mixed
     */
    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    /**
     * @param $property
     * @param $value
     */
    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }

    /**
     * Reset all variable to start new request.
     */
    public function reset()
    {
        $this->response = new Response();
    }

    /**
     * @param \Exception $e
     * @return string
     */
    public function getResponseStatusCodeFromException(\Exception $e)
    {
        if (null === $e->getResponse()) {
            return '';
        }
        if (! is_a($e->getResponse(), '\GuzzleHttp\Psr7\Response')){
            return '';
        }

        $responseStatusCode = $e->getResponse()->getStatusCode();
        return $responseStatusCode;
    }

    /**
     * Get psr7 headers array and return string representation.
     * @param \Psr\Http\Message\ResponseInterface
     * @return string
     */
    public function getHeadersString(\Psr\Http\Message\ResponseInterface $psr7response)
    {
        $strHeaders = '';
        if($psr7response === null)
        {
            return $strHeaders;
        }

        $arrHeaders = $psr7response->getHeaders();
        if (!is_array($arrHeaders) || count($arrHeaders) < 1) {
            return '';
        }

        foreach ($arrHeaders as $key => $value) {

            $strVal = '';
            if(is_array($value) && count($value)>0){
                $strVal = $value[0];

            }elseif (is_string($value)){
                $strVal = $value;
            }

            $strHeaders .= $key.':'.$strVal.PHP_EOL;
        }

        return $strHeaders;
    }
}
