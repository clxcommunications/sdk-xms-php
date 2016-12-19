<?php

/**
 * Contains the base class for all SMS batch create and result
 * classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Base class for all SMS batch classes.
 *
 * Holds fields that are common to both the create and response
 * classes.
 */
class MtBatchSms
{

    /**
     * The batch recipients
     *
     * @var string[] one or more MSISDNs
     */
    public $recipients;

    /**
     * The batch sender.
     *
     * @var string a short code or long number
     */
    public $sender;

    /**
     * The type of delivery report to use for this batch.
     *
     * @var ReportType the report type
     */
    public $deliveryReport;

    /**
     * The time at which this batch should be sent.
     *
     * @var \DateTime the send date and time
     */
    public $sendAt;

    /**
     * The time at which this batch should expire.
     *
     * @var \DateTime the expiry date and time
     */
    public $expireAt;

    /**
     * The URL to which callbacks should be sent.
     *
     * @var string a valid URL
     */
    public $callbackUrl;

    /**
     * Prevent introduction of new fields.
     *
     * Typically this would happen when a misspelling a real field.
     * Will always throw an `\InvalidArgumentException`.
     *
     * @param string $name  the field name
     * @param string $value the value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $msg = "Attempt to set unknown field '$name'";
        throw new \InvalidArgumentException($msg);
    }

}

?>