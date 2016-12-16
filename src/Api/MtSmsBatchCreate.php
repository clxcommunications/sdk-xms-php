<?php

namespace Clx\Xms\Api;

/**
 * Describes parameters available during batch creation.
 *
 * We can create two kinds of batches, textual and binary, described
 * in the child classes `MtTextSmsBatchCreate` and
 * `MtBinarySmsBatchCreate`, respectively.
 */
class MtSmsBatchCreate extends MtSmsBatch
{

    /**
     * The initial set of tags to give the batch.
     */
    public $tags;

}

?>