<?php

/**
 * Contains JSON deserialization of the XMS API object classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * A collection of static deserialization functions.
 *
 * These function are capable of deserializing XMS API data objects in
 * a manner suitable for XMS.
 */
class Deserialize
{

    /**
     * Attempts to parse the given JSON blob into an object.
     *
     * Uses `json_decode`. If parse fails then an exception is thrown.
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
     * Deserializes the given string into a `DateTime`.
     *
     * Assumes the string is in ISO-8601 format.
     *
     * @param string $json original JSON message
     * @param string $str  the string holding the date time
     *
     * @return DateTime a date time
     *
     * @throws UnexpectedResponseException if given invalid time string
     */
    private static function _dateTime(&$json, $str)
    {
        try {
            return new \DateTime($str);
        } catch (\Exception $ex) {
            throw new UnexpectedResponseException($ex->getMessage(), $json);
        }
    }

    /**
     * Helper that populates the given batch result.
     *
     * The batch result is populated from an object as returned by
     * `json_decode`.
     *
     * @param string               $json   original JSON string
     * @param object               $fields the JSON fields
     * @param Api\MtBatchSmsResult $object the target object
     *
     * @return void
     */
    private static function _batchResponseHelper(
        string &$json, \stdClass &$fields, Api\MtBatchSmsResult &$object
    ) {
        $object->batchId = $fields->id;
        $object->recipients = $fields->to;
        $object->sender = $fields->from;
        $object->canceled = $fields->canceled;

        if (isset($fields->delivery_report)) {
            $object->deliveryReport = $fields->delivery_report;
        }

        if (isset($fields->send_at)) {
            $object->sendAt = Deserialize::_dateTime($json, $fields->send_at);
        }

        if (isset($fields->expire_at)) {
            $object->expireAt = Deserialize::_dateTime(
                $json, $fields->expire_at
            );
        }

        if (isset($fields->created_at)) {
            $object->createdAt = Deserialize::_dateTime(
                $json, $fields->created_at
            );
        }

        if (isset($fields->modified_at)) {
            $object->modifiedAt = Deserialize::_dateTime(
                $json, $fields->modified_at
            );
        }

        if (isset($fields->callback_url)) {
            $object->callbackUrl = $fields->callback_url;
        }
    }

    /**
     * Converts an object describing parameter mappings to associative
     * arrays.
     *
     * We want an associative array but since `json_decode` produces
     * an object whose fields correspond to the substitutions we need
     * to do a bit of conversion.
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
     * Helper that creates and populates a batch result object.
     *
     * The result is populated from the result of `json_decode`.
     *
     * @param string $json   the JSON formatted string
     * @param object $fields the `json_decode` containing the result
     *
     * @return Api\MtBatchSmsResult the parsed result
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    private static function _batchResponseFromFields(&$json, &$fields)
    {
        if ($fields->type == 'mt_text') {
            $result = new Api\MtBatchTextSmsResult();
            $result->body = $fields->body;

            if (isset($fields->parameters)) {
                $result->parameters = Deserialize::_convertParameters(
                    $fields->parameters
                );
            }
        } else if ($fields->type == 'mt_binary') {
            $result = new Api\MtBatchBinarySmsResult();
            $result->udh = hex2bin($fields->udh);
            $result->body = base64_decode($fields->body);
        } else {
            throw new UnexpectedResponseException(
                "Received unexpected batch type " . $fields->type,
                $json
            );
        }

        // Read the common fields.
        Deserialize::_batchResponseHelper($json, $fields, $result);

        return $result;
    }

    /**
     * Reads a JSON blob describing a batch result.
     *
     * If the `type` field has the value `mt_text` then an
     * `MtBatchSmsTextCreate` object is returned, if the value is
     * `mt_binary` then an `MtBatchTextSmsCreate` object is
     * returned, otherwise an exception is thrown.
     *
     * @param string $json the JSON text to interpret
     *
     * @return Api\MtBatchSmsResult the parsed result
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
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
     * @return Api\Page the parsed page
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    public static function batchesPage($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Api\Page();
        $result->page = $fields->page;
        $result->size = $fields->page_size;
        $result->totalSize = $fields->count;
        $result->content = array_map(
            function ($s) use ($json) {
                return Deserialize::_batchResponseFromFields($json, $s);
            },
            $fields->batches
        );

        return $result;
    }

    /**
     * Reads a JSON formatted string describing a dry-run result.
     *
     * @param string $json the JSON text
     *
     * @return Api\MtBatchDryRunResult the parsed result
     */
    public static function batchDryRun($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Api\MtBatchDryRunResult();
        $result->numberOfRecipients = $fields->number_of_recipients;
        $result->numberOfMessages = $fields->number_of_messages;

        if (isset($fields->per_recipient)) {
            $result->perRecipient = array_map(
                function ($s) {
                    $pr = new Api\DryRunPerRecipient();
                    $pr->recipient = $s->recipient;
                    $pr->numberOfParts = $s->number_of_parts;
                    $pr->body = $s->body;
                    $pr->encoding = $s->encoding;
                    return $pr;
                },
                $fields->per_recipient
            );
        }

        return $result;
    }

    /**
     * Reads a JSON blob describing a batch delivery report.
     *
     * @param string $json the JSON text
     *
     * @return Api\BatchDeliveryReport the parsed batch delivery report
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    public static function batchDeliveryReport($json)
    {
        $fields = Deserialize::_fromJson($json);

        if (!isset($fields->type) || $fields->type != 'delivery_report_sms') {
            throw new UnexpectedResponseException(
                "Expected delivery report", $json
            );
        }

        $result = new Api\BatchDeliveryReport();
        $result->batchId = $fields->batch_id;
        $result->totalMessageCount = $fields->total_message_count;
        $result->statuses = array_map(
            function ($s) {
                $r = new Api\BatchDeliveryReportStatus();
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

    /**
     * Reads a batch recipient delivery report from the given JSON
     * text.
     *
     * @param string $json JSON formatted text
     *
     * @return Api\batchRecipientDeliveryReport a delivery report
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    public static function batchRecipientDeliveryReport($json)
    {
        $fields = Deserialize::_fromJson($json);

        if (!isset($fields->type)
            || $fields->type != 'recipient_delivery_report_sms'
        ) {
            throw new UnexpectedResponseException(
                "Expected recipient delivery report", $json
            );
        }

        $result = new Api\BatchRecipientDeliveryReport();

        $result->batchId = $fields->batch_id;
        $result->recipient = $fields->recipient;
        $result->code = $fields->code;
        $result->status = $fields->status;
        $result->statusAt = Deserialize::_dateTime($json, $fields->at);

        if (isset($fields->status_message)) {
            $result->statusMessage = $fields->status_message;
        }

        if (isset($fields->operator)) {
            $result->operator = $fields->operator;
        }

        if (isset($fields->operator_status_at)) {
            $result->operatorStatusAt = Deserialize::_dateTime(
                $json, $fields->operator_status_at
            );
        }

        return $result;
    }

    /**
     * Helper that creates a group auto update object from the given
     * fields.
     *
     * @param object $fields the fields as generated by `json_decode`
     *
     * @return Api\GroupAutoUpdate the created group auto update
     */
    private static function _autoUpdateFromFields(&$fields)
    {
        $result = new Api\GroupAutoUpdate($fields->to);

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

    /**
     * Helper that creates a group response object from the given
     * fields.
     *
     * @param string $json   original JSON message
     * @param object $fields the fields as generated by `json_decode`
     *
     * @return Api\GroupResult the created group response
     */
    private static function _groupResponseFromFields(string &$json, &$fields)
    {
        $result = new Api\GroupResult();
        $result->childGroups = $fields->child_groups;
        $result->groupId = $fields->id;
        $result->size = $fields->size;
        $result->createdAt = Deserialize::_dateTime(
            $json, $fields->created_at
        );
        $result->modifiedAt = Deserialize::_dateTime(
            $json, $fields->modified_at
        );

        if (isset($fields->name)) {
            $result->name = $fields->name;
        }

        if (isset($fields->auto_update)) {
            $result->autoUpdate = Deserialize::_autoUpdateFromFields(
                $fields->auto_update
            );
        }

        return $result;
    }

    /**
     * Parses a group response from the given JSON text.
     *
     * @param string $json JSON formatted text
     *
     * @return Api\GroupResult the created group response
     */
    public static function groupResponse($json)
    {
        $fields = Deserialize::_fromJson($json);
        return Deserialize::_groupResponseFromFields($json, $fields);
    }

    /**
     * Parses a page of groups from the given JSON text.
     *
     * @param string $json JSON formatted text
     *
     * @return Api\Page the created page of groups
     */
    public static function groupsPage($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Api\Page();
        $result->page = $fields->page;
        $result->size = $fields->page_size;
        $result->totalSize = $fields->count;
        $result->content = array_map(
            function ($s) use ($json) {
                return Deserialize::_groupResponseFromFields($json, $s);
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
        $fields = Deserialize::_fromJson($json);
        return (array) $fields->tags;
    }

    /**
     * Reads a JSON blob containing an error response.
     *
     * @param string $json a JSON formatted text
     *
     * @return Api\Error the decoded error
     */
    public static function error($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Api\Error();
        $result->code = $fields->code;
        $result->text = $fields->text;

        return $result;
    }

    /**
     * Helper that reads an MO from the given fields.
     *
     * @param string $json   original JSON formatted text
     * @param object $fields the result of `json_decode`
     *
     * @return Api\MoSms the parsed inbound message
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    private static function _moSmsFromFields(&$json, &$fields)
    {
        if ($fields->type === 'mo_text') {
            $result = new Api\MoTextSms();
            $result->body = $fields->body;

            if (isset($fields->keyword)) {
                $result->keyword = $fields->keyword;
            }
        } else if ($fields->type === 'mo_binary') {
            $result = new Api\MoBinarySms();
            $result->body = base64_decode($fields->body);
            $result->udh = hex2bin($fields->udh);
        } else {
            throw new UnexpectedResponseException(
                "Received unexpected inbound type " . $fields->type,
                $json
            );
        }

        $result->messageId = $fields->id;
        $result->sender = $fields->from;
        $result->recipient = $fields->to;

        if (isset($fields->operator)) {
            $result->operator = $fields->operator;
        }

        if (isset($fields->sent_at)) {
            $result->sentAt = Deserialize::_dateTime($json, $fields->sent_at);
        }

        if (isset($fields->received_at)) {
            $result->receivedAt = Deserialize::_dateTime(
                $json, $fields->received_at
            );
        }

        return $result;
    }

    /**
     * Reads a JSON blob containing an MO message.
     *
     * @param string $json a JSON formatted text
     *
     * @return Api\MoSms the decoded error
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    public static function moSms($json)
    {
        $fields = Deserialize::_fromJson($json);
        return Deserialize::_moSmsFromFields($json, $fields);
    }

    /**
     * Reads a JSON blob describing a page of MO messages.
     *
     * @param string $json the JSON text
     *
     * @return Api\Page the parsed page
     *
     * @throws UnexpectedResponseException if the JSON contained an
     *     unexpected message type
     */
    public static function inboundsPage($json)
    {
        $fields = Deserialize::_fromJson($json);

        $result = new Api\Page();
        $result->page = $fields->page;
        $result->size = $fields->page_size;
        $result->totalSize = $fields->count;
        $result->content = array_map(
            function ($s) {
                return Deserialize::_moSmsFromFields($json, $s);
            },
            $fields->inbounds
        );

        return $result;
    }

}

?>