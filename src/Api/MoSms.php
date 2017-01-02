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
    private $_messageId;

    /**
     * The message recipient.
     *
     * @var string a short code or long number
     */
    private $_recipient;

    /**
     * The message sender.
     *
     * @var string an MSISDN
     */
    private $_sender;

    /**
     * The MCCMNC of the originating operator, if available.
     *
     * @var string|null an MCCMNC or `null` if none is available
     */
    private $_operator;

    /**
     * The time when this message was sent, if available.
     *
     * @var \DateTime|null the send date and time
     */
    private $_sentAt;

    /**
     * The time when the messaging system received this message.
     *
     * @var \DateTime the time of receiving the message
     */
    private $_receivedAt;

    /**
     * Get the message identifier.
     *
     * @return string message identifier
     */
    public function getMessageId()
    {
        return $this->_messageId;
    }

    /**
     * Set the message identifier.
     *
     * @param string $messageId message identifier
     *
     * @return void
     */
    public function setMessageId($messageId)
    {
        $this->_messageId = $messageId;
    }

    /**
     * Get the message recipient.
     *
     * @return string a short code or long number
     */
    public function getRecipient()
    {
        return $this->_recipient;
    }

    /**
     * Set the message recipient.
     *
     * @param string $recipient a short code or long number
     *
     * @return void
     */
    public function setRecipient($recipient)
    {
        $this->_recipient = $recipient;
    }

    /**
     * Get the message sender.
     *
     * @return string an MSISDN
     */
    public function getSender()
    {
        return $this->_sender;
    }

    /**
     * Set the message sender.
     *
     * @param string $sender an MSISDN
     *
     * @return void
     */
    public function setSender($sender)
    {
        $this->_sender = $sender;
    }

    /**
     * Get the MCCMNC of the originating operator, if available.
     *
     * @return string|null an MCCMNC or `null` if none is available
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * Set the MCCMNC of the originating operator.
     *
     * @param string|null $operator an MCCMNC or `null` if none is available
     *
     * @return void
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
    }

    /**
     * Get the time when this message was sent, if available.
     *
     * @return \DateTime|null the send date and time
     */
    public function getSentAt()
    {
        return $this->_sentAt;
    }

    /**
     * Set the time when this message was sent.
     *
     * @param \DateTime|null $sentAt the send date and time
     *
     * @return void
     */
    public function setSentAt($sentAt)
    {
        $this->_sentAt = $sentAt;
    }

    /**
     * Get the time when the messaging system received this message.
     *
     * @return \DateTime the time of receiving the message
     */
    public function getReceivedAt()
    {
        return $this->_receivedAt;
    }

    /**
     * Set the time when the messaging system received this message.
     *
     * @param \DateTime $receivedAt the time of receiving the message
     *
     * @return void
     */
    public function setReceivedAt($receivedAt)
    {
        $this->_receivedAt = $receivedAt;
    }

}

?>