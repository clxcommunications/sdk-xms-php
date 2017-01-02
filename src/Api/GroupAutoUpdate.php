<?php

/**
 * Contains a class that describes a group auto update rule.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A description of automatic group updates.
 *
 * An automatic update is triggered by a mobile originated message to
 * a given number containing special keywords.
 *
 * The possible actions are to add or remove the sending number to or
 * from the group, respectively.
 */
class GroupAutoUpdate
{

    /**
     * The recipient of the mobile originated message.
     *
     * @var string a short code or long number
     */
    private $_recipient;

    /**
     * Add the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    private $_addFirstWord;

    /**
     * Add the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    private $_addSecondWord;

    /**
     * Remove the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    private $_removeFirstWord;

    /**
     * Remove the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    private $_removeSecondWord;

    /**
     * Creates a new group auto update rule.
     *
     * When the given recipient receives a mobile originated SMS
     * containing keywords (first and/or second) matching the given
     * `add` pair then the sender MSISDN is added to the group.
     * Similarly, if the MO is matching the given `remove` keyword
     * pair then the MSISDN is removed from the group.
     *
     * The add and remove keyword pair may contain `null` for those
     * keywords that should not be considered. For example,
     *
     * ```php
     * new GroupAutoUpdate('12345', ['add', null], ['remove', null])
     * ```
     *
     * or equivalently
     *
     * ```php
     * new GroupAutoUpdate('12345', ['add'], ['remove'])
     * ```
     *
     * would trigger based solely on the first keyword of the MO
     * message. On the other hand,
     *
     * ```php
     * new GroupAutoUpdate('12345', ['alert', 'add'], ['alert', 'remove'])
     * ```
     *
     * would trigger only when both keywords are given in the MO
     * message.
     *
     * @param string   $recipient      recipient that triggers this rule
     * @param string[] $addWordPair    pair containing the `add` keywords
     * @param string[] $removeWordPair pair containing the `remove` keywords
     */
    public function __construct(
        $recipient,
        array $addWordPair = [null, null],
        array $removeWordPair = [null, null]
    ) {
        $this->_recipient = $recipient;

        $this->_addFirstWord = isset($addWordPair[0])
                            ? $addWordPair[0]
                            : null;

        $this->_addSecondWord = isset($addWordPair[1])
                             ? $addWordPair[1]
                             : null;

        $this->_removeFirstWord = isset($removeWordPair[0])
                               ? $removeWordPair[0]
                               : null;

        $this->_removeSecondWord = isset($removeWordPair[1])
                                ? $removeWordPair[1]
                                : null;
    }

    /**
     * Get the recipient of the mobile originated message.
     *
     * @return string a short code or long number
     */
    public function getRecipient()
    {
        return $this->_recipient;
    }

    /**
     * Get first keyword that will trigger adding the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @return string|null a keyword
     */
    public function getAddFirstWord()
    {
        return $this->_addFirstWord;
    }

    /**
     * Get second keyword that will trigger adding the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @return string|null a keyword
     */
    public function getAddSecondWord()
    {
        return $this->_addSecondWord;
    }

    /**
     * Get first keyword that will trigger removing the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @return string|null a keyword
     */
    public function getRemoveFirstWord()
    {
        return $this->_removeFirstWord;
    }

    /**
     * Get second keyword that will trigger removing the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @return string|null a keyword
     */
    public function getRemoveSecondWord()
    {
        return $this->_removeSecondWord;
    }

}

?>