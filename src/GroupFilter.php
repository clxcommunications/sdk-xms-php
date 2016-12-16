<?php

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
    public $pageSize;

    /**
     * Fetch only groups having or or more of these tags.
     *
     * @var string[] tags
     */
    public $tags;

}

?>