<?php

use Gamez\Psr\Log\TestLoggerTrait;
use InterNations\Component\HttpMock\PHPUnit\HttpMockTrait;
use Symfony\Component\HttpFoundation\Response;

use Clx\Xms as X;
use Clx\Xms\Api as XA;

/**
 * A little fake batch SMS create subclass.
 */
class DummyMtBatchCreate extends XA\MtBatchSmsCreate
{
}

class ClientTest extends PHPUnit\Framework\TestCase
{

    use HttpMockTrait;
    use TestLoggerTrait;

    private $_client;

    public static function setUpBeforeClass()
    {
        static::setUpHttpMockBeforeClass(26542, 'localhost');
    }

    public static function tearDownAfterClass()
    {
        static::tearDownHttpMockAfterClass();
    }

    public function setUp()
    {
        $this->setUpHttpMock();
        $this->_client = new X\Client('foo', 'bar', "http://localhost:26542/xms");
    }

    public function tearDown()
    {
        $this->tearDownHttpMock();
    }

    public function testDestruct()
    {
        /* Unset the client variable and see if any fatal error
         * occurs, e.g., a exception. */
        unset($this->_client);
    }

    public function testUnansweredRequest()
    {
        $client = new X\Client('foo', 'bar', "http://localhost:26541/xms");

        try {
            $tags = $client->fetchBatch('BATCHID');
            $this->assertTrue(false, 'expected exception');
        } catch (X\HttpCallException $ex) {
            // This is good.
        }
    }

    public function testInvalidUrl()
    {
        $client = new X\Client('foo', 'bar', "/this is an invalid URL");

        try {
            $tags = $client->fetchBatch('BATCHID');
            $this->assertTrue(false, 'expected exception');
        } catch (X\HttpCallException $ex) {
            // This is good.
        }
    }

    public function testHandles400BadRequest()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_BAD_REQUEST)
            ->body('{"code":"yes_this_is_code","text":"the text"}')
            ->end();
        $this->http->setUp();

        try {
            $this->_client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\ErrorResponseException $ex) {
            $this->assertEquals('yes_this_is_code', $ex->getErrorCode());
            $this->assertEquals('the text', $ex->getMessage());
        }
    }

    public function testHandles403Forbidden()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_FORBIDDEN)
            ->body('{"code":"yes_this_is_code","text":"the text"}')
            ->end();
        $this->http->setUp();

        try {
            $this->_client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\ErrorResponseException $ex) {
            $this->assertEquals('yes_this_is_code', $ex->getErrorCode());
            $this->assertEquals('the text', $ex->getMessage());
        }
    }

    public function testHandles404NotFound()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_NOT_FOUND)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $this->_client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\NotFoundException $ex) {
            $this->assertEquals(
                'http://localhost:26542/xms/v1/foo/batches/batchid',
                $ex->getUrl()
            );
        }
    }

    public function testHandles401Unauthorized()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_UNAUTHORIZED)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $this->_client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\UnauthorizedException $ex) {
            $this->assertEquals('foo', $ex->getServicePlanId());
            $this->assertEquals('bar', $ex->getToken());
        }
    }

    public function testHandles500InternalServerError()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/batchid')
            ->then()
            ->statusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->body('{}')
            ->end();
        $this->http->setUp();

        try {
            $this->_client->fetchBatch('batchid');
            $this->assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals('{}', $ex->getHttpBody());
        }
    }

    public function testLogsRequestAndResponse()
    {
        $this->http->mock
            ->when()
            ->methodIs('PUT')
            ->pathIs('/xms/v1/foo/groups/groupid/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->body('{"tags":[]}')
            ->end();
        $this->http->setUp();

        $logger = $this->getTestLogger();

        $this->_client->setLogger($logger);
        $this->_client->replaceGroupTags('groupid', []);

        $this->assertTrue(
            $logger->hasRecord('Request'),
            'Missing request log'
        );
    }

    public function testCreateTextBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_text",
  "body" : "hello",
  "id" : "5Z8QsIRsk86f-jHB",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchTextSmsCreate();
        $batch->body = 'hello';
        $batch->recipients = ['987654321', '123456789'];
        $batch->sender = '12345';

        $result = $this->_client->createTextBatch($batch);

        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
    "type": "mt_text",
    "body": "hello",
    "from": "12345",
    "to": ["987654321", "123456789"]
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testCreateBinaryBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "id" : "5Z8QsIRsk86f-jHB",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchBinarySmsCreate();
        $batch->body = "\x00\x01\x02\x03";
        $batch->udh = "\xff\xfe\xfd";
        $batch->recipients = ['987654321', '123456789'];
        $batch->sender = '12345';

        $result = $this->_client->createBinaryBatch($batch);

        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345"
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testDryRunBinaryBatch()
    {
        $responseBody = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches/dry_run')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchBinarySmsCreate();
        $batch->body = "\x00\x01\x02\x03";
        $batch->udh = "\xff\xfe\xfd";
        $batch->recipients = ['987654321'];
        $batch->sender = '12345';

        $result = $this->_client->createBatchDryRun($batch);

        $this->assertEquals(2, $result->numberOfRecipients);

        $expectedRequestBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "to" : [ "987654321" ],
  "from" : "12345"
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testDryRunTextBatch()
    {
        $responseBody = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2,"per_recipient":[{"recipient":"987654321","body":"Hello","number_of_parts":1,"encoding":"text"},{"recipient":"555555555","body":"Hello","number_of_parts":1,"encoding":"text"}]}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs(
                '/xms/v1/foo/batches/dry_run'
                . '?per_recipient=true'
                . '&number_of_recipients=20'
            )
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchTextSmsCreate();
        $batch->body = 'Hello';
        $batch->recipients = ['987654321', '555555555'];
        $batch->sender = '12345';

        $result = $this->_client->createBatchDryRun($batch, 20);

        $this->assertEquals(2, $result->numberOfRecipients);

        $expectedRequestBody = <<<'EOD'
{
  "type" : "mt_text",
  "body" : "Hello",
  "to" : [ "987654321", "555555555" ],
  "from" : "12345"
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testDryRunBatchWrongType()
    {
        $this->expectException(InvalidArgumentException::class);
        $result = $this->_client->createBatchDryRun(new DummyMtBatchCreate(), 20);
    }

    public function testReplaceTextBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_text",
  "body" : "hello",
  "id" : "5Z8QsIRsk86f-jHB",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('PUT')
            ->pathIs('/xms/v1/foo/batches/BatchID')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchTextSmsCreate();
        $batch->body = 'hello';
        $batch->recipients = ['987654321', '123456789'];
        $batch->sender = '12345';

        $result = $this->_client->replaceTextBatch('BatchID', $batch);

        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
    "type": "mt_text",
    "body": "hello",
    "from": "12345",
    "to": ["987654321", "123456789"]
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testReplaceBinaryBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "id" : "5Z8QsIRsk86f-jHB",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('PUT')
            ->pathIs('/xms/v1/foo/batches/5Z8QsIRsk86f-jHB')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchBinarySmsCreate();
        $batch->body = "\x00\x01\x02\x03";
        $batch->udh = "\xff\xfe\xfd";
        $batch->recipients = ['987654321', '123456789'];
        $batch->sender = '12345';

        $result = $this->_client->replaceBinaryBatch(
            '5Z8QsIRsk86f-jHB', $batch
        );

        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345"
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testUpdateTextBatch()
    {
        $responseBody = <<<'EOD'
{
    "body": "Hello, world!",
    "canceled": false,
    "created_at": "2016-12-09T12:54:28.247Z",
    "delivery_report": "none",
    "expire_at": "2016-12-12T12:54:28.247Z",
    "from": "12345",
    "id": "4nQCc1T6Dg-R-zHX",
    "modified_at": "2016-12-09T12:54:28.247Z",
    "tags": [
        "rah"
    ],
    "to": [
        "987654321"
    ],
    "type": "mt_text"
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches/4nQCc1T6Dg-R-zHX')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchTextSmsUpdate();
        $batch->resetSendAt();
        $batch->body = 'hello';

        $result = $this->_client->updateTextBatch('4nQCc1T6Dg-R-zHX', $batch);

        $this->assertEquals('4nQCc1T6Dg-R-zHX', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
    "type": "mt_text",
    "body": "hello",
    "send_at": null
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testUpdateBinaryBatch()
    {
        $responseBody = <<<'EOD'
{
    "udh" : "fffefd",
    "body" : "AAECAw==",
    "canceled": false,
    "created_at": "2016-12-09T12:54:28.247Z",
    "delivery_report": "none",
    "expire_at": "2016-12-12T12:54:28.247Z",
    "from": "12345",
    "id": "4nQCc1T6Dg-R-zHY",
    "modified_at": "2016-12-09T12:54:28.247Z",
    "tags": [
        "rah"
    ],
    "to": [
        "987654321"
    ],
    "type": "mt_binary"
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches/4nQCc1T6Dg-R-zHY')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $batch = new XA\MtBatchBinarySmsUpdate();
        $batch->resetCallbackUrl();
        $batch->body = 'hello';

        $result = $this->_client->updateBinaryBatch('4nQCc1T6Dg-R-zHY', $batch);

        $this->assertEquals('4nQCc1T6Dg-R-zHY', $result->batchId);

        $expectedRequestBody = <<<'EOD'
{
    "type": "mt_binary",
    "body": "aGVsbG8=",
    "callback_url": null
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testFetchTextBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_text",
  "body" : "Hello, world!",
  "id" : "!-@#$%^&*",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/%21-%40%23%24%25%5E%26%2A')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $result = $this->_client->fetchBatch('!-@#$%^&*');

        $this->assertInstanceOf(XA\MtBatchTextSmsResult::class, $result);
        $this->assertEquals('!-@#$%^&*', $result->batchId);
    }

    public function testFetchBinaryBatch()
    {
        $responseBody = <<<'EOD'
{
  "type" : "mt_binary",
  "udh" : "fffefd",
  "body" : "AAECAw==",
  "id" : "5Z8QsIRsk86f-jHB",
  "to" : [ "987654321", "123456789" ],
  "from" : "12345",
  "expire_at" : "2016-12-17T08:15:29.969Z",
  "created_at" : "2016-12-14T08:15:29.969Z",
  "modified_at" : "2016-12-14T08:15:29.969Z",
  "canceled" : false
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/5Z8QsIRsk86f-jHB')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $result = $this->_client->fetchBatch('5Z8QsIRsk86f-jHB');

        $this->assertInstanceOf(XA\MtBatchBinarySmsResult::class, $result);
        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->batchId);
    }

    public function testFetchBatches()
    {
        $responseBody1 = <<<'EOD'
{
    "batches": [
        {
            "body": "AAECAw==",
            "canceled": false,
            "created_at": "2016-12-14T08:15:29.969Z",
            "delivery_report": "none",
            "expire_at": "2016-12-17T08:15:29.969Z",
            "from": "12345",
            "id": "5Z8QsIRsk86f-jHB",
            "modified_at": "2016-12-14T08:15:29.969Z",
            "tags": [
                "rah"
            ],
            "to": [
                "987654321",
                "123456789"
            ],
            "type": "mt_binary",
            "udh": "fffefd"
        },
        {
            "body": "Hello, world!",
            "canceled": false,
            "created_at": "2016-12-09T12:54:28.247Z",
            "delivery_report": "none",
            "expire_at": "2016-12-12T12:54:28.247Z",
            "from": "12345",
            "id": "4nQCc1T6Dg-R-zHX",
            "modified_at": "2016-12-09T12:54:28.247Z",
            "tags": [
                "rah"
            ],
            "to": [
                "987654321"
            ],
            "type": "mt_text"
        },
        {
            "body": "Hello",
            "canceled": false,
            "created_at": "2016-12-06T11:14:37.438Z",
            "delivery_report": "none",
            "expire_at": "2016-12-09T11:14:37.438Z",
            "from": "12345",
            "id": "4G4OmwztSJbVL2bl",
            "modified_at": "2016-12-06T11:14:37.438Z",
            "tags": [
                "rah1",
                "rah2"
            ],
            "to": [
                "987654321",
                "555555555"
            ],
            "type": "mt_text"
        }
    ],
    "count": 7,
    "page": 0,
    "page_size": 3
}
EOD;

        $responseBody2 = <<<'EOD'
{
    "batches": [],
    "count": 7,
    "page": 1,
    "page_size": 0
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/batches?page=0&page_size=10'
                . '&from=12345%2C98765&tags=tag1%2Ctag2'
                . '&start_date=2016-12-01&end_date=2016-12-02'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody1)
            ->end();
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/batches?page=1&page_size=10'
                . '&from=12345%2C98765&tags=tag1%2Ctag2'
                . '&start_date=2016-12-01&end_date=2016-12-02'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody2)
            ->end();
        $this->http->setUp();

        $filter = new X\BatchFilter();
        $filter->pageSize = 10;
        $filter->senders = ['12345', '98765'];
        $filter->tags = ['tag1', 'tag2'];
        $filter->startDate = new \DateTime('2016-12-01');
        $filter->endDate = new \DateTime('2016-12-02');

        $pages = $this->_client->fetchBatches($filter);

        $page = $pages->get(0);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(3, $page->size);
        $this->assertEquals(7, $page->totalSize);
        $this->assertEquals('4G4OmwztSJbVL2bl', $page->content[2]->batchId);

        $page = $pages->get(1);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(0, $page->size);
        $this->assertEquals(7, $page->totalSize);
        $this->assertEquals([], $page->content);
    }

    public function testCancelBatch()
    {
        $this->http->mock
            ->when()
            ->methodIs('DELETE')
            ->pathIs('/xms/v1/foo/batches/BatchId')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->end();
        $this->http->setUp();

        $this->_client->cancelBatch('BatchId');
    }

    public function testFetchBatchTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/batches/BATCHID/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags":["tag1", "tag2"]}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->fetchBatchTags('BATCHID');

        $this->assertEquals(['tag1', 'tag2'], $tags);
    }

    public function testFetchDeliveryReport()
    {
        $responseBody = <<<'EOD'
{
    "batch_id": "3SD49KIOW8lL1Z5E",
    "statuses": [
        {
            "code": 0,
            "count": 2,
            "status": "Delivered"
        },
        {
            "code": 11,
            "count": 1,
            "status": "Failed"
        }
    ],
    "total_message_count": 2,
    "type": "delivery_report_sms"
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/batches/3SD49KIOW8lL1Z5E/delivery_report'
                . '?type=full'
                . '&status=Delivered%2CFailed'
                . '&code=0%2C11%2C400'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $result = $this->_client->fetchDeliveryReport(
            '3SD49KIOW8lL1Z5E',
            X\DeliveryReportType::FULL,
            ['Delivered', 'Failed'],
            [0, 11, 400]
        );

        $this->assertEquals('3SD49KIOW8lL1Z5E', $result->batchId);
    }


    public function testFetchRecipientDeliveryReport()
    {
        $responseBody = <<<'EOD'
{"recipient":"123456789","code":11,"status":"Failed","at":"2016-12-05T16:24:23.318Z","type":"recipient_delivery_report_sms","batch_id":"3-mbA7z9wDKY76ag","operator_status_at":"2016-12-05T16:24:00.000Z"}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/batches/3-mbA7z9wDKY76ag'
                . '/delivery_report/123456789'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $result = $this->_client->fetchRecipientDeliveryReport(
            '3-mbA7z9wDKY76ag', '123456789'
        );

        $this->assertEquals('3-mbA7z9wDKY76ag', $result->batchId);
    }

    public function testCreateGroup()
    {
        $responseBody = <<<'EOD'
{
    "auto_update": {
        "to": "12345",
        "add": {
            "first_word": "hello"
        },
        "remove": {
            "first_word": "goodbye"
        }
    },
    "child_groups": [],
    "created_at": "2016-12-08T12:38:19.962Z",
    "id": "4cldmgEdAcBfcHW3",
    "modified_at": "2016-12-08T12:38:19.962Z",
    "name": "rah-test",
    "size": 1
}
EOD;

        $group = new XA\GroupCreate();
        $group->members = ['123456789', '987654321'];
        $group->name = 'my group';

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/groups')
            ->then()
            ->statusCode(Response::HTTP_CREATED)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $result = $this->_client->createGroup($group);

        $this->assertEquals('4cldmgEdAcBfcHW3', $result->groupId);


        $expectedRequestBody = <<<'EOD'
{
    "name": "my group",
    "members": ["123456789", "987654321"]
}
EOD;

        $this->assertJsonStringEqualsJsonString(
            $expectedRequestBody,
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testUpdateGroup()
    {
        $responseBody = <<<'EOD'
{
    "child_groups": [],
    "created_at": "2016-12-08T12:38:19.962Z",
    "id": "4cldmgEdAcBfcHW3",
    "modified_at": "2016-12-10T12:38:19.162Z",
    "size": 1004
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/groups/4cldmgEdAcBfcHW3')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $group = new XA\GroupUpdate();

        $result = $this->_client->updateGroup('4cldmgEdAcBfcHW3', $group);

        $this->assertEquals('4cldmgEdAcBfcHW3', $result->groupId);
        $this->assertEquals(1004, $result->size);
    }

    public function testDeleteGroup()
    {
        $this->http->mock
            ->when()
            ->methodIs('DELETE')
            ->pathIs('/xms/v1/foo/groups/GroupId')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->end();
        $this->http->setUp();

        $this->_client->deleteGroup('GroupId');
    }

    public function testFetchGroup()
    {
        $responseBody = <<<'EOD'
{
    "auto_update": {
        "to": "12345",
        "add": {
        },
        "remove": {
        }
    },
    "child_groups": [],
    "created_at": "2016-12-08T12:38:19.962Z",
    "id": "4cldmgEdAcBfcHW3",
    "modified_at": "2016-12-10T12:38:19.162Z",
    "name": "rah-test",
    "size": 1
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/groups/4cldmgEdAcBfcHW3')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $group = $this->_client->fetchGroup('4cldmgEdAcBfcHW3');

        $this->assertEquals('4cldmgEdAcBfcHW3', $group->groupId);
    }

    public function testFetchGroups()
    {
        $responseBody1 = <<<'EOD'
{
  "count": 8,
  "page": 0,
  "groups": [
    {
      "id": "4cldmgEdAcBfcHW3",
      "name": "rah-test",
      "size": 1,
      "created_at": "2016-12-08T12:38:19.962Z",
      "modified_at": "2016-12-08T12:38:19.962Z",
      "child_groups": [],
      "auto_update": {
        "to": "12345"
      }
    }
  ],
  "page_size": 1
}
EOD;

        $responseBody2 = <<<'EOD'
{
    "groups": [],
    "count": 8,
    "page": 1,
    "page_size": 0
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/groups?page=0&page_size=10&tags=tag1%2Ctag2')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody1)
            ->end();
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/groups?page=1&page_size=10&tags=tag1%2Ctag2')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody2)
            ->end();
        $this->http->setUp();

        $filter = new X\GroupFilter();
        $filter->pageSize = 10;
        $filter->tags = ['tag1', 'tag2'];

        $pages = $this->_client->fetchGroups($filter);

        $page = $pages->get(0);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(1, $page->size);
        $this->assertEquals(8, $page->totalSize);
        $this->assertEquals('4cldmgEdAcBfcHW3', $page->content[0]->groupId);

        $page = $pages->get(1);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(0, $page->size);
        $this->assertEquals(8, $page->totalSize);
        $this->assertEquals([], $page->content);
    }

    public function testFetchGroupTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/groups/groupid/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags":["tag1", "tag2"]}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->fetchGroupTags('groupid');

        $this->assertEquals(['tag1', 'tag2'], $tags);
    }

    public function testReplaceBatchTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('PUT')
            ->pathIs('/xms/v1/foo/batches/batchid/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags" : ["tag"]}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->replaceBatchTags('batchid', ['tag']);

        $this->assertEquals(['tag'], $tags);
    }

    public function testReplaceGroupTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('PUT')
            ->pathIs('/xms/v1/foo/groups/GroupId/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags" : []}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->replaceGroupTags('GroupId', []);

        $this->assertEquals([], $tags);

        $this->assertJsonStringEqualsJsonString(
            '{"tags":[]}',
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testUpdateBatchTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/batches/batchid/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags" : ["tag"]}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->updateBatchTags('batchid', ['at'], ['rt']);

        $this->assertEquals(['tag'], $tags);

        $this->assertJsonStringEqualsJsonString(
            '{"add":["at"],"remove":["rt"]}',
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testUpdateGroupTags()
    {
        $this->http->mock
            ->when()
            ->methodIs('POST')
            ->pathIs('/xms/v1/foo/groups/GroupId/tags')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body('{"tags" : ["a", "b"]}')
            ->end();
        $this->http->setUp();

        $tags = $this->_client->updateGroupTags('GroupId', [], ['foo']);

        $this->assertEquals(['a', 'b'], $tags);

        $this->assertJsonStringEqualsJsonString(
            '{"add":[],"remove":["foo"]}',
            (string) $this->http->requests->latest()->getBody()
        );
    }

    public function testFetchInbound()
    {
        $responseBody = <<<'EOD'
{
  "type": "mo_text",
  "to": "12345",
  "from": "987654321",
  "id": "10101010101",
  "sent_at": "2016-12-03T16:24:23.318Z",
  "received_at": "2016-12-05T16:24:23.318Z",
  "body": "Hello, world!",
  "keyword": "kivord",
  "operator": "31110"
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs('/xms/v1/foo/inbounds/10101010101')
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody)
            ->end();
        $this->http->setUp();

        $mo = $this->_client->fetchInbound('10101010101');

        $this->assertEquals('987654321', $mo->sender);
    }

    public function testFetchInbounds()
    {
        $responseBody1 = <<<'EOD'
{
  "count": 4,
  "page": 0,
  "inbounds": [
    {
      "type": "mo_text",
      "to": "12345",
      "from": "987654321",
      "id": "10101010101",
      "received_at": "2016-12-05T16:24:23.318Z",
      "body": "Hello, world!",
      "keyword": "kivord",
      "operator": "31110"
    }, {
      "type": "mo_binary",
      "to": "54321",
      "from": "123456789",
      "id": "20202020202",
      "received_at": "2016-12-05T16:24:23.318Z",
      "body": "AwE=",
      "udh": "00010203"
    }, {
      "type": "mo_text",
      "to": "12345",
      "from": "987654321",
      "id": "30303030303",
      "sent_at": "2016-12-03T16:24:23.318Z",
      "received_at": "2016-12-05T16:24:23.318Z",
      "body": "Hello, world!",
      "keyword": "kivord",
      "operator": "31110"
    }
  ],
  "page_size": 3
}
EOD;

        $responseBody2 = <<<'EOD'
{
    "inbounds": [],
    "count": 4,
    "page": 1,
    "page_size": 0
}
EOD;

        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/inbounds'
                . '?page=0&page_size=12&to=23456%2C8654'
                . '&start_date=2016-12-11&end_date=2016-12-12'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody1)
            ->end();
        $this->http->mock
            ->when()
            ->methodIs('GET')
            ->pathIs(
                '/xms/v1/foo/inbounds'
                . '?page=1&page_size=12&to=23456%2C8654'
                . '&start_date=2016-12-11&end_date=2016-12-12'
            )
            ->then()
            ->statusCode(Response::HTTP_OK)
            ->header('content-type', 'application/json')
            ->body($responseBody2)
            ->end();
        $this->http->setUp();

        $filter = new X\InboundsFilter();
        $filter->pageSize = 12;
        $filter->recipients = ['23456', '8654'];
        $filter->startDate = new \DateTime('2016-12-11');
        $filter->endDate = new \DateTime('2016-12-12');

        $pages = $this->_client->fetchInbounds($filter);

        $page = $pages->get(0);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(0, $page->page);
        $this->assertEquals(3, $page->size);
        $this->assertEquals(4, $page->totalSize);
        $this->assertEquals('10101010101', $page->content[0]->messageId);
        $this->assertEquals('20202020202', $page->content[1]->messageId);
        $this->assertEquals('30303030303', $page->content[2]->messageId);

        $page = $pages->get(1);
        $this->assertInstanceOf(XA\Page::class, $page);
        $this->assertEquals(1, $page->page);
        $this->assertEquals(0, $page->size);
        $this->assertEquals(4, $page->totalSize);
        $this->assertEquals([], $page->content);
    }

}

?>