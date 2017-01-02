<?php

/**
 * Contains a class describing a batch recipient delivery report.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A delivery report for an individual batch recipient.
 */
class BatchRecipientDeliveryReport
{

    /**
     * @var string the batch identifier
     */
    public $batchId;

    /**
     * @var string the recipient
     */
    public $recipient;

    /**
     * @var int the delivery code
     */
    public $code;

    /**
     * @var DeliveryStatus the delivery status
     */
    public $status;

    /**
     * @var string|null the status message
     */
    public $statusMessage;

    /**
     * @var string|null the recipient's operator
     */
    public $operator;

    /**
     * @var \DateTime the time at delivery
     */
    public $statusAt;

    /**
     * @var \DateTime|null the time of delivery as reported by operator
     */
    public $operatorStatusAt;

}

?>