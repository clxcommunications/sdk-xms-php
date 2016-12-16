<?php

/**
 * Contains JSON serialization of the XMS API object classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

class Serialize
{

    /**
     * Serializes the given `DateTime` in a ISO-8601 format.
     *
     * @param DateTime $dt the date time object to format
     *
     * @return string a date time in ISO-8601 format
     */
    private static function _dateTime($dt)
    {
        return $dt->format(\DateTime::ATOM);
    }

    private static function _toJson($fields)
    {
        return json_encode($fields, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public static function _createBatchHelper(
        &$fields, Api\MtSmsBatchCreate &$batch
    ) {
        $fields['from'] = $batch->sender;
        $fields['to'] = $batch->recipients;

        if (isset($batch->deliveryReport)) {
            $fields['delivery_report'] = $batch->deliveryReport;
        }

        if (isset($batch->sendAt)) {
            $fields['send_at'] = Serialize::_dateTime($batch->sendAt);
        }

        if (isset($batch->expireAt)) {
            $fields['expire_at'] = Serialize::_dateTime($batch->expireAt);
        }

        if (isset($batch->tags)) {
            $fields['tags'] = $batch->tags;
        }

        if (isset($batch->callbackUrl)) {
            $fields['callback_url'] = $batch->callbackUrl;
        }
    }

    public static function textBatch(Api\MtTextSmsBatchCreate $batch_create)
    {
        $fields = array(
            'type' => 'mt_text',
            'body' => $batch_create->body
        );

        if (!empty($batch_create->parameters)) {
            $fields['parameters'] = $batch_create->parameters;
        }

        Serialize::_createBatchHelper($fields, $batch_create);

        return Serialize::_toJson($fields);
    }

    public static function binaryBatch(Api\MtBinarySmsBatchCreate $batch_create)
    {
        $fields = array(
            'type' => 'mt_binary',
            'body' => base64_encode($batch_create->body),
            'udh' => bin2hex($batch_create->udh)
        );

        Serialize::_createBatchHelper($fields, $batch_create);

        return Serialize::_toJson($fields);
    }

    public static function group(Api\GroupCreate $group)
    {
        $fields = [];

        if (isset($group->name)) {
            $fields['name'] = $group->name;
        }

        if (isset($group->members)) {
            $fields['members'] = $group->members;
        }

        if (isset($group->childGroups)) {
            $fields['child_groups'] = $group->childGroups;
        }

        if (isset($group->autoUpdate)) {
            $gau = $group->autoUpdate;
            $fau = [ 'to' => $gau->recipient ];

            if (isset($gau->addFirstWord)) {
                $fau['add']['first_word'] = $gau->addFirstWord;
            }

            if (isset($gau->addSecondWord)) {
                $fau['add']['second_word'] = $gau->addSecondWord;
            }

            if (isset($gau->removeFirstWord)) {
                $fau['remove']['first_word'] = $gau->removeFirstWord;
            }

            if (isset($gau->removeSecondWord)) {
                $fau['remove']['second_word'] = $gau->removeSecondWord;
            }

            $fields['auto_update'] = $fau;
        }

        return Serialize::_toJson($fields);
    }

    /**
     * Serializes the given tags to a JSON string.
     *
     * @param string[] $tags a list of tags
     *
     * @return string a JSON formatted text
     */
    public static function tags(array $tags)
    {
        return Serialize::_toJson([ 'tags' => $tags ]);
    }

    /**
     * Serializes the given tag updates to a JSON string.
     *
     * @param string[] $tagsToAdd    list of tags
     * @param string[] $tagsToRemove list of tags
     *
     * @return string a JSON formatted text
     */
    public static function tagsUpdate(array $tagsToAdd, array $tagsToRemove)
    {
        $fields = [
            'add' => $tagsToAdd,
            'remove' => $tagsToRemove
        ];

        return Serialize::_toJson($fields);
    }

}

?>