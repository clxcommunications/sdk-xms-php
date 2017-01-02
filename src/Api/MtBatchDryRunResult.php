<?php

/**
 * Contains the class holding batch dry-run results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A batch dry run report.
 */
class MtBatchDryRunResult
{

    /**
     * The number of recipients that would receive the batch message.
     *
     * @var int the number of batch recipients
     */
    private $_numberOfRecipients;

    /**
     * @var int the number of messages that will be sent
     */
    private $_numberOfMessages;

    /**
     * @var DryRunPerRecipient[] the per-recipient dry-run result
     */
    private $_perRecipient;

    /**
     * Get the number of recipients that would receive the batch message.
     *
     * @return int the number of batch recipients
     */
    public function getNumberOfRecipients()
    {
        return $this->_numberOfRecipients;
    }

    /**
     * Set the number of recipients that would receive the batch message.
     *
     * @param int $numberOfRecipients the number of batch recipients
     *
     * @return void
     */
    public function setNumberOfRecipients($numberOfRecipients)
    {
        $this->_numberOfRecipients = $numberOfRecipients;
    }

    /**
     * Get the number of messages that would be sent in the batch.
     *
     * @return int the number of messages that would be sent
     */
    public function getNumberOfMessages()
    {
        return $this->_numberOfMessages;
    }

    /**
     * Set the number of messages that would be sent in the batch.
     *
     * @param int $numberOfMessages the number of messages that would be sent
     *
     * @return void
     */
    public function setNumberOfMessages($numberOfMessages)
    {
        $this->_numberOfMessages = $numberOfMessages;
    }

    /**
     * Get the per-recipient dry-run results.
     *
     * @return DryRunPerRecipient[] the per-recipient dry-run result
     */
    public function getPerRecipient()
    {
        return $this->_perRecipient;
    }

    /**
     * Set the per-recipient dry-run results.
     *
     * @param DryRunPerRecipient[] $perRecipient the per-recipient results
     *
     * @return void
     */
    public function setPerRecipient($perRecipient)
    {
        $this->_perRecipient = $perRecipient;
    }

}

?>