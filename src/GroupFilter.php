<?php

/**
 * Contains the group filter class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Filter to use when listing groups.
 */
class GroupFilter
{

    /**
     * The maximum number of groups to retrieve per page.
     *
     * @var int page size
     */
    private $_pageSize;

    /**
     * Fetch only groups having or or more of these tags.
     *
     * @var string[] tags
     */
    private $_tags;

    /**
     * Get the maximum number of groups to retrieve per page.
     *
     * @return int page size
     */
    public function getPageSize()
    {
        return $this->_pageSize;
    }

    /**
     * Set the maximum number of groups to retrieve per page.
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
     * Get tag filter.
     *
     * @return string[] tags
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set tag filter.
     *
     * Only groups having or or more of these tags will be fetched.
     *
     * @param string[] $tags tags
     *
     * @return void
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

}

?>