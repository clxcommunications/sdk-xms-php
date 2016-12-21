<?php

/**
 * Contains the base class for SMS mobile originated messages.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Base class for SMS mobile originated messages.
 *
 * Holds fields that are common to both the textual and binary MO
 * classes.
 */
abstract class MoSms
{

    /**
     * The message identifier.
     *
     * @var string message identifier
     */
    public $messageId;

    /**
     * The message recipient.
     *
     * @var string a short code or long number
     */
    public $recipient;

    /**
     * The message sender.
     *
     * @var string an MSISDN
     */
    public $sender;

    /**
     * The MCCMNC of the originating operator, if available.
     *
     * @var string|null an MCCMNC or `null` if non is available
     */
    public $operator;

    /**
     * The time when this message was sent, if available.
     *
     * @var \DateTime|null the send date and time
     */
    public $sentAt;

    /**
     * The time when the messaging system received this message.
     *
     * @var \DateTime the time of receiving the message
     */
    public $receivedAt;

}

?>