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
        $this->assertEquals('${foo}${bar}', $result->getBody());
        $this->assertTrue($result->isCanceled());
        $this->assertEquals(
            new DateTime('2016-12-01T11:03:13.192Z'), $result->getCreatedAt()
        );
        $this->assertEquals('none', $result->getDeliveryReport());
        $this->assertEquals(
            new DateTime('2016-12-02T11:03:13.192Z'), $result->getSendAt()
        );
        $this->assertEquals(
            new DateTime('2016-12-05T11:03:13.192Z'), $result->getExpireAt()
        );
        $this->assertEquals('12345', $result->getSender());
        $this->assertEquals('3SD49KIOW8lL1Z5E', $result->getBatchId());
        $this->assertEquals(
            new DateTime('2016-12-01T11:03:13Z'), $result->getModifiedAt()
        );
        $this->assertEquals(
            'https://example.com/callbacker', $result->getCallbackUrl()
        );
        $this->assertEquals(
            ['987654321', '555555555'], $result->getRecipients()
        );
        $this->assertEquals(
            [
                'foo' => [
                    'default' => 'you',
                    '987654321' => 'Mary',
                    '123456789' => 'Joe'
                ],
                'bar' => []
            ],
            $result->getParameters()
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

        $this->assertEquals(3, $result->getSize());
        $this->assertEquals(0, $result->getPage());
        $this->assertEquals(7, $result->getTotalSize());
        $this->assertCount(3, $result->getContent());

        $this->assertInstanceOf(
            XA\MtBatchBinarySmsResult::class, $result->getContent()[0]
        );
        $this->assertInstanceOf(
            XA\MtBatchTextSmsResult::class, $result->getContent()[1]
        );
        $this->assertInstanceOf(
            XA\MtBatchTextSmsResult::class, $result->getContent()[2]
        );

        $this->assertEquals(
            '5Z8QsIRsk86f-jHB', $result->getContent()[0]->getBatchId()
        );
        $this->assertEquals(
            '4nQCc1T6Dg-R-zHX', $result->getContent()[1]->getBatchId()
        );
        $this->assertEquals(
            '4G4OmwztSJbVL2bl', $result->getContent()[2]->getBatchId()
        );
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

        $this->assertEquals('3SD49KIOW8lL1Z5E', $result->getBatchId());
        $this->assertEquals(2, $result->getTotalMessageCount());
        $this->assertCount(2, $result->getStatuses());

        $this->assertEquals(0, $result->getStatuses()[0]->getCode());
        $this->assertEquals(11, $result->getStatuses()[1]->getCode());

        $this->assertEquals('Delivered', $result->getStatuses()[0]->getStatus());
        $this->assertEquals('Failed', $result->getStatuses()[1]->getStatus());

        $this->assertEquals(2, $result->getStatuses()[0]->getCount());
        $this->assertEquals(1, $result->getStatuses()[1]->getCount());

        $this->assertNull($result->getStatuses()[0]->getRecipients());
        $this->assertNull($result->getStatuses()[1]->getRecipients());

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

        $this->assertEquals('4G4OmwztSJbVL2bl', $result->getBatchId());
        $this->assertEquals(2, $result->getTotalMessageCount());
        $this->assertCount(2, $result->getStatuses());

        $this->assertEquals(0, $result->getStatuses()[0]->getCode());
        $this->assertEquals(11, $result->getStatuses()[1]->getCode());

        $this->assertEquals(
            'Delivered',
            $result->getStatuses()[0]->getStatus()
        );
        $this->assertEquals('Failed', $result->getStatuses()[1]->getStatus());

        $this->assertEquals(1, $result->getStatuses()[0]->getCount());
        $this->assertEquals(1, $result->getStatuses()[1]->getCount());

        $this->assertEquals(
            ['555555555'],
            $result->getStatuses()[0]->getRecipients()
        );
        $this->assertEquals(
            ['987654321'],
            $result->getStatuses()[1]->getRecipients()
        );
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
        $expected->setBatchId('3-mbA7z9wDKY76ag');
        $expected->setOperatorStatusAt(new \DateTime('2016-12-05T16:24:00.000Z'));
        $expected->setStatusAt(new \DateTime('2016-12-05T16:24:23.318Z'));
        $expected->setStatus(XA\DeliveryStatus::FAILED);
        $expected->setCode(11);
        $expected->setRecipient('123456789');
        $expected->setStatusMessage('mystatusmessage');
        $expected->setOperator('31101');

        $this->assertEquals($expected->getBatchId(), $result->getBatchId());
        $this->assertEquals(
            $expected->getOperatorStatusAt(), $result->getOperatorStatusAt()
        );
        $this->assertEquals($expected->getStatusAt(), $result->getStatusAt());
        $this->assertEquals($expected->getStatus(), $result->getStatus());
        $this->assertEquals($expected->getCode(), $result->getCode());
        $this->assertEquals(
            $expected->getRecipient(), $result->getRecipient()
        );
        $this->assertEquals(
            $expected->getStatusMessage(), $result->getStatusMessage()
        );
        $this->assertEquals($expected->getOperator(), $result->getOperator());
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

        $this->assertEquals('12345', $result->getAutoUpdate()->getRecipient());
        $this->assertEquals(
            'hello',
            $result->getAutoUpdate()->getAddFirstWord()
        );
        $this->assertEquals(
            'world',
            $result->getAutoUpdate()->getAddSecondWord()
        );
        $this->assertEquals(
            'goodbye',
            $result->getAutoUpdate()->getRemoveFirstWord()
        );
        $this->assertEquals(
            'world',
            $result->getAutoUpdate()->getRemoveSecondWord()
        );
        $this->assertCount(0, $result->getChildGroups());
        $this->assertEquals(
            new \DateTime('2016-12-08T12:38:19.962Z'),
            $result->getCreatedAt()
        );
        $this->assertEquals('4cldmgEdAcBfcHW3', $result->getGroupId());
        $this->assertEquals(
            new \DateTime('2016-12-10T12:38:19.162Z'),
            $result->getModifiedAt()
        );
        $this->assertEquals('rah-test', $result->getName());
        $this->assertEquals(1, $result->getSize());
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

        $this->assertEquals(1, $result->getSize());
        $this->assertEquals(2, $result->getPage());
        $this->assertEquals(8, $result->getTotalSize());
        $this->assertCount(1, $result->getContent());
        $this->assertInstanceOf(
            XA\GroupResult::class, $result->getContent()[0]
        );
        $this->assertEquals(
            '4cldmgEdAcBfcHW3',
            $result->getContent()[0]->getGroupId()
        );
    }

    public function testReadGroupMembers()
    {
        $json = '["123456789", "987654321"]';

        $result = X\Deserialize::groupMembers($json);

        $this->assertEquals(['123456789', '987654321'], $result);
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

        $this->assertEquals('yes_this_is_code', $result->getCode());
        $this->assertEquals('This is a text', $result->getText());
    }

    public function testDryRunWithPerRecipients()
    {
        $json = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2,"per_recipient":[{"recipient":"987654321","body":"Hello","number_of_parts":1,"encoding":"text"},{"recipient":"555555555","body":"Hello","number_of_parts":1,"encoding":"text"}]}
EOD;

        $result = X\Deserialize::batchDryRun($json);

        $this->assertEquals(2, $result->getNumberOfRecipients());
        $this->assertEquals(2, $result->getNumberOfMessages());
        $this->assertEquals('Hello', $result->getPerRecipient()[0]->getBody());
        $this->assertEquals(
            XA\DryRunPerRecipient::ENCODING_TEXT,
            $result->getPerRecipient()[0]->getEncoding()
        );
        $this->assertEquals(
            '555555555', $result->getPerRecipient()[1]->getRecipient()
        );
        $this->assertEquals(
            1, $result->getPerRecipient()[1]->getNumberOfParts()
        );
    }

    public function testDryRunWithoutPerRecipients()
    {
        $json = <<<'EOD'
{"number_of_recipients":2,"number_of_messages":2}
EOD;

        $result = X\Deserialize::batchDryRun($json);

        $this->assertEquals(2, $result->getNumberOfRecipients());
        $this->assertEquals(2, $result->getNumberOfMessages());
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
  "udh": "00010203",
  "operator": "48271"
}
EOD;

        $result = X\Deserialize::moSms($json);

        $this->assertInstanceOf(XA\MoBinarySms::class, $result);
        $this->assertEquals('54321', $result->getRecipient());
        $this->assertEquals('123456789', $result->getSender());
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->getMessageId()
        );
        $this->assertEquals("\x03\x01", $result->getBody());
        $this->assertEquals("\x00\x01\x02\x03", $result->getUdh());
        $this->assertEquals(
            new \DateTime('2016-12-03T16:24:23.318Z'),
            $result->getSentAt()
        );
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->getReceivedAt()
        );
        $this->assertEquals('48271', $result->getOperator());
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
        $this->assertEquals('12345', $result->getRecipient());
        $this->assertEquals('987654321', $result->getSender());
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->getMessageId()
        );
        $this->assertEquals('Hello, world!', $result->getBody());
        $this->assertEquals("kivord", $result->getKeyword());
        $this->assertEquals(
            new \DateTime('2016-12-03T16:24:23.318Z'),
            $result->getSentAt()
        );
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->getReceivedAt()
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
        $this->assertEquals('12345', $result->getRecipient());
        $this->assertEquals('987654321', $result->getSender());
        $this->assertEquals(
            'b88b4cee-168f-4721-bbf9-cd748dd93b60', $result->getMessageId()
        );
        $this->assertEquals('Hello, world!', $result->getBody());
        $this->assertEquals(
            new \DateTime('2016-12-05T16:24:23.318Z'),
            $result->getReceivedAt()
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

        $this->assertEquals(2, $result->getSize());
        $this->assertEquals(3, $result->getPage());
        $this->assertEquals(9, $result->getTotalSize());
        $this->assertCount(2, $result->getContent());
        $this->assertInstanceOf(XA\MoTextSms::class, $result->getContent()[0]);
        $this->assertEquals(
            'b88b4cee', $result->getContent()[0]->getMessageId()
        );
        $this->assertInstanceOf(
            XA\MoBinarySms::class, $result->getContent()[1]
        );
        $this->assertEquals(
            'cd748dd93b60', $result->getContent()[1]->getMessageId()
        );
    }

}

?>