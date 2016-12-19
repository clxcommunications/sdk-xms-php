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
class MtBatchSmsUpdate
{

    /**
     * The message destinations to add to the batch.
     *
     * @var string[]|null a list of MSISDNs or group IDs
     */
    public $recipientInsertions;

    /**
     * The message destinations to remove from the batch.
     *
     * @var string[]|null a list of MSISDNs or group IDs
     */
    public $recipientRemovals;

    /**
     * The message originator.
     *
     * @var string|null an MSISDN or short code
     */
    public $sender;

    /**
     * Description of how to update the batch delivery report value.
     *
     * @var ReportType|null|Reset an update description
     */
    public $deliveryReport;

    /**
     * Description of how to update the batch send at value.
     *
     * @var \DateTime|null|Reset an update description
     */
    public $sendAt;

    /**
     * Description of how to update the batch expire at value.
     *
     * @var \DateTime|null|Reset an update description
     */
    public $expireAt;

    /**
     * Description of how to update the batch callback URL.
     *
     * @var string|null|Reset an update description
     */
    public $callbackUrl;

    /**
     * Resets delivery report field to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetDeliveryReport()
    {
        $this->deliveryReport = Reset::reset();
        return $this;
    }

    /**
     * Resets the send at field to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetSendAt()
    {
        $this->sendAt = Reset::reset();
        return $this;
    }

    /**
     * Resets the expire at field to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetExpireAt()
    {
        $this->expireAt = Reset::reset();
        return $this;
    }

    /**
     * Resets the callback URL to the XMS default value.
     *
     * @return MtBatchUpdate this object for use in a chained
     *                       invocation
     */
    public function resetCallbackUrl()
    {
        $this->callbackUrl = Reset::reset();
        return $this;
    }

}

?>