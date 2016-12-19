<?php

/**
 * Contains an class for group results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * This class holds the result of a group fetch operation.
 *
 * This may be used either standalone or as an element of a paged
 * result.
 */
class GroupResult
{

    /**
     * The unique group identifier.
     *
     * @var string group identifier
     */
    public $groupId;

    /**
     * The group name.
     *
     * @var string group name
     */
    public $name;

    /**
     * The number of members of this group.
     *
     * @var int number of group members
     */
    public $size;

    /**
     * A list of groups that in turn belong to this group.
     *
     * @var string[] group identifiers of the child groups
     */
    public $childGroups;

    /**
     * Describes how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * value is `null`.
     *
     * @var GroupAutoUpdate the auto update definition
     */
    public $autoUpdate;

    /**
     * The time at which this group was created.
     *
     * @var \DateTime the time of creation
     */
    public $createdAt;

    /**
     * The time when this group was last modified.
     *
     * @var \DateTime the time of modification
     */
    public $modifiedAt;

}

?>