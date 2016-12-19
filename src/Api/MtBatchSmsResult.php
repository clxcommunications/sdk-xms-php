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