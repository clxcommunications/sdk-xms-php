<?php

namespace Clx\Xms\Api;

/**
 * A batch delivery report.
 */
class BatchDeliveryReport
{

    /**
     * Identifier of the batch that this report covers.
     *
     * @var string batch identifier
     */
    public $batchId;

    /**
     * The total number of messages sent as part of this batch.
     *
     * @var int the batch size
     */
    public $totalMessageCount;

    /**
     * The batch status buckets.
     *
     * This array describes the aggregated status for the batch where
     * each array element contains information about messages having a
     * certain delivery status and delivery code.
     *
     * @var BatchDeliveryReportStatus[] status buckets
     */
    public $statuses;

}

?>