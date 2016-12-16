<?php

namespace Clx\Xms\Api;

/**
 * Describes updates that can be performed on a group.
 */
class GroupUpdate
{

    /**
     * Updates the group name.
     *
     * If `null` then the current value is kept, if `Reset::reset()`
     * then the value is reset to its XMS default, and if set to a
     * string the name is updated.
     *
     * @var string|null|Reset the update action
     */
    public $name;

    /**
     * The MSISDNs that should be added to this group.
     *
     * @var string[]|null a list of group members to add
     */
    public $memberInsertions;

    /**
     * The MSISDNs that should be removed from this group.
     *
     * @var string[]|null a list of group members to remove
     */
    public $memberRemovals;

    /**
     * The child groups that should be added to this group.
     *
     * @var string[]|null a list of group IDs
     */
    public $childGroupInsertions;

    /**
     * The child groups that should be removed from this group.
     *
     * @var string[]|null a non-null list of group IDs
     */
    public $childGroupRemovals;

    /**
     * Identifier of a group whose members should be added to this
     * group.
     *
     * @var string|null a group ID
     */
    public $addFromGroup;

    /**
     * Identifier of a group whose members should be removed from this
     * group.
     *
     * @var string|null a group ID
     */
    public $removeFromGroup;

    /**
     * Describes how this group should be auto updated.
     *
     * If `null` then the current value is kept, if `Reset::reset()`
     * then the value is reset to its XMS default, and if set to a
     * `GroupAutoUpdate` the value is updated.
     *
     * @var GroupAutoUpdate|null|Reset an auto update description
     */
    public $autoUpdate;

    /**
     * Resets group name field to the XMS default value.
     *
     * @return GroupUpdate this object for use in a chained invocation
     */
    public function resetName()
    {
        $this->name = Reset::reset();
        return $this;
    }

    /**
     * Resets group auto update field to the XMS default value.
     *
     * @return GroupUpdate this object for use in a chained invocation
     */
    public function resetAutoUpdate()
    {
        $this->autoUpdate = Reset::reset();
        return $this;
    }

}

?>