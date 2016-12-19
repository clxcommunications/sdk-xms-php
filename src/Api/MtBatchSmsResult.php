<?php

namespace Clx\Xms\Api;

/**
 * Contains the common fields of text and binary batches.
 */
class MtBatchSmsResult extends MtBatchSms
{

    /**
     * @var string the unique batch identifier
     */
    public $batchId;

    /**
     * @var \DateTime time when this batch was created
     */
    public $createdAt;

    /**
     * @var \DateTime time when this batch was last modified
     */
    public $modifiedAt;

    /**
     * @var bool whether this batch has been canceled
     */
    public $canceled;

}

?>