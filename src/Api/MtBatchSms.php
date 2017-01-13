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
abstract class MtBatchSms
{

    /**
     * The batch recipients
     *
     * @var string[] one or more MSISDNs
     */
    private $_recipients;

    /**
     * The batch sender.
     *
     * @var string a short code or long number
     */
    private $_sender;

    /**
     * The type of delivery report to use for this batch.
     *
     * @var string the report type
     */
    private $_deliveryReport;

    /**
     * The time at which this batch should be sent.
     *
     * @var \DateTime the send date and time
     */
    private $_sendAt;

    /**
     * The time at which this batch should expire.
     *
     * @var \DateTime the expiry date and time
     */
    private $_expireAt;

    /**
     * The URL to which callbacks should be sent.
     *
     * @var string a valid URL
     */
    private $_callbackUrl;

    /**
     * Get the batch recipients.
     *
     * @return string[] one or more MSISDNs
     */
    public function getRecipients()
    {
        return $this->_recipients;
    }

    /**
     * Set the batch recipients.
     *
     * @param string[] $recipients one or more MSISDNs
     *
     * @return void
     */
    public function setRecipients($recipients)
    {
        $this->_recipients = $recipients;
    }

    /**
     * Get the batch sender.
     *
     * @return string a short code or long number
     */
    public function getSender()
    {
        return $this->_sender;
    }

    /**
     * Set the batch sender.
     *
     * @param string $sender a short code or long number
     *
     * @return void
     */
    public function setSender($sender)
    {
        $this->_sender = $sender;
    }

    /**
     * Get the type of delivery report to use for this batch.
     *
     * @return ReportType the report type
     */
    public function getDeliveryReport()
    {
        return $this->_deliveryReport;
    }

    /**
     * Set the type of delivery report to use for this batch.
     *
     * @param ReportType $deliveryReport the report type
     *
     * @return void
     */
    public function setDeliveryReport($deliveryReport)
    {
        $this->_deliveryReport = $deliveryReport;
    }

    /**
     * Get the time at which this batch should be sent.
     *
     * @return \DateTime the send date and time
     */
    public function getSendAt()
    {
        return $this->_sendAt;
    }

    /**
     * Set the time at which this batch should be sent.
     *
     * @param \DateTime $sendAt the send date and time
     *
     * @return void
     */
    public function setSendAt($sendAt)
    {
        $this->_sendAt = $sendAt;
    }

    /**
     * Get the time at which this batch should expire.
     *
     * @return \DateTime the expiry date and time
     */
    public function getExpireAt()
    {
        return $this->_expireAt;
    }

    /**
     * Set the time at which this batch should expire.
     *
     * @param \DateTime $expireAt the expiry date and time
     *
     * @return void
     */
    public function setExpireAt($expireAt)
    {
        $this->_expireAt = $expireAt;
    }

    /**
     * Get the URL to which callbacks should be sent.
     *
     * @return string a valid URL
     */
    public function getCallbackUrl()
    {
        return $this->_callbackUrl;
    }

    /**
     * Set the URL to which callbacks should be sent.
     *
     * @param string $callbackUrl a valid URL
     *
     * @return void
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->_callbackUrl = $callbackUrl;
    }

}

?>