<?php

/**
 * Contains the batch filter class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Filter to use when listing batches.
 */
class BatchFilter
{

    /**
     * The maximum number of batches to retrieve per page.
     *
     * @var int page size
     */
    private $_pageSize;

    /**
     * Fetch only batches having one of these senders.
     *
     * @var string[] list of short codes and long numbers
     */
    private $_senders;

    /**
     * Fetch only batches having one or more of these tags.
     *
     * @var string[] list of tags
     */
    private $_tags;

    /**
     * Fetch only batches sent at or after this date.
     *
     * @var \DateTime start date filter
     */
    private $_startDate;

    /**
     * Fetch only batches sent before this date.
     *
     * @var \DateTime end date filter
     */
    private $_endDate;

    /**
     * Get the maximum number of batches to retrieve per page.
     *
     * @return int page size
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * Set the maximum number of batches to retrieve per page.
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
     * Get sender filter.
     *
     * @return string[] list of short codes and long numbers
     */
    public function getSenders()
    {
        return $this->_senders;
    }

    /**
     * Set sender filter.
     *
     * Only batches having one of these senders will be fetched.
     *
     * @param string[] $senders list of short codes and long numbers
     *
     * @return void
     */
    public function setSenders($senders)
    {
        $this->_senders = $senders;
    }

    /**
     * Get tag filter.
     *
     * @return string[] list of tags
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set tag filter.
     *
     * Only batches having one or more of these tags will be fetched.
     *
     * @param string[] $tags list of tags
     *
     * @return void
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
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
     * Only batches sent at or after this date will be fetched.
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
     * Only batches sent before this date will be fetched.
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