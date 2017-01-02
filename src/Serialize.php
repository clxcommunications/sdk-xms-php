<?php

/**
 * Contains JSON serialization of the XMS API object classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * A collection of static serialization functions.
 *
 * These function are capable of serializing XMS API data objects in a
 * manner suitable for XMS.
 */
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

    /**
     * Serializes the given fields into JSON that can be sent to XMS.
     *
     * @param [] $fields an associative array describing the JSON
     *
     * @return string a JSON formatted string
     */
    private static function _toJson($fields)
    {
        return json_encode($fields, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper that prepares the fields of a batch creates for JSON
     * serialization.
     *
     * @param Api\MtBatchSmsCreate $batch the batch to serialize
     *
     * @return [] associative array for JSON serialization
     */
    private static function _createBatchHelper(Api\MtBatchSmsCreate &$batch)
    {
        $fields = [
            'from' => $batch->getSender(),
            'to' => $batch->getRecipients()
        ];

        if (null != $batch->getDeliveryReport()) {
            $fields['delivery_report'] = $batch->getDeliveryReport();
        }

        if (null != $batch->getSendAt()) {
            $fields['send_at'] = Serialize::_dateTime($batch->getSendAt());
        }

        if (null != $batch->getExpireAt()) {
            $fields['expire_at'] = Serialize::_dateTime($batch->getExpireAt());
        }

        if (null != $batch->getTags()) {
            $fields['tags'] = $batch->getTags();
        }

        if (null != $batch->getCallbackUrl()) {
            $fields['callback_url'] = $batch->getCallbackUrl();
        }

        return $fields;
    }

    /**
     * Serializes the given text batch into JSON.
     *
     * @param Api\MtBatchTextSmsCreate $batch the batch to serialize
     *
     * @return string JSON formatted string
     */
    public static function textBatch(Api\MtBatchTextSmsCreate $batch)
    {
        $fields = Serialize::_createBatchHelper($batch);

        $fields['type'] = 'mt_text';
        $fields['body'] = $batch->getBody();

        if (!empty($batch->getParameters())) {
            $fields['parameters'] = $batch->getParameters();
        }

        return Serialize::_toJson($fields);
    }

    /**
     * Serializes the given binary batch into JSON.
     *
     * @param Api\MtBatchBinarySmsCreate $batch the batch to serialize
     *
     * @return string JSON formatted string
     */
    public static function binaryBatch(Api\MtBatchBinarySmsCreate $batch)
    {
        $fields = Serialize::_createBatchHelper($batch);

        $fields['type'] = 'mt_binary';
        $fields['body'] = base64_encode($batch->getBody());
        $fields['udh'] = bin2hex($batch->getUdh());

        return Serialize::_toJson($fields);
    }

    /**
     * Helper that prepares the given batch for serialization
     *
     * @param Api\MtBatchSmsUpdate $batch the batch to serialize
     *
     * @return [] associative array suitable for JSON serialization
     */
    private static function _batchUpdateHelper(Api\MtBatchSmsUpdate $batch)
    {
        $fields = [];

        if (!empty($batch->getRecipientInsertions())) {
            $fields['to_add'] = $batch->getRecipientInsertions();
        }

        if (!empty($batch->getRecipientRemovals())) {
            $fields['to_remove'] = $batch->getRecipientRemovals();
        }

        if (null != $batch->getSender()) {
            $fields['from'] = $batch->getSender();
        }

        if (null != $batch->getDeliveryReport()) {
            if ($batch->getDeliveryReport() === Api\Reset::reset()) {
                $fields['delivery_report'] = null;
            } else {
                $fields['delivery_report'] = $batch->getDeliveryReport();
            }
        }

        if (null != $batch->getSendAt()) {
            if ($batch->getSendAt() === Api\Reset::reset()) {
                $fields['send_at'] = null;
            } else {
                $fields['send_at'] = Serialize::_dateTime($batch->getSendAt());
            }
        }

        if (null != $batch->getExpireAt()) {
            if ($batch->getExpireAt() === Api\Reset::reset()) {
                $fields['expire_at'] = null;
            } else {
                $fields['expire_at']
                    = Serialize::_dateTime($batch->getExpireAt());
            }
        }

        if (null != $batch->getCallbackUrl()) {
            if ($batch->getCallbackUrl() === Api\Reset::reset()) {
                $fields['callback_url'] = null;
            } else {
                $fields['callback_url'] = $batch->getCallbackUrl();
            }
        }

        return $fields;
    }

    /**
     * Serializes the given text batch update into JSON.
     *
     * @param Api\MtBatchTextSmsUpdate $batch the batch update to serialize
     *
     * @return string JSON formatted string
     */
    public static function textBatchUpdate(Api\MtBatchTextSmsUpdate $batch)
    {
        $fields = Serialize::_batchUpdateHelper($batch);

        $fields['type'] = 'mt_text';

        if (null != $batch->getBody()) {
            $fields['body'] = $batch->getBody();
        }

        if (null != $batch->getParameters()) {
            if ($batch->getParameters() === Api\Reset::reset()) {
                $fields['parameters'] = null;
            } else {
                $fields['parameters'] = $batch->getParameters();
            }
        }

        return Serialize::_toJson($fields);
    }

    /**
     * Serializes the given binary batch update into JSON.
     *
     * @param Api\MtBatchBinarySmsUpdate $batch the batch update to serialize
     *
     * @return string JSON formatted string
     */
    public static function binaryBatchUpdate(Api\MtBatchBinarySmsUpdate $batch)
    {
        $fields = Serialize::_batchUpdateHelper($batch);

        $fields['type'] = 'mt_binary';

        if (null != $batch->getBody()) {
            $fields['body'] = base64_encode($batch->getBody());
        }

        if (null != $batch->getUdh()) {
            $fields['udh'] = bin2hex($batch->getUdh());
        }

        return Serialize::_toJson($fields);
    }

    /**
     * Helper that prepares the given group auto update for JSON
     * serialization.
     *
     * @param Api\GroupAutoUpdate $autoUpdate the auto update to serialize
     *
     * @return [] associative array suitable for JSON serialization
     */
    public static function _groupAutoUpdateHelper(
        Api\GroupAutoUpdate $autoUpdate
    ) {
        $fields = [ 'to' => $autoUpdate->getRecipient() ];

        if (null != $autoUpdate->getAddFirstWord()) {
            $fields['add']['first_word'] = $autoUpdate->getAddFirstWord();
        }

        if (null != $autoUpdate->getAddSecondWord()) {
            $fields['add']['second_word'] = $autoUpdate->getAddSecondWord();
        }

        if (null != $autoUpdate->getRemoveFirstWord()) {
            $fields['remove']['first_word'] = $autoUpdate->getRemoveFirstWord();
        }

        if (null != $autoUpdate->getRemoveSecondWord()) {
            $fields['remove']['second_word'] = $autoUpdate->getRemoveSecondWord();
        }

        return $fields;
    }

    /**
     * Serializes the given group create object to JSON.
     *
     * @param Api\GroupCreate $group the group to serialize
     *
     * @return string a JSON string
     */
    public static function group(Api\GroupCreate $group)
    {
        $fields = [];

        if ($group->getName() != null) {
            $fields['name'] = $group->getName();
        }

        if (!empty($group->getMembers())) {
            $fields['members'] = $group->getMembers();
        }

        if (!empty($group->getChildGroups())) {
            $fields['child_groups'] = $group->getChildGroups();
        }

        if ($group->getAutoUpdate() != null) {
            $fields['auto_update'] = Serialize::_groupAutoUpdateHelper(
                $group->getAutoUpdate()
            );
        }

        if (!empty($group->getTags())) {
            $fields['tags'] = $group->getTags();
        }

        return Serialize::_toJson($fields);
    }

    /**
     * Serializes the given group update object to JSON.
     *
     * @param Api\GroupUpdate $groupUpdate the group update to serialize
     *
     * @return string a JSON string
     */
    public static function groupUpdate(Api\GroupUpdate $groupUpdate)
    {
        $fields = [];

        if (null != $groupUpdate->getName()) {
            $fields['name'] = $groupUpdate->getName() === Api\Reset::reset()
                            ? null
                            : $groupUpdate->getName();
        }

        if (!empty($groupUpdate->getMemberInsertions())) {
            $fields['add'] = $groupUpdate->getMemberInsertions();
        }

        if (!empty($groupUpdate->getMemberRemovals())) {
            $fields['remove'] = $groupUpdate->getMemberRemovals();
        }

        if (!empty($groupUpdate->getChildGroupInsertions())) {
            $fields['child_groups_add']
                = $groupUpdate->getChildGroupInsertions();
        }

        if (!empty($groupUpdate->getChildGroupRemovals())) {
            $fields['child_groups_remove']
                = $groupUpdate->getChildGroupRemovals();
        }

        if (null != $groupUpdate->getAddFromGroup()) {
            $fields['add_from_group'] = $groupUpdate->getAddFromGroup();
        }

        if (null != $groupUpdate->getRemoveFromGroup()) {
            $fields['remove_from_group'] = $groupUpdate->getRemoveFromGroup();
        }

        if (null != $groupUpdate->getAutoUpdate()) {
            if ($groupUpdate->getAutoUpdate() === Api\Reset::reset()) {
                $fields['auto_update'] = null;
            } else {
                $fields['auto_update'] = Serialize::_groupAutoUpdateHelper(
                    $groupUpdate->getAutoUpdate()
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