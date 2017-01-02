<?php

/**
 * Contains the base class for all SMS batch result classes.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Contains the common fields of text and binary batches.
 */
abstract class MtBatchSmsResult extends MtBatchSms
{

    /**
     * @var string the unique batch identifier
     */
    private $_batchId;

    /**
     * @var \DateTime time when this batch was created
     */
    private $_createdAt;

    /**
     * @var \DateTime time when this batch was last modified
     */
    private $_modifiedAt;

    /**
     * @var bool whether this batch has been canceled
     */
    private $_canceled;

    /**
     * Get the unique batch identifier.
     *
     * @return string batch identifier
     */
    public function getBatchId()
    {
        return $this->_batchId;
    }

    /**
     * Set the unique batch identifier.
     *
     * @param string $batchId batch identifier
     *
     * @return void
     */
    public function setBatchId($batchId)
    {
        $this->_batchId = $batchId;
    }

    /**
     * Get the time when this batch was created.
     *
     * @return \DateTime time when this batch was created
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * Set the time when this batch was created.
     *
     * @param \DateTime $createdAt time when this batch was created
     *
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = $createdAt;
    }

    /**
     * Get the time when this batch was last modified.
     *
     * @return \DateTime time at last modification
     */
    public function getModifiedAt()
    {
        return $this->_modifiedAt;
    }

    /**
     * Set the time when this batch was last modified.
     *
     * @param \DateTime $modifiedAt time at last modification
     *
     * @return void
     */
    public function setModifiedAt($modifiedAt)
    {
        $this->_modifiedAt = $modifiedAt;
    }

    /**
     * Whether this batch has been canceled.
     *
     * @return bool `true` if canceled, `false` otherwise
     */
    public function isCanceled()
    {
        return $this->_canceled;
    }

    /**
     * Set this batch cancellation status.
     *
     * @param bool $canceled `true` if canceled, `false` otherwise
     *
     * @return void
     */
    public function setCanceled($canceled)
    {
        $this->_canceled = $canceled;
    }

}

?>