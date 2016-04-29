<?php
/**
 * Created by PhpStorm.
 * User: Alessandro
 * Date: 29/04/2016
 * Time: 15:07
 */

namespace Padosoft\HTTPClient\Test;

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


class HttpHelperTest extends \Padosoft\Test\TestBase
{
    /** @test */
    public function testHttpHelper() {
        $objguzzle=new Client();
        $requestHelper = new RequestHelper();
        $requestHelper  ->setAuth('alevento','129895ale',TypeAuthentication::BASIC)
                        ->setJson(['name'=>'cix'])
                        ->setHttpErrorsTrue();


        $req = new HTTPClient($objguzzle,$requestHelper);
        $req->sendRequest(MethodHttpHelper::POST,'https://api.github.com/orgs/b2msrl/repos');



    }

    public function testExceptionWithMockHandler()
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            //new Response(200, ['X-Foo' => 'Bar']),
            //new Response(202, ['Content-Length' => 0]),
            new RequestException("Error Communicating with Server", new Request('GET', 'test')),
            new ClientException("Client error",new Request('GET', 'test'),new Response(202, []),null,[])
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $sensio = new SensiolabHelper($client,$this->mockCommand);
        //$this->assertEquals(null,$sensio->getSensiolabVulnerabilties(__DIR__.'/test_file/composer_ko/composer.lock'));
        //$this->assertEquals(null,$sensio->getSensiolabVulnerabilties(__DIR__.'/test_file/composer_ko/composer.lock'));

    }

}
