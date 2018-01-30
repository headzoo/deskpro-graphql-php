<?php

use Deskpro\API\GraphQL\MutationBuilderInterface;
use Deskpro\API\GraphQL\QueryBuilderInterface;
use PHPUnit\Framework\TestCase;
use Deskpro\API\GraphQL\Client;
use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request;

/**
 * Class ClientTest
 */
class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    public $fixture;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|Guzzle
     */
    public $httpClient;

    /**
     * Called before each test
     */
    public function setUp()
    {
        $this->httpClient = $this->getMockBuilder(Guzzle::class)->getMock();
        $this->fixture = new Client('http://deskpro-dev.com', $this->httpClient);
    }
    
    public function testGetSetHelpdeskUrl()
    {
        $this->assertSame($this->fixture, $this->fixture->setHelpdeskUrl('http://deskpro.company.com'));
        $this->assertEquals('http://deskpro.company.com', $this->fixture->getHelpdeskUrl());
    }
    
    public function testGetSetHTTPClient()
    {
        $guzzle = new Guzzle();
        $this->assertSame($this->fixture, $this->fixture->setHTTPClient($guzzle));
        $this->assertSame($guzzle, $this->fixture->getHTTPClient());
    }
    
    public function testGetSetDefaultHeaders()
    {
        $defaultHeaders = [
            'X-Testing' => 'Foo'
        ];
        $this->assertSame($this->fixture, $this->fixture->setDefaultHeaders($defaultHeaders));
        $this->assertEquals($defaultHeaders, $this->fixture->getDefaultHeaders());
    }
    
    public function testSetAuthToken()
    {
        $this->assertSame($this->fixture, $this->fixture->setAuthToken(1, 'dev'));
    }

    public function testSetAuthKey()
    {
        $this->assertSame($this->fixture, $this->fixture->setAuthKey(1, 'dev'));
    }
    
    public function testCreateQuery()
    {
        $actual = $this->fixture->createQuery('GetNews');
        $this->assertInstanceOf(QueryBuilderInterface::class, $actual);
    }

    public function testCreateMutation()
    {
        $actual = $this->fixture->createMutation('GetNews');
        $this->assertInstanceOf(MutationBuilderInterface::class, $actual);
    }
    
    public function testExecute()
    {
        $data = [
            'content_get_news' => [
                'title'   => 'Testing title',
                'content' => 'Testing content'
            ]
        ];
        $body = json_encode([
            'data' => $data
        ]);
        
        $resp = new Response(200, [], $body);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($resp));
        
        $actual = $this->fixture->execute('', [
            'id' => 101
        ]);
        
        $this->assertEquals($data, $actual);
    }
    
    public function testExecuteVariablesWithDollarSign()
    {
        $data = [
            'content_get_news' => [
                'title'   => 'Testing title',
                'content' => 'Testing content'
            ]
        ];
        $body = json_encode([
            'data' => $data
        ]);

        $resp = new Response(200, [], $body);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($resp));

        $actual = $this->fixture->execute('', [
            '$id' => 101
        ]);

        $this->assertEquals($data, $actual);
    }
    
    public function testExecuteSendsDefaultHeaders()
    {
        $defaultHeaders = [
            'X-Testing' => 'Testing'
        ];
        $this->fixture->setDefaultHeaders($defaultHeaders);
        
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(function(Request $req) {
                $headers = $req->getHeaders();
                $this->assertEquals($headers['X-Testing'], ['Testing']);
                
                return new Response(200, [], json_encode([
                    'data' => []
                ]));
            }));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }

    public function testExecuteSendsAuthKey()
    {
        $this->fixture->setAuthKey(1, 'dev');

        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(function(Request $req) {
                $headers = $req->getHeaders();
                $expected = sprintf('%s 1:dev', Client::AUTH_KEY_KEY);
                $this->assertEquals($headers[Client::AUTH_HEADER], [$expected]);

                return new Response(200, [], json_encode([
                    'data' => []
                ]));
            }));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }

    public function testExecuteSendsAuthToken()
    {
        $this->fixture->setAuthToken(1, 'dev');

        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnCallback(function(Request $req) {
                $headers = $req->getHeaders();
                $expected = sprintf('%s 1:dev', Client::AUTH_TOKEN_KEY);
                $this->assertEquals($headers[Client::AUTH_HEADER], [$expected]);

                return new Response(200, [], json_encode([
                    'data' => []
                ]));
            }));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\InvalidResponseException
     */
    public function testExecuteThrowsInvalidJson()
    {
        $resp = new Response(200, []);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($resp));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\QueryErrorException
     */
    public function testExecuteThrowsQueryError()
    {
        $body = json_encode([
            'errors' => [
                ['message' => 'error']
            ]
        ]);
        
        $resp = new Response(200, [], $body);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($resp));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }

    /**
     * @expectedException \Deskpro\API\GraphQL\Exception\InvalidResponseException
     */
    public function testExecuteThrowsInvalidResponse()
    {
        $body = json_encode([
            'invalid' => []
        ]);

        $resp = new Response(200, [], $body);
        $this->httpClient
            ->expects($this->once())
            ->method('send')
            ->will($this->returnValue($resp));

        $this->fixture->execute('', [
            'id' => 101
        ]);
    }
}