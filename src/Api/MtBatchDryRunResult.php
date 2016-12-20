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
    public $numberOfRecipients;

    /**
     * @var int the number of messages that will be sent
     */
    public $numberOfMessages;

    /**
     * @var DryRunPerRecipient[] the per-recipient dry-run result
     */
    public $perRecipient;

}

?>