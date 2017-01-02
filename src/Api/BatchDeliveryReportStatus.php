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
    private $_code;

    /**
     * The delivery status for this recipient bucket.
     *
     * @var string delivery status
     */
    private $_status;

    /**
     * The number of recipients belonging to this bucket.
     *
     * @var int number of recipients
     */
    private $_count;

    /**
     * The recipients having this status.
     *
     * Note, this is non-empty only if a `full` delivery report has
     * been requested.
     *
     * @var string[] individual recipients
     */
    private $_recipients;

    /**
     * Get the delivery status code for this recipient bucket.
     *
     * @return int delivery status code
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Set the delivery status code for this recipient bucket.
     *
     * @param int $code delivery status code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * Get the delivery status for this recipient bucket.
     *
     * @return string delivery status
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * Set the delivery status for this recipient bucket.
     *
     * @param string $status delivery status
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->_status = $status;
    }

    /**
     * Get the number of recipients belonging to this bucket.
     *
     * @return int number of recipients
     */
    public function getCount()
    {
        return $this->_count;
    }

    /**
     * Set the number of recipients belonging to this bucket.
     *
     * @param int $count number of recipients
     *
     * @return void
     */
    public function setCount($count)
    {
        $this->_count = $count;
    }

    /**
     * Get the recipients having this status.
     *
     * Note, this is non-empty only if a `full` delivery report has
     * been requested.
     *
     * @return string[] individual recipients
     */
    public function getRecipients()
    {
        return $this->_recipients;
    }

    /**
     * Set the recipients having this status.
     *
     * @param string[] $recipients individual recipients
     *
     * @return void
     */
    public function setRecipients($recipients)
    {
        $this->_recipients = $recipients;
    }

}

?>