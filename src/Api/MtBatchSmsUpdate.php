<?php

/**
 * Contains the base class for all SMS batch update classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Describes updates that can be performed on text and binary SMS
 * batches.
 */
abstract class MtBatchSmsUpdate
{

    /**
     * The message destinations to add to the batch.
     *
     * @var string[]|null a list of MSISDNs or group IDs
     */
    private $_recipientInsertions;

    /**
     * The message destinations to remove from the batch.
     *
     * @var string[]|null a list of MSISDNs or group IDs
     */
    private $_recipientRemovals;

    /**
     * The message originator.
     *
     * @var string|null an MSISDN or short code
     */
    private $_sender;

    /**
     * Description of how to update the batch delivery report value.
     *
     * @var string|null|Reset an update description
     */
    private $_deliveryReport;

    /**
     * Description of how to update the batch send at value.
     *
     * @var \DateTime|null|Reset an update description
     */
    private $_sendAt;

    /**
     * Description of how to update the batch expire at value.
     *
     * @var \DateTime|null|Reset an update description
     */
    private $_expireAt;

    /**
     * Description of how to update the batch callback URL.
     *
     * @var string|null|Reset an update description
     */
    private $_callbackUrl;

    /**
     * Get the message destinations to add to the batch.
     *
     * @return string[]|null a list of MSISDNs or group IDs
     */
    public function getRecipientInsertions()
    {
        return $this->_recipientInsertions;
    }

    /**
     * Set the message destinations to add to the batch.
     *
     * @param string[]|null $recipientInsertions a list of MSISDNs or group IDs
     *
     * @return void
     */
    public function setRecipientInsertions($recipientInsertions)
    {
        $this->_recipientInsertions = $recipientInsertions;
    }

    /**
     * Get the message destinations to remove from the batch.
     *
     * @return string[]|null a list of MSISDNs or group IDs
     */
    public function getRecipientRemovals()
    {
        return $this->_recipientRemovals;
    }

    /**
     * Set the message destinations to remove from the batch.
     *
     * @param string[]|null $recipientRemovals a list of MSISDNs or group IDs
     *
     * @return void
     */
    public function setRecipientRemovals($recipientRemovals)
    {
        $this->_recipientRemovals = $recipientRemovals;
    }

    /**
     * Get the message originator.
     *
     * If `null` then the batch sender will remain unchanged.
     *
     * @return string|null an MSISDN or short code
     */
    public function getSender()
    {
        return $this->_sender;
    }

    /**
     * Set the message originator.
     *
     * If `null` then the batch sender will remain unchanged.
     *
     * @param string|null $sender an MSISDN or short code
     *
     * @return void
     */
    public function setSender($sender)
    {
        $this->_sender = $sender;
    }

    /**
     * Get description of how to update the batch delivery report value.
     *
     * @return string|null|Reset an update description
     */
    public function getDeliveryReport()
    {
        return $this->_deliveryReport;
    }

    /**
     * Resets delivery report field to the XMS default value.
     *
     * @return void
     */
    public function resetDeliveryReport()
    {
        $this->_deliveryReport = Reset::reset();
    }

    /**
     * Set updated batch delivery report value.
     *
     * If `null` then the current value is left unchanged.
     *
     * @param string|null $deliveryReport an update description
     *
     * @return void
     */
    public function setDeliveryReport($deliveryReport)
    {
        $this->_deliveryReport = $deliveryReport;
    }

    /**
     * Get description of how to update the batch send at value.
     *
     * @return \DateTime|null|Reset an update description
     */
    public function getSendAt()
    {
        return $this->_sendAt;
    }

    /**
     * Resets the send at field to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetSendAt()
    {
        $this->_sendAt = Reset::reset();
        return $this;
    }

    /**
     * Set updated batch scheduled send at value.
     *
     * If `null` then the current value is left unchanged.
     *
     * @param \DateTime|null $sendAt scheduled send time
     *
     * @return void
     */
    public function setSendAt($sendAt)
    {
        $this->_sendAt = $sendAt;
    }

    /**
     * Get description of how to update the batch expire at value.
     *
     * @return \DateTime|null|Reset an update description
     */
    public function getExpireAt()
    {
        return $this->_expireAt;
    }

    /**
     * Resets the expire at field to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetExpireAt()
    {
        $this->_expireAt = Reset::reset();
        return $this;
    }

    /**
     * Set updated batch expire at value.
     *
     * If `null` then the current value is left unchanged.
     *
     * @param \DateTime|null $expireAt expire at time
     *
     * @return void
     */
    public function setExpireAt($expireAt)
    {
        $this->_expireAt = $expireAt;
    }

    /**
     * Get description of how to update the batch callback URL.
     *
     * @return string|null|Reset an update description
     */
    public function getCallbackUrl()
    {
        return $this->_callbackUrl;
    }

    /**
     * Resets the callback URL to the XMS default value.
     *
     * @return void
     */
    public function resetCallbackUrl()
    {
        $this->_callbackUrl = Reset::reset();
    }

    /**
     * Set new batch callback URL.
     *
     * If `null` then the current value is left unchanged.
     *
     * @param string|null $callbackUrl updated callback URL
     *
     * @return void
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->_callbackUrl = $callbackUrl;
    }

}

?>