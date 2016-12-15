<?php

use Clx\Xms as X;

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

        $this->assertInstanceOf(X\MtTextSmsBatchResponse::class, $result);
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
            // Expected
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
            X\MtBinarySmsBatchResponse::class, $result->content[0]
        );
        $this->assertInstanceOf(
            X\MtTextSmsBatchResponse::class, $result->content[1]
        );
        $this->assertInstanceOf(
            X\MtTextSmsBatchResponse::class, $result->content[2]
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


    public function testReadGroupResponse()
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
        $this->assertInstanceOf(X\GroupResponse::class, $result->content[0]);
        $this->assertEquals('4cldmgEdAcBfcHW3', $result->content[0]->groupId);
    }

    public function testReadTags()
    {
        $json = '["tag1", "таг2"]';

        $result = X\Deserialize::tags($json);

        $this->assertSame(["tag1", "таг2"], $result);
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

}

?>