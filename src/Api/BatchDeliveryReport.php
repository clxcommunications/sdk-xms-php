<?php

/**
 * Contains the class that describes a batch delivery report.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

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
    private $_batchId;

    /**
     * The total number of messages sent as part of this batch.
     *
     * @var int number of sent messages in the batch
     */
    private $_totalMessageCount;

    /**
     * The batch status buckets.
     *
     * This array describes the aggregated status for the batch where
     * each array element contains information about messages having a
     * certain delivery status and delivery code.
     *
     * @var BatchDeliveryReportStatus[] status buckets
     */
    private $_statuses;

    /**
     * Get identifier of the batch that this report covers.
     *
     * @return string batch identifier
     */
    public function getBatchId()
    {
        return $this->_batchId;
    }

    /**
     * Set identifier of the batch that this report covers.
     *
     * @param string $batchId batch identifier
     *
     * @return void
     */
    public function setBatchId($batchId)
    {
        $this->_batchId = $batchId;
    }

    /**
     * Get the total number of messages sent as part of this batch.
     *
     * @return int number of sent messages in the batch
     */
    public function getTotalMessageCount()
    {
        return $this->_totalMessageCount;
    }

    /**
     * Set the total number of messages sent as part of this batch.
     *
     * @param int $totalMessageCount number of sent messages in the batch
     *
     * @return void
     */
    public function setTotalMessageCount($totalMessageCount)
    {
        $this->_totalMessageCount = $totalMessageCount;
    }

    /**
     * Get the batch status buckets.
     *
     * The returned array describes the aggregated status for the
     * batch where each array element contains information about
     * messages having a certain delivery status and delivery code.
     *
     * @return BatchDeliveryReportStatus[] status buckets
     */
    public function getStatuses()
    {
        return $this->_statuses;
    }

    /**
     * Set the batch status buckets.
     *
     * The array describes the aggregated status for the
     * batch where each array element contains information about
     * messages having a certain delivery status and delivery code.
     *
     * @param BatchDeliveryReportStatus[] $statuses status buckets
     *
     * @return void
     */
    public function setStatuses($statuses)
    {
        $this->_statuses = $statuses;
    }

}

?>