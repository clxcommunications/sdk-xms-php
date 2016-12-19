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
    public $pageSize;

    /**
     * Fetch only groups having or or more of these tags.
     *
     * @var string[] tags
     */
    public $tags;

}

?>