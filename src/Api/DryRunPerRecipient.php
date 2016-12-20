<?php

/**
 * Contains the class holding batch per recipient dry-run results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Per-recipient dry-run result.
 *
 * Object of this class only occur within dry-run results.
 *
 * @see MtBatchDryRunResult
 */
class DryRunPerRecipient
{

    /**
     * Constant indicating non-unicode encoding.
     */
    const ENCODING_TEXT = "text";

    /**
     * Constant indicating unicode encoding.
     */
    const ENCODING_UNICODE = "unicode";

    /**
     * @var string the recipient
     */
    public $recipient;

    /**
     * @var int number of message part needed for the recipient
     */
    public $numberOfParts;

    /**
     * @var string message body sent to this recipient
     */
    public $body;

    /**
     * Indicates the text encoding used for this recipient.
     *
     * This is one of "text" or "unicode".
     *
     * @var string text encoding used for this recipient
     *
     * @see DryRunPerRecipient::ENCODING_TEXT
     * @see DryRunPerRecipient::ENCODING_UNICODE
     */
    public $encoding;

}

?>