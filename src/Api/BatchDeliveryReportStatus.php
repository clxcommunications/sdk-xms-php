<?php

namespace Clx\Xms\Api;

/**
 * Aggregated statistics for a given batch.
 */
class BatchDeliveryReportStatus
{

    /**
     * The delivery status code for this recipient bucket.
     */
    public $code;

    /**
     * The delivery status for this recipient bucket.
     */
    public $status;

    /**
     * The number of recipients belonging to this bucket.
     */
    public $count;

    /**
     * The recipients having this status. Note, this is non-empty only
     * if a `full` delivery report has been requested.
     */
    public $recipients;

}

?>