<?php

/**
 * Contains JSON deserialization of the XMS API object classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

require_once "Api.php";
require_once "Exceptions.php";

/**
 * A collection of static deserialization functions. These function
 * are capable of deserializing XMS API data objects.
 */
class Deserialize
{

    /**
     * Attempts to parse the given JSON blob into an object. Uses
     * `json_decode`. If parse fails then an exception is thrown.
     *
     * @param string $json the JSON text
     *
     * @return object an object as returned by `json_decode`
     */
    private static function _fromJson(&$json)
    {
        $fields = json_decode($json);

        if (is_null($fields)) {
            throw new UnexpectedResponseException(
                json_last_error_msg(),
                $json
            );
        }

        return $fields;
    }

    /**
     * Deserializes the given string into a `DateTime`. Assumes the
     * string is in ISO-8601 format.
     *
     * @param string $str the string holding the date time
     *
     * @return DateTime a date time
     */
    private static function _dateTime($str)
    {
        return new \DateTime($str);
    }

    /**
     * Helper that populates the given batch result. The batch result
     * is populated from an object as returned by `json_decode`.
     *
     * @param object             $fields the JSON fields
     * @param MtSmsBatchResponse $object the target object
     *
     * @return void
     */
    private static function _batchResponseHelper(
        \stdClass &$fields, MtSmsBatchResponse &$object
    ) {
        $object->batchId = $fields->id;
        $object->recipients = $fields->to;
        $object->sender = $fields->from;
        $object->canceled = $fields->canceled;

        if (isset($fields->delivery_report)) {
            $object->deliveryReport = $fields->delivery_report;
        }

        if (isset($fields->send_at)) {
            $object->sendAt = Deserialize::_dateTime($fields->send_at);
        }

        if (isset($fields->expire_at)) {
            $object->expireAt = Deserialize::_dateTime($fields->expire_at);
        }

        if (isset($fields->created_at)) {
            $object->createdAt = Deserialize::_dateTime($fields->created_at);
        }

        if (isset($fields->modified_at)) {
            $object->modifiedAt = Deserialize::_dateTime($fields->modified_at);
        }

        if (isset($fields->callback_url)) {
            $object->callbackUrl = $fields->callback_url;
        }
    }

    /**
     * Converts an object describing parameter mappings to associative
     * arrays. We want an associative array but since `json_decode`
     * produces an object whose fields correspond to the substitutions
     * we need to do a bit of conversion.
     *
     * @param object $params the parameter mapping object
     *
     * @return array the parameter mappings
     */
    private static function _convertParameters(&$params)
    {
        $res = [];

        foreach ($params as $param => $substitutions) {
            $res["$param"] = [];
            foreach ($substitutions as $key => $value) {
                $res["$param"]["$key"] = "$value";
            }
        }

        return $res;
    }

    /**
     * Helper that creates and populates a batch result object. The
     * result is populated from the result of `json_decode`.
     *
     * @param string $json   the JSON formatted string
     * @param object $fields the `json_decode` containing the result
     *
     * @return MtSmsBatchResponse the parsed result
     */
    private static function _batchResponseFromFields(&$json, &$fields)
    {
        if ($fields->type == 'mt_text') {
            $result = new MtTextSmsBatchResponse();
            $result->body = $fields->body;

            if (isset($fields->parameters)) {
                $result->parameters = Deserialize::_convertParameters(
                    $fields->parameters
                );
            }
        } else if ($fields->type == 'mt_binary') {
            $result = new MtBinarySmsBatchResponse();
            $result->udh = hex2bin($fields->udh);
            $result->body = base64_decode($fields->body);
        } else {
            throw new UnexpectedResponseException(
                "Received unexpected batch type " . $fields->type,
                $json
            );
        }

        // Read the common fields.
        Deserialize::_batchResponseHelper($fields, $result);

        return $result;
    }

    /**
     * Reads a JSON blob describing a batch result. If the `type`
     * field has the value `mt_text` then an `MtTextSmsBatchCreate`
     * object is returned, if the value is `mt_binary` then an
     * `MtBinarySmsBatchCreate` object is returned, otherwise an
     * exception is thrown.
     *
     * @param string $json the JSON text to interpret
     *
     * @return MtSmsBatchResponse the parsed result
     */
    public static function batchResponse($json)
    {
        $fields = Deserialize::_fromJson($json);
        return Deserialize::_batchResponseFromFields($json, $fields);
    }

    /**
     * Reads a JSON blob describing a page of batches.
     *
     * @param string $json the JSON text
     *
     * @return Page the parsed page
     */
    public static function batchesPage($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Page();
        $result->page = $fields->page;
        $result->size = $fields->page_size;
        $result->totalSize = $fields->count;
        $result->content = array_map(
            function ($s) {
                return Deserialize::_batchResponseFromFields($json, $s);
            },
            $fields->batches
        );

        return $result;
    }

    /**
     * Reads a JSON blob describing a batch delivery report.
     *
     * @param string $json the JSON text
     *
     * @return BatchDeliveryReport the parsed batch delivery report
     */
    public static function batchDeliveryReport($json)
    {
        $fields = Deserialize::_fromJson($json);

        if (!isset($fields->type) || $fields->type != 'delivery_report_sms') {
            throw new UnexpectedResponseException(
                "Expected delivery report", $json
            );
        }

        $result = new BatchDeliveryReport();
        $result->batchId = $fields->batch_id;
        $result->totalMessageCount = $fields->total_message_count;
        $result->statuses = array_map(
            function ($s) {
                $r = new BatchDeliveryReportStatus();
                $r->code = $s->code;
                $r->status = $s->status;
                $r->count = $s->count;
                if (isset($s->recipients)) {
                    $r->recipients = $s->recipients;
                }
                return $r;
            },
            $fields->statuses
        );

        return $result;
    }

    private static function _autoUpdateFromFields(&$fields)
    {
        $result = new GroupAutoUpdate($fields->to);

        if (isset($fields->add) && isset($fields->add->first_word)) {
            $result->addFirstWord = $fields->add->first_word;
        }

        if (isset($fields->add) && isset($fields->add->second_word)) {
            $result->addSecondWord = $fields->add->second_word;
        }

        if (isset($fields->remove) && isset($fields->remove->first_word)) {
            $result->removeFirstWord = $fields->remove->first_word;
        }

        if (isset($fields->remove) && isset($fields->remove->second_word)) {
            $result->removeSecondWord = $fields->remove->second_word;
        }

        return $result;
    }

    private static function _groupResponseFromFields(&$fields)
    {
        $result = new GroupResponse();
        $result->childGroups = $fields->child_groups;
        $result->groupId = $fields->id;
        $result->name = $fields->name;
        $result->size = $fields->size;

        if (isset($fields->auto_update)) {
            $result->autoUpdate = Deserialize::_autoUpdateFromFields(
                $fields->auto_update
            );
        }

        if (isset($fields->created_at)) {
            $result->createdAt = Deserialize::_dateTime($fields->created_at);
        }

        if (isset($fields->modified_at)) {
            $result->modifiedAt = Deserialize::_dateTime($fields->modified_at);
        }

        return $result;
    }

    public static function groupResponse($json)
    {
        $fields = Deserialize::_fromJson($json);
        return Deserialize::_groupResponseFromFields($fields);
    }

    public static function groupsPage($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Page();
        $result->page = $fields->page;
        $result->size = $fields->page_size;
        $result->totalSize = $fields->count;
        $result->content = array_map(
            function ($s) {
                return Deserialize::_groupResponseFromFields($s);
            },
            $fields->groups
        );

        return $result;
    }

    /**
     * Reads a JSON blob containing a list of tags.
     *
     * @param string $json a JSON formatted text
     *
     * @return string[] a list of tags
     */
    public static function tags($json)
    {
        return (array) Deserialize::_fromJson($json);
    }

    /**
     * Reads a JSON blob containing an error response.
     *
     * @param string $json a JSON formatted text
     *
     * @return Error the decoded error
     */
    public static function error($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Error();
        $result->code = $fields->code;
        $result->text = $fields->text;

        return $result;
    }

}

?>