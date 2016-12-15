<?php

/**
 * Contains tests of the Serialize class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

use Clx\Xms as X;

class SerializeTest extends PHPUnit\Framework\TestCase
{

    public function testBatchCreateText()
    {
        $batch = new X\MtTextSmsBatchCreate();
        $batch->sender = '12345';
        $batch->recipients = ['987654321', '123456789'];
        $batch->body = 'Hello, ${name}!';
        $batch->parameters['name'] = [
            '987654321' => 'Mary',
            '123456789' => 'Joe',
            'default' => 'you'
        ];
        $batch->deliveryReport = X\ReportType::NONE;
        $batch->expireAt = new \DateTime('2016-12-04T11:03:13.192Z');

        $actual = X\Serialize::textBatch($batch);

        $expected = <<<'EOD'
{
    "body": "Hello, ${name}!",
    "delivery_report": "none",
    "expire_at": "2016-12-04T11:03:13+00:00",
    "from": "12345",
    "to": [
        "987654321",
        "123456789"
    ],
    "parameters": {
        "name": {
            "987654321": "Mary",
            "123456789": "Joe",
            "default": "you"
        }
    },
    "type": "mt_text"
}
EOD;

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testBatchCreateBinary()
    {
        $batch = new X\MtBinarySmsBatchCreate();
        $batch->sender = '12345';
        $batch->recipients = ['987654321', '123456789'];
        $batch->body = "\x00\x01\x02\x03";
        $batch->udh = "\xff\xfe\xfd";
        $batch->deliveryReport = X\ReportType::SUMMARY;
        $batch->expireAt = new \DateTime('2016-12-17T08:15:29.969Z');
        $batch->tags = [ "tag1", "таг2" ];

        $actual = X\Serialize::binaryBatch($batch);

        $expected = <<<'EOD'
{
    "body": "AAECAw==",
    "delivery_report": "summary",
    "expire_at": "2016-12-17T08:15:29+00:00",
    "from": "12345",
    "tags": [ "tag1", "таг2" ],
    "to": [
        "987654321",
        "123456789"
    ],
    "type": "mt_binary",
    "udh": "fffefd"
}
EOD;

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

    public function testGroupCreate()
    {
        $group = new X\GroupCreate();
        $group->name = 'test name';
        $group->members = ['123456789', '987654321'];
        $group->childGroups = ['group1', 'group2'];
        $group->autoUpdate = new X\GroupAutoUpdate(
            '12345', ['ADD'], ['REMOVE', 'ME']
        );

        $actual = X\Serialize::group($group);

        $expected = <<<'EOD'
{
    "auto_update": {
        "to": "12345",
        "add": {
            "first_word": "ADD"
        },
        "remove": {
            "first_word": "REMOVE",
            "second_word": "ME"
        }
    },
    "members": ["123456789", "987654321"],
    "child_groups": ["group1", "group2"],
    "name": "test name"
}
EOD;

        $this->assertJsonStringEqualsJsonString($expected, $actual);
    }

}

?>