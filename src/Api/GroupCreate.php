<?php

/**
 * Contains the class holding the group create parameters.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

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
    private $_name;

    /**
     * A list of MSISDNs that belong to this group.
     *
     * @var string[] zero or more MSISDNs
     */
    private $_members = [];

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
     * @var GroupAutoUpdate|null the auto update definition
     */
    private $_autoUpdate;

    /**
     * The tags associated to this group.
     *
     * @var string[] the group tags
     */
    private $_tags = [];

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
     * Get a list of MSISDNs that belong to this group.
     *
     * @return string[] zero or more MSISDNs
     */
    public function getMembers()
    {
        return $this->_members;
    }

    /**
     * Set the list of MSISDNs that belong to this group.
     *
     * @param string[] $members zero or more MSISDNs
     *
     * @return void
     */
    public function setMembers($members)
    {
        $this->_members = $members;
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
     * Get the tags associated to this group.
     *
     * @return string[] the group tags
     */
    public function getTags()
    {
        return $this->_tags;
    }

    /**
     * Set the tags associated to this group.
     *
     * @param string[] $tags the group tags
     *
     * @return void
     */
    public function setTags($tags)
    {
        $this->_tags = $tags;
    }

}

?>