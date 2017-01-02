<?php

/**
 * Contains a class that describes how a group should be updated.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

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
    private $_name;

    /**
     * The MSISDNs that should be added to this group.
     *
     * @var string[]|null a list of group members to add
     */
    private $_memberInsertions;

    /**
     * The MSISDNs that should be removed from this group.
     *
     * @var string[]|null a list of group members to remove
     */
    private $_memberRemovals;

    /**
     * The child groups that should be added to this group.
     *
     * @var string[]|null a list of group IDs
     */
    private $_childGroupInsertions;

    /**
     * The child groups that should be removed from this group.
     *
     * @var string[]|null a non-null list of group IDs
     */
    private $_childGroupRemovals;

    /**
     * Identifier of a group whose members should be added to this
     * group.
     *
     * @var string|null a group ID
     */
    private $_addFromGroup;

    /**
     * Identifier of a group whose members should be removed from this
     * group.
     *
     * @var string|null a group ID
     */
    private $_removeFromGroup;

    /**
     * Describes how this group should be auto updated.
     *
     * If `null` then the current value is kept, if `Reset::reset()`
     * then the value is reset to its XMS default, and if set to a
     * `GroupAutoUpdate` the value is updated.
     *
     * @var GroupAutoUpdate|null|Reset an auto update description
     */
    private $_autoUpdate;

    /**
     * Gets the group name update.
     *
     * If `null` then the current value is kept, if `Reset::reset()`
     * then the value is reset to its XMS default, and if set to a
     * string the name is updated.
     *
     * @return string|null|Reset the update action
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Resets group name field to the XMS default value.
     *
     * @return void
     */
    public function resetName()
    {
        $this->_name = Reset::reset();
    }

    /**
     * Set the new group name.
     *
     * @param string $name the updated group name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Get the MSISDNs that should be added to this group.
     *
     * @return string[]|null a list of group members to add
     */
    public function getMemberInsertions()
    {
        return $this->_memberInsertions;
    }

    /**
     * Set the MSISDNs that should be added to this group.
     *
     * @param string[] $memberInsertions a list of group members to add
     *
     * @return void
     */
    public function setMemberInsertions($memberInsertions)
    {
        $this->_memberInsertions = $memberInsertions;
    }

    /**
     * Get the MSISDNs that should be removed from this group.
     *
     * @return string[]|null a list of group members to remove
     */
    public function getMemberRemovals()
    {
        return $this->_memberRemovals;
    }

    /**
     * Set the MSISDNs that should be removed from this group.
     *
     * @param string[] $memberRemovals a list of group members to remove
     *
     * @return void
     */
    public function setMemberRemovals($memberRemovals)
    {
        $this->_memberRemovals = $memberRemovals;
    }

    /**
     * Get the child groups that should be added to this group.
     *
     * @return string[]|null a list of group IDs
     */
    public function getChildGroupInsertions()
    {
        return $this->_childGroupInsertions;
    }

    /**
     * Set the child groups that should be added to this group.
     *
     * @param string[] $childGroupInsertions a list of group IDs
     *
     * @return void
     */
    public function setChildGroupInsertions($childGroupInsertions)
    {
        $this->_childGroupInsertions = $childGroupInsertions;
    }

    /**
     * Get the child groups that should be removed from this group.
     *
     * @return string[]|null a list of group IDs
     */
    public function getChildGroupRemovals()
    {
        return $this->_childGroupRemovals;
    }

    /**
     * Set the child groups that should be removed from this group.
     *
     * @param string[] $childGroupRemovals a list of group IDs
     *
     * @return void
     */
    public function setChildGroupRemovals($childGroupRemovals)
    {
        $this->_childGroupRemovals = $childGroupRemovals;
    }

    /**
     * Get identifier of the group whose members should be added to
     * this group.
     *
     * @return string|null a group ID
     */
    public function getAddFromGroup()
    {
        return $this->_addFromGroup;
    }

    /**
     * Set identifier of a group whose members should be added to this
     * group.
     *
     * @param string $addFromGroup a group ID
     *
     * @return void
     */
    public function setAddFromGroup($addFromGroup)
    {
        $this->_addFromGroup = $addFromGroup;
    }

    /**
     * Get identifier of the group whose members should be removed
     * from this group.
     *
     * @return string|null a group ID
     */
    public function getRemoveFromGroup()
    {
        return $this->_removeFromGroup;
    }

    /**
     * Set identifier of a group whose members should be removed from
     * this group.
     *
     * @param string $removeFromGroup a group ID
     *
     * @return void
     */
    public function setRemoveFromGroup($removeFromGroup)
    {
        $this->_removeFromGroup = $removeFromGroup;
    }

    /**
     * Get description of how this group should be auto updated.
     *
     * If `null` then the current value is kept, if `Reset::reset()`
     * then the value is reset to its XMS default, and if set to a
     * `GroupAutoUpdate` the value is updated.
     *
     * @return GroupAutoUpdate|null|Reset an auto update description
     */
    public function getAutoUpdate()
    {
        return $this->_autoUpdate;
    }

    /**
     * Resets group auto update field to the XMS default value.
     *
     * @return void
     */
    public function resetAutoUpdate()
    {
        $this->_autoUpdate = Reset::reset();
    }

    /**
     * Set a new group auto update description.
     *
     * @param GroupAutoUpdate $autoUpdate an auto update description
     *
     * @return void
     */
    public function setAutoUpdate($autoUpdate)
    {
        $this->_autoUpdate = $autoUpdate;
    }

}

?>