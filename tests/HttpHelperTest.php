<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 29/04/2016
 * Time: 15:07
 */

namespace Padosoft\HTTPClient\Test;

use Padosoft\HTTPClient\GuzzleMockTools;
use Padosoft\HTTPClient\HTTPClient;
use Padosoft\HTTPClient\RequestHelper;
use Padosoft\HTTPClient\TypeAuthentication;
use Padosoft\HTTPClient\MethodHttpHelper;
use GuzzleHttp\Client;
use Padosoft\Test\TestBase;
use \GuzzleHttp\Handler\MockHandler;
use \GuzzleHttp\Psr7\Response;
use \GuzzleHttp\Psr7\Request;
use \GuzzleHttp\HandlerStack;
use \GuzzleHttp\Exception;
use \GuzzleHttp\Exception\ClientException;
use \GuzzleHttp\Exception\RequestException;
use \GuzzleHttp\Exception\BadResponseException;
use \GuzzleHttp\Exception\ServerException;
use \GuzzleHttp\Exception\TooManyRedirectsException;
use \GuzzleHttp\Exception\ConnectException;
use \GuzzleHttp\Exception\TransferException;



class HttpHelperTest extends \Padosoft\Test\TestBase
{
    
    use GuzzleMockTools;
    /** @test */
    /*public function testHttpHelper() {
        $objguzzle=new Client();
        $requestHelper = new RequestHelper();
        $requestHelper  ->setAuth('padosoft','xxxxxx',TypeAuthentication::BASIC)
                        ->setJson(['name'=>'cix'])
                        ->setHttpErrorsTrue();


        $req = new HTTPClient($objguzzle,$requestHelper);
        $req->sendRequest(MethodHttpHelper::POST,'https://api.github.com/orgs/padosoft/repos');



    }*/

    public function testExceptionHttpHelperWithMockHandler()
    {
        // Create a mock and queue exceptions.

        $mock = new MockHandler();
        $this->addAllException(MethodHttpHelper::POST, $mock);
        $handler = HandlerStack::create($mock);

        $client = new Client(['handler' => $handler]);
        
        $requestHelper = new RequestHelper();
        $requestHelper  ->setAuth('alevento','129895ale',TypeAuthentication::BASIC)
            ->setJson(['name'=>'cix'])
            ->setHttpErrorsTrue();


        $req = new HTTPClient($client,$requestHelper);
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Client error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Server error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Bad response error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Connect error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Too many redirect error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Request error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Transfer error/',$ex->getMessage());
        }
        try {
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\Exception $ex) {
            $this->assertRegExp('/Runtime error/',$ex->getMessage());
        }
        


    }

}
