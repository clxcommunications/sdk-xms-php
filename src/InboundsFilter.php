<?php

/**
 * Contains the inbounds filter class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Filter to use when listing inbounds messages.
 */
class InboundsFilter
{

    /**
     * The maximum number of messages to retrieve per page.
     *
     * @var int page size
     */
    private $_pageSize;

    /**
     * Fetch only messages having one of these recipients.
     *
     * @var string[] list of short codes and long numbers
     */
    private $_recipients;

    /**
     * Fetch only messages received at or after this date.
     *
     * @var \DateTime start date filter
     */
    private $_startDate;

    /**
     * Fetch only messages received before this date.
     *
     * @var \DateTime end date filter
     */
    private $_endDate;

    /**
     * Get the maximum number of messages to retrieve per page.
     *
     * @return int page size
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * Set the maximum number of messages to retrieve per page.
     *
     * @param int $pageSize page size
     *
     * @return void
     */
    public function setPageSize($pageSize)
    {
        $this->_pageSize = $pageSize;
    }

    /**
     * Get recipient filter.
     *
     * @return string[] list of short codes and long numbers
     */
    public function getRecipients()
    {
        return $this->_recipients;
    }

    /**
     * Set recipient filter.
     *
     * Only messages having one of these recipients will be fetched.
     *
     * @param string[] $recipients list of short codes and long numbers
     *
     * @return void
     */
    public function setRecipients($recipients)
    {
        $this->_recipients = $recipients;
    }

    /**
     * Get start date filter.
     *
     * @return \DateTime start date filter
     */
    public function getStartDate()
    {
        return $this->_startDate;
    }

    /**
     * Set start date filter.
     *
     * Only messages received at or after this date will be fetched.
     *
     * @param \DateTime $startDate start date filter
     *
     * @return void
     */
    public function setStartDate($startDate)
    {
        $this->_startDate = $startDate;
    }

    /**
     * Get end date filter.
     *
     * @return \DateTime end date filter
     */
    public function getEndDate()
    {
        return $this->_endDate;
    }

    /**
     * Set end date filter.
     *
     * Only messages received before this date will be fetched.
     *
     * @param \DateTime $endDate end date filter
     *
     * @return void
     */
    public function setEndDate($endDate)
    {
        $this->_endDate = $endDate;
    }

}

?>