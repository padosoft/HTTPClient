<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 29/04/2016
 * Time: 15:07
 */

namespace Padosoft\HTTPClient\Test;

use Padosoft\HTTPClient\HTTPClient;
use Padosoft\HTTPClient\HttpHelper;
use Padosoft\HTTPClient\RequestHelper;
use Padosoft\HTTPClient\TypeAuthentication;
use Padosoft\HTTPClient\MethodHttpHelper;
use GuzzleHttp\Client;
use Padosoft\Test\TestBase;
use Padosoft\Test\traits\GuzzleMockTools;
use GuzzleHttp\Exception;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\TooManyRedirectsException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\TransferException;


/**
 * Class HttpHelperTest
 * @package Padosoft\HTTPClient\Test
 */
class HttpHelperTest extends \Padosoft\Test\TestBase
{

    use GuzzleMockTools;
    /** @test */

    public function testHelper() {

        $client = new Client();
        $requestHelper = new RequestHelper();

        $helper = new HttpHelper(new HTTPClient($client,$requestHelper));
        $helper->sendPostJsonWithAuth('https://api.github.com/orgs/b2msrl/repos',['name'=>'trest2'],'user' ,'pwd');
    }

    public function testHttpHelperResponse200() {


// The first request is intercepted with the first response.
        //echo $client->request('GET', '/')->getStatusCode();


        $requestHelper = new RequestHelper();
        /*$requestHelper  ->setAuth('padosoft','password',TypeAuthentication::BASIC)
                        ->setJson(['name'=>'repoprova']);*/

        $client = $this->createResponse(200);
        $req = new HTTPClient($client,$requestHelper);
        $response = $req->sendRequest(MethodHttpHelper::GET,'/');
        $this->assertEquals(200,$response->status_code );

    }

    public function testHttpHelperResponse400SetHttpErrorFalse() {

        $requestHelper = new RequestHelper();

        $client = $this->createResponse(400);
        $req = new HTTPClient($client,$requestHelper);
        $response = $req->sendRequest(MethodHttpHelper::POST,'test');
        $this->assertEquals(400,$response->status_code );


    }

    public function testHttpHelperResponse400SetHttpErrorTrue() {

        $requestHelper = new RequestHelper();
        $requestHelper->setHttpErrorsTrue();
        try {
            $client = $this->createResponse(400);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->assertRegExp('/Client error/',$ex->getMessage());
        }


    }





    public function testExceptionHttpHelperWithMockHandler()
    {
        // Create a mock and queue exceptions.



        //$client = $this->createClientWithAllExceptionMock(MethodHttpHelper::POST);

        $requestHelper = new RequestHelper();
        $requestHelper  ->setAuth('padosoft','password',TypeAuthentication::BASIC)
            ->setJson(['name'=>'repoprova'])
            ->setHttpErrorsTrue();


        //$req = new HTTPClient($client,$requestHelper);
        try {
            $client = $this->createClientExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\ClientException $ex) {
            $this->assertRegExp('/Client error/',$ex->getMessage());
        }
        try {
            $client = $this->createServerExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\ServerException $ex) {
            $this->assertRegExp('/Server error/',$ex->getMessage());
        }
        try {
            $client = $this->createBadResponseExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\BadResponseException $ex) {
            $this->assertRegExp('/Bad response error/',$ex->getMessage());
        }
        try {
            $client = $this->createConnectExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\ConnectException $ex) {
            $this->assertRegExp('/Connect error/',$ex->getMessage());
        }
        try {
            $client = $this->createTooManyRedirectsExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\TooManyRedirectsException $ex) {
            $this->assertRegExp('/Too many redirect error/',$ex->getMessage());
        }
        try {
            $client = $this->createRequestExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\RequestException $ex) {
            $this->assertRegExp('/Request error/',$ex->getMessage());
        }
        try {
            $client = $this->createTransferExceptionMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\GuzzleHttp\Exception\TransferException $ex) {
            $this->assertRegExp('/Transfer error/',$ex->getMessage());
        }
        try {
            $client = $this->createRuntimeExceptionnMock(MethodHttpHelper::POST);
            $req = new HTTPClient($client,$requestHelper);
            $req->sendRequest(MethodHttpHelper::POST,'test');
        } catch (\RuntimeException $ex) {
            $this->assertRegExp('/Runtime error/',$ex->getMessage());
        }



    }

}
