<?php

use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use Symfony\Component\HttpFoundation\Response;

use Clx\Xms as X;

class ClientTest extends PHPUnit\Framework\TestCase
{

    use HttpMockTrait;


    public static function setUpBeforeClass()
    {
        static::setUpHttpMockBeforeClass(26592, 'localhost');
    }

    public static function tearDownAfterClass()
    {
        static::tearDownHttpMockAfterClass();
    }

    public function setUp()
    {
        $this->setUpHttpMock();
    }

    public function tearDown()
    {
        $this->tearDownHttpMock();
    }

    public function testHandles400BadRequest()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26592/xms");

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_BAD_REQUEST)
            ->body('{"code":"yes_this_is_code","text":"the text"}')
            ->end();
        $this->http->setUp();

        try {
            $client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\XmsErrorException $ex) {
            $this->assertEquals('yes_this_is_code', $ex->getErrorCode());
            $this->assertEquals('the text', $ex->getMessage());
        }
    }

    public function testHandles403Forbidden()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26592/xms");

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_FORBIDDEN)
            ->body('{"code":"yes_this_is_code","text":"the text"}')
            ->end();
        $this->http->setUp();

        try {
            $client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\XmsErrorException $ex) {
            $this->assertEquals('yes_this_is_code', $ex->getErrorCode());
            $this->assertEquals('the text', $ex->getMessage());
        }
    }

    public function testHandles404NotFound()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26592/xms");

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_NOT_FOUND)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\NotFoundException $ex) {
            $this->assertEquals(
                'http://localhost:26592/xms/v1/batches/batchid',
                $ex->getUrl()
            );
        }
    }

    public function testHandles401Unauthorized()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26592/xms");

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_UNAUTHORIZED)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\UnauthorizedException $ex) {
            $this->assertEquals('foo', $ex->getServicePlanId());
            $this->assertEquals('bar', $ex->getToken());
        }
    }

    public function testHandles500InternalServerError()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26592/xms");

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals(500, $ex->getHttpStatus());
            $this->assertEquals('{}', $ex->getHttpBody());
        }
    }

}

?>