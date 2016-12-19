<?php

namespace Clx\Xms\Api;

/**
 * Describes parameters available during batch creation.
 *
 * We can create two kinds of batches, textual and binary, described
 * in the child classes `MtBatchSmsTextCreate` and
 * `MtBatchTextSmsCreate`, respectively.
 */
class MtBatchSmsCreate extends MtBatchSms
{

    /**
     * The initial set of tags to give the batch.
     */
    public $tags;

}

?>