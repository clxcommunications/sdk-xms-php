<?php

namespace Clx\Xms\Api;

/**
 * A description of the fields necessary to create a group.
 */
class GroupCreate
{

    /**
     * The group name.
     *
     * @var string group name
     */
    public $name;

    /**
     * A list of MSISDNs that belong to this group.
     *
     * @var string[] zero or more MSISDNs
     */
    public $members;

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

}

?>