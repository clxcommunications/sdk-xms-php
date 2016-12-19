<?php

/**
 * Contains an enumeration of delivery statuses.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A collection of known delivery statuses.
 *
 * Note, new statuses may be introduced to the XMS API.
 */
class DeliveryStatus
{

    /**
     * Message is queued within REST API system and will be dispatched
     * according to the rate of the account.
     */
    const QUEUED = "Queued";

    /**
     * Message has been dispatched and accepted for delivery by the
     * SMSC.
     */
    const DISPATCHED = "Dispatched";

    /**
     * Message was aborted before reaching SMSC.
     */
    const ABORTED = "Aborted";

    /**
     * Message was rejected by SMSC.
     */
    const REJECTED = "Rejected";

    /**
     * Message has been delivered.
     */
    const DELIVERED = "Delivered";

    /**
     * Message failed to be delivered.
     */
    const FAILED = "Failed";

    /**
     * Message expired before delivery.
     */
    const EXPIRED = "Expired";

    /**
     * It is not known if message was delivered or not.
     */
    const UNKNOWN = "Unknown";

}

?>