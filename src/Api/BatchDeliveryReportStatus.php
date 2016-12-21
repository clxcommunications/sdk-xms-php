<?php

/**
 * Contains a class that represents the delivery statistics for a
 * given statistics "bucket".
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Aggregated statistics for a given batch.
 */
class BatchDeliveryReportStatus
{

    /**
     * The delivery status code for this recipient bucket.
     *
     * @var int delivery status code
     */
    public $code;

    /**
     * The delivery status for this recipient bucket.
     *
     * @var string delivery status
     */
    public $status;

    /**
     * The number of recipients belonging to this bucket.
     *
     * @var int number of recipients
     */
    public $count;

    /**
     * The recipients having this status.
     *
     * Note, this is non-empty only if a `full` delivery report has
     * been requested.
     *
     * @var string[] individual recipients
     */
    public $recipients;

}

?>