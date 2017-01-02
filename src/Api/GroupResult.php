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
    private $_groupId;

    /**
     * The group name.
     *
     * @var string group name
     */
    private $_name;

    /**
     * The number of members of this group.
     *
     * @var int number of group members
     */
    private $_size;

    /**
     * A list of groups that in turn belong to this group.
     *
     * @var string[] group identifiers of the child groups
     */
    private $_childGroups = [];

    /**
     * Describes how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * value is `null`.
     *
     * @var GroupAutoUpdate the auto update definition
     */
    private $_autoUpdate;

    /**
     * The time at which this group was created.
     *
     * @var \DateTime the time of creation
     */
    private $_createdAt;

    /**
     * The time when this group was last modified.
     *
     * @var \DateTime the time of modification
     */
    private $_modifiedAt;

    /**
     * Get the unique group identifier.
     *
     * @return string group identifier
     */
    public function getGroupId()
    {
        return $this->_groupId;
    }

    /**
     * Set the unique group identifier.
     *
     * @param string $groupId group identifier
     *
     * @return void
     */
    public function setGroupId($groupId)
    {
        $this->_groupId = $groupId;
    }

    /**
     * Get the group name.
     *
     * @return string group name
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Set the group name.
     *
     * @param string $name group name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * Get the number of members of this group.
     *
     * @return int number of group members
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Set the number of members of this group.
     *
     * @param int $size number of group members
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->_size = $size;
    }

    /**
     * Get a list of the groups that in turn belong to this group.
     *
     * @return string[] group identifiers of the child groups
     */
    public function getChildGroups()
    {
        return $this->_childGroups;
    }

    /**
     * Set the list of groups that in turn belong to this group.
     *
     * @param string[] $childGroups group identifiers of the child groups
     *
     * @return void
     */
    public function setChildGroups($childGroups)
    {
        $this->_childGroups = $childGroups;
    }

    /**
     * Get description of how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * returns `null`.
     *
     * @return GroupAutoUpdate|null the auto update definition
     */
    public function getAutoUpdate()
    {
        return $this->_autoUpdate;
    }

    /**
     * Set description of how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * may be set to `null`.
     *
     * @param GroupAutoUpdate|null $autoUpdate the auto update definition
     *
     * @return void
     */
    public function setAutoUpdate($autoUpdate)
    {
        $this->_autoUpdate = $autoUpdate;
    }

    /**
     * Get the time when this group was created.
     *
     * @return \DateTime the time of creation
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Set the time when this group was created.
     *
     * @param \DateTime $createdAt the time of creation
     *
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = $createdAt;
    }

    /**
     * Get the time when this group was last modified.
     *
     * @return \DateTime the time of last modification
     */
    public function getModifiedAt()
    {
        return $this->_modifiedAt;
    }

    /**
     * Set the time when this group was last modified.
     *
     * @param \DateTime $modifiedAt the time of last modification
     *
     * @return void
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->_modifiedAt = $modifiedAt;
    }

}

?>