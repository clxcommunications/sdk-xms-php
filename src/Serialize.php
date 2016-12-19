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
        &$fields, Api\MtBatchSmsCreate &$batch
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

    public static function textBatch(Api\MtBatchTextSmsCreate $batch_create)
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

    public static function binaryBatch(Api\MtBatchBinarySmsCreate $batch_create)
    {
        $fields = array(
            'type' => 'mt_binary',
            'body' => base64_encode($batch_create->body),
            'udh' => bin2hex($batch_create->udh)
        );

        Serialize::_createBatchHelper($fields, $batch_create);

        return Serialize::_toJson($fields);
    }

    private static function _batchUpdateHelper(Api\MtBatchSmsUpdate $batch)
    {
        $fields = [];

        if (isset($batch->recipientInsertions)) {
            $fields['to_add'] = $batch->recipientInsertions;
        }

        if (isset($batch->recipientRemovals)) {
            $fields['to_remove'] = $batch->recipientRemovals;
        }

        if (isset($batch->sender)) {
            $fields['from'] = $batch->sender;
        }

        if (isset($batch->deliveryReport)) {
            if ($batch->deliveryReport === Api\Reset::reset()) {
                $fields['delivery_report'] = null;
            } else {
                $fields['delivery_report'] = $batch->deliveryReport;
            }
        }

        if (isset($batch->sendAt)) {
            if ($batch->sendAt === Api\Reset::reset()) {
                $fields['send_at'] = null;
            } else {
                $fields['send_at'] = Serialize::_dateTime($batch->sendAt);
            }
        }

        if (isset($batch->expireAt)) {
            if ($batch->expireAt === Api\Reset::reset()) {
                $fields['expire_at'] = null;
            } else {
                $fields['expire_at'] = Serialize::_dateTime($batch->expireAt);
            }
        }

        if (isset($batch->callbackUrl)) {
            if ($batch->callbackUrl === Api\Reset::reset()) {
                $fields['callback_url'] = null;
            } else {
                $fields['callback_url'] = $batch->callbackUrl;
            }
        }

        return $fields;
    }

    public static function textBatchUpdate(Api\MtBatchTextSmsUpdate $batch)
    {
        $fields = Serialize::_batchUpdateHelper($batch);

        $fields['type'] = 'mt_text';

        if (isset($batch->body)) {
            $fields['body'] = $batch->body;
        }

        if (isset($batch->parameters)) {
            if ($batch->parameters === Api\Reset::reset()) {
                $fields['parameters'] = null;
            } else {
                $fields['parameters'] = $batch->parameters;
            }
        }

        return Serialize::_toJson($fields);
    }

    public static function binaryBatchUpdate(Api\MtBatchBinarySmsUpdate $batch)
    {
        $fields = Serialize::_batchUpdateHelper($batch);

        $fields['type'] = 'mt_binary';

        if (isset($batch->body)) {
            $fields['body'] = base64_encode($batch->body);
        }

        if (isset($batch->udh)) {
            $fields['udh'] = bin2hex($batch->udh);
        }

        return Serialize::_toJson($fields);
    }

    public static function _groupAutoUpdateHelper(&$autoUpdate)
    {
        $fields = [ 'to' => $autoUpdate->recipient ];

        if (isset($autoUpdate->addFirstWord)) {
            $fields['add']['first_word'] = $autoUpdate->addFirstWord;
        }

        if (isset($autoUpdate->addSecondWord)) {
            $fields['add']['second_word'] = $autoUpdate->addSecondWord;
        }

        if (isset($autoUpdate->removeFirstWord)) {
            $fields['remove']['first_word'] = $autoUpdate->removeFirstWord;
        }

        if (isset($autoUpdate->removeSecondWord)) {
            $fields['remove']['second_word'] = $autoUpdate->removeSecondWord;
        }

        return $fields;
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
            $fields['auto_update'] = Serialize::_groupAutoUpdateHelper(
                $group->autoUpdate
            );
        }

        return Serialize::_toJson($fields);
    }

    public static function groupUpdate(Api\GroupUpdate $groupUpdate)
    {
        $fields = [];

        if (isset($groupUpdate->name)) {
            $fields['name'] = $groupUpdate->name === Api\Reset::reset()
                            ? null
                            : $groupUpdate->name;
        }

        if (isset($groupUpdate->memberInsertions)) {
            $fields['add'] = $groupUpdate->memberInsertions;
        }

        if (isset($groupUpdate->memberRemovals)) {
            $fields['remove'] = $groupUpdate->memberRemovals;
        }

        if (isset($groupUpdate->childGroupInsertions)) {
            $fields['child_groups_add'] = $groupUpdate->childGroupInsertions;
        }

        if (isset($groupUpdate->childGroupRemovals)) {
            $fields['child_groups_remove'] = $groupUpdate->childGroupRemovals;
        }

        if (isset($groupUpdate->addFromGroup)) {
            $fields['add_from_group'] = $groupUpdate->addFromGroup;
        }

        if (isset($groupUpdate->removeFromGroup)) {
            $fields['remove_from_group'] = $groupUpdate->removeFromGroup;
        }

        if (isset($groupUpdate->autoUpdate)) {
            if ($groupUpdate->autoUpdate === Api\Reset::reset()) {
                $fields['auto_update'] = null;
            } else {
                $fields['auto_update'] = Serialize::_groupAutoUpdateHelper(
                    $groupUpdate->autoUpdate
                );
            }
        }

        return empty($fields) ? '{}' : Serialize::_toJson($fields);
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