<?php

use Clx\Xms as X;
use Clx\Xms\Api as XA;

class DeserializeTest extends PHPUnit\Framework\TestCase
{

    public function testReadInvalidJson()
    {
        $json = "{this is invalid JSON}";

        try {
            X\Deserialize::batchResponse($json);
            $this->assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testReadBatchResponseText()
    {
        $json = <<<'EOD'
{
    "body": "${foo}${bar}",
    "canceled": true,
    "parameters": {
        "foo": {
            "123456789": "Joe",
            "987654321": "Mary",
            "default": "you"
        },
        "bar": {}
    },
    "created_at": "2016-12-01T11:03:13.192Z",
    "delivery_report": "none",
    "send_at": "2016-12-02T11:03:13.192Z",
    "expire_at": "2016-12-05T11:03:13.192Z",
    "from": "12345",
    "id": "3SD49KIOW8lL1Z5E",
    "modified_at": "2016-12-01T11:03:13Z",
    "to": [
        "987654321",
        "555555555"
    ],
    "callback_url": "https://example.com/callbacker",
    "type": "mt_text"
}
EOD;

        $result = X\Deserialize::batchResponse($json);

        $this->assertInstanceOf(XA\MtBatchTextSmsResult::class, $result);
        $this->assertEquals('${foo}${bar}', $result->body);
        $this->assertTrue($result->canceled);
        $this->assertEquals(
            new DateTime('2016-12-01T11:03:13.192Z'), $result->createdAt
        );
        $this->assertEquals('none', $result->deliveryReport);
        $this->assertEquals(
            new DateTime('2016-12-02T11:03:13.192Z'), $result->sendAt
        );
        $this->assertEquals(
            new DateTime('2016-12-05T11:03:13.192Z'), $result->expireAt
        );
        $this->assertEquals('12345', $result->sender);
        $this->assertEquals('3SD49KIOW8lL1Z5E', $result->batchId);
        $this->assertEquals(
            new DateTime('2016-12-01T11:03:13Z'), $result->modifiedAt
        );
        $this->assertEquals(
            'https://example.com/callbacker', $result->callbackUrl
        );
        $this->assertEquals(['987654321', '555555555'], $result->recipients);
        $this->assertEquals(
            [
                'foo' => [
                    'default' => 'you',
                    '987654321' => 'Mary',
                    '123456789' => 'Joe'
                ],
                'bar' => []
            ],
            $result->parameters
        );

        // The type attribute should not be deserialized.
        $this->assertObjectNotHasAttribute('type', $result);
    }

    public function testReadBatchResponseUnknown()
    {
        $json = <<<'EOD'
{
    "some_field": "some_value",
    "type": "mt_what"
}
EOD;

        try {
            $result = X\Deserialize::batchResponse($json);
            $this->assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testReadBatchesPage()
    {
        $json = <<<'EOD'
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

        $result = X\Deserialize::batchesPage($json);

        $this->assertEquals(3, $result->size);
        $this->assertEquals(0, $result->page);
        $this->assertEquals(7, $result->totalSize);
        $this->assertCount(3, $result->content);

        $this->assertInstanceOf(
            XA\MtBatchBinarySmsResult::class, $result->content[0]
        );
        $this->assertInstanceOf(
            XA\MtBatchTextSmsResult::class, $result->content[1]
        );
        $this->assertInstanceOf(
            XA\MtBatchTextSmsResult::class, $result->content[2]
        );

        $this->assertEquals('5Z8QsIRsk86f-jHB', $result->content[0]->batchId);
        $this->assertEquals('4nQCc1T6Dg-R-zHX', $result->content[1]->batchId);
        $this->assertEquals('4G4OmwztSJbVL2bl', $result->content[2]->batchId);
    }

    public function testReadDeliveryReportSummary()
    {
        $json = <<<'EOD'
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

        $result = X\Deserialize::batchDeliveryReport($json);

        $this->assertEquals('3SD49KIOW8lL1Z5E', $result->batchId);
        $this->assertEquals(2, $result->totalMessageCount);
        $this->assertCount(2, $result->statuses);

        $this->assertEquals(0, $result->statuses[0]->code);
        $this->assertEquals(11, $result->statuses[1]->code);

        $this->assertEquals('Delivered', $result->statuses[0]->status);
        $this->assertEquals('Failed', $result->statuses[1]->status);

        $this->assertEquals(2, $result->statuses[0]->count);
        $this->assertEquals(1, $result->statuses[1]->count);

        $this->assertNull($result->statuses[0]->recipients);
        $this->assertNull($result->statuses[1]->recipients);

        // The type field should not be picked up.
        $this->assertObjectNotHasAttribute('type', $result);
    }

    public function testReadDeliveryReportFull()
    {
        $json = <<<'EOD'
{
  "type" : "delivery_report_sms",
  "batch_id" : "4G4OmwztSJbVL2bl",
  "total_message_count" : 2,
  "statuses" : [ {
    "code" : 0,
    "status" : "Delivered",
    "count" : 1,
    "recipients" : [ "555555555" ]
  }, {
    "code" : 11,
    "status" : "Failed",
    "count" : 1,
    "recipients" : [ "987654321" ]
  } ]
}
EOD;

        $result = X\Deserialize::batchDeliveryReport($json);

        $this->assertEquals('4G4OmwztSJbVL2bl', $result->batchId);
        $this->assertEquals(2, $result->totalMessageCount);
        $this->assertCount(2, $result->statuses);

        $this->assertEquals(0, $result->statuses[0]->code);
        $this->assertEquals(11, $result->statuses[1]->code);

        $this->assertEquals('Delivered', $result->statuses[0]->status);
        $this->assertEquals('Failed', $result->statuses[1]->status);

        $this->assertEquals(1, $result->statuses[0]->count);
        $this->assertEquals(1, $result->statuses[1]->count);

        $this->assertEquals(['555555555'], $result->statuses[0]->recipients);
        $this->assertEquals(['987654321'], $result->statuses[1]->recipients);
    }

    public function testReadDeliveryReportUnknownType()
    {
        $json = '{ "hello" : "value" }';

        try {
            X\Deserialize::batchDeliveryReport($json);
            assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testReadRecipientDeliveryReport()
    {
        $json = <<<'EOD'
{"recipient":"123456789","code":11,"status":"Failed","at":"2016-12-05T16:24:23.318Z","type":"recipient_delivery_report_sms","batch_id":"3-mbA7z9wDKY76ag","operator_status_at":"2016-12-05T16:24:00.000Z","status_message":"mystatusmessage","operator":"31101"}
EOD;

        $result = X\Deserialize::batchRecipientDeliveryReport($json);

        $expected = new XA\BatchRecipientDeliveryReport();
        $expected->batchId = '3-mbA7z9wDKY76ag';
        $expected->operatorStatusAt = new \DateTime('2016-12-05T16:24:00.000Z');
        $expected->statusAt = new \DateTime('2016-12-05T16:24:23.318Z');
        $expected->status = XA\DeliveryStatus::FAILED;
        $expected->code = 11;
        $expected->recipient = '123456789';
        $expected->statusMessage = 'mystatusmessage';
        $expected->operator = '31101';

        $this->assertEquals($expected, $result);
    }

    public function testReadRecipientDeliveryReportUnknownType()
    {
        $json = '{ "hello" : "value" }';

        try {
            X\Deserialize::batchRecipientDeliveryReport($json);
            assertTrue(false, "expected exception");
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testReadGroupResult()
    {
        $json = <<<'EOD'
{
    "auto_update": {
        "to": "12345",
        "add": {
            "first_word": "hello",
            "second_word": "world"
        },
        "remove": {
            "first_word": "goodbye",
            "second_word": "world"
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

        $result = X\Deserialize::groupResponse($json);

        $this->assertEquals('12345', $result->autoUpdate->recipient);
        $this->assertEquals('hello', $result->autoUpdate->addFirstWord);
        $this->assertEquals('world', $result->autoUpdate->addSecondWord);
        $this->assertEquals('goodbye', $result->autoUpdate->removeFirstWord);
        $this->assertEquals('world', $result->autoUpdate->removeSecondWord);
        $this->assertCount(0, $result->childGroups);
        $this->assertEquals(
            new \DateTime('2016-12-08T12:38:19.962Z'),
            $result->createdAt
        );
        $this->assertEquals('4cldmgEdAcBfcHW3', $result->groupId);
        $this->assertEquals(
            new \DateTime('2016-12-10T12:38:19.162Z'),
            $result->modifiedAt
        );
        $this->assertEquals('rah-test', $result->name);
        $this->assertEquals(1, $result->size);
    }

    public function testReadGroupsPage()
    {
        $json = <<<'EOD'
{
  "count": 8,
  "page": 2,
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

        $result = X\Deserialize::groupsPage($json);

        $this->assertEquals(1, $result->size);
        $this->assertEquals(2, $result->page);
        $this->assertEquals(8, $result->totalSize);
        $this->assertCount(1, $result->content);
        $this->assertInstanceOf(XA\GroupResult::class, $result->content[0]);
        $this->assertEquals('4cldmgEdAcBfcHW3', $result->content[0]->groupId);
    }

    public function testReadTags()
    {
        $json = '{ "tags": ["tag1", "таг2"] }';

        $result = X\Deserialize::tags($json);

        $this->assertEquals(["tag1", "таг2"], $result);
    }

    public function testReadError()
    {
        $json = <<<'EOD'
{
    "code": "yes_this_is_code",
    "text": "This is a text"
}
EOD;

        $result = X\Deserialize::error($json);

        $this->assertEquals('yes_this_is_code', $result->code);
        $this->assertEquals('This is a text', $result->text);
    }

    public function testDryRunWithPerRecipients()
    {
        $json = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2,"per_recipient":[{"recipient":"987654321","body":"Hello","number_of_parts":1,"encoding":"text"},{"recipient":"555555555","body":"Hello","number_of_parts":1,"encoding":"text"}]}
EOD;

        $result = X\Deserialize::batchDryRun($json);

        $this->assertEquals(2, $result->numberOfRecipients);
        $this->assertEquals(2, $result->numberOfMessages);
        $this->assertEquals('Hello', $result->perRecipient[0]->body);
        $this->assertEquals(
            XA\DryRunPerRecipient::ENCODING_TEXT,
            $result->perRecipient[0]->encoding
        );
        $this->assertEquals('555555555', $result->perRecipient[1]->recipient);
        $this->assertEquals(1, $result->perRecipient[1]->numberOfParts);
    }

    public function testDryRunWithoutPerRecipients()
    {
        $json = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2}
EOD;

        $result = X\Deserialize::batchDryRun($json);

        $this->assertEquals(2, $result->numberOfRecipients);
        $this->assertEquals(2, $result->numberOfMessages);
    }

    public function testMoBinarySms()
    {
        $json = <<<'EOD'
{
  "type": "mo_binary",
  "to": "54321",
  "from": "123456789",
  "id": "b88b4cee-168f-4721-bbf9-cd748dd93b60",
  "sent_at": "2016-12-03T16:24:23.318Z",
  "received_at": "2016-12-05T16:24:23.318Z",
  "body": "AwE=",
  "udh": "00010203"
}
EOD;

        $result = X\Deserialize::moSms($json);

        $this->assertInstanceOf(XA\MoBinarySms::class, $result);
        $this->assertEquals('54321', $result->recipient);
        $this->assertEquals('123456789', $result->sender);
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->messageId
        );
        $this->assertEquals("\x03\x01", $result->body);
        $this->assertEquals("\x00\x01\x02\x03", $result->udh);
        $this->assertEquals(
            new \DateTime('2016-12-03T16:24:23.318Z'),
            $result->sentAt
        );
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->receivedAt
        );
    }

    public function testMoTextSms()
    {
        $json = <<<'EOD'
{
  "type": "mo_text",
  "to": "12345",
  "from": "987654321",
  "id": "b88b4cee-168f-4721-bbf9-cd748dd93b60",
  "sent_at": "2016-12-03T16:24:23.318Z",
  "received_at": "2016-12-05T16:24:23.318Z",
  "body": "Hello, world!",
  "keyword": "kivord",
  "operator": "31110"
}
EOD;

        $result = X\Deserialize::moSms($json);

        $this->assertInstanceOf(XA\MoTextSms::class, $result);
        $this->assertEquals('12345', $result->recipient);
        $this->assertEquals('987654321', $result->sender);
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->messageId
        );
        $this->assertEquals('Hello, world!', $result->body);
        $this->assertEquals("kivord", $result->keyword);
        $this->assertEquals(
            new \DateTime('2016-12-03T16:24:23.318Z'),
            $result->sentAt
        );
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->receivedAt
        );
    }

    public function testMoTextSmsMinimal()
    {
        $json = <<<'EOD'
{
  "type": "mo_text",
  "to": "12345",
  "from": "987654321",
  "id": "b88b4cee-168f-4721-bbf9-cd748dd93b60",
  "received_at": "2016-12-05T16:24:23.318Z",
  "body": "Hello, world!"
}
EOD;

        $result = X\Deserialize::moSms($json);

        $this->assertInstanceOf(XA\MoTextSms::class, $result);
        $this->assertEquals('12345', $result->recipient);
        $this->assertEquals('987654321', $result->sender);
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->messageId
        );
        $this->assertEquals('Hello, world!', $result->body);
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->receivedAt
        );
    }

    public function testMoTextSmsInvalidDateTime()
    {
        $json = <<<'EOD'
{
  "type": "mo_text",
  "to": "12345",
  "from": "987654321",
  "id": "b88b4cee-168f-4721-bbf9-cd748dd93b60",
  "received_at": "2016-12-05T16:24:23318Z",
  "body": "Hello, world!"
}
EOD;

        try {
            X\Deserialize::moSms($json);
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testMoUnknownSms()
    {
        $json = '{"type": "whatever"}';

        try {
            X\Deserialize::moSms($json);
        } catch (X\UnexpectedResponseException $ex) {
            $this->assertEquals($json, $ex->getHttpBody());
        }
    }

    public function testReadInboundsPage()
    {
        $json = <<<'EOD'
{
  "count": 9,
  "page": 3,
  "inbounds": [
    {
      "type": "mo_text",
      "to": "12345",
      "from": "987654321",
      "id": "b88b4cee",
      "received_at": "2016-12-05T16:24:23.318Z",
      "body": "Hello, world!"
    },
    {
      "type": "mo_binary",
      "to": "54321",
      "from": "123456789",
      "id": "cd748dd93b60",
      "sent_at": "2016-12-03T16:24:23.318Z",
      "received_at": "2016-12-05T16:24:23.318Z",
      "body": "AwE=",
      "udh": "00010203"
    }
  ],
  "page_size": 2
}
EOD;

        $result = X\Deserialize::inboundsPage($json);

        $this->assertEquals(2, $result->size);
        $this->assertEquals(3, $result->page);
        $this->assertEquals(9, $result->totalSize);
        $this->assertCount(2, $result->content);
        $this->assertInstanceOf(XA\MoTextSms::class, $result->content[0]);
        $this->assertEquals('b88b4cee', $result->content[0]->messageId);
        $this->assertInstanceOf(XA\MoBinarySms::class, $result->content[1]);
        $this->assertEquals('cd748dd93b60', $result->content[1]->messageId);
    }

}

?>