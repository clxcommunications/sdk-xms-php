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
    private $_recipient;

    /**
     * @var int number of message parts needed for the recipient
     */
    private $_numberOfParts;

    /**
     * @var string message body sent to this recipient
     */
    private $_body;

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
    private $_encoding;

    /**
     * Get the recipient that this dry run result concerns.
     *
     * @return string the recipient address
     */
    public function getRecipient()
    {
        return $this->_recipient;
    }

    /**
     * Set the recipient that this dry run result concerns.
     *
     * @param string $recipient the recipient address
     *
     * @return void
     */
    public function setRecipient($recipient)
    {
        $this->_recipient = $recipient;
    }

    /**
     * Get the number of message parts needed for this recipient.
     *
     * @return int number of message parts
     */
    public function getNumberOfParts()
    {
        return $this->_numberOfParts;
    }

    /**
     * Set the number of message parts needed for this recipient.
     *
     * @param int $numberOfParts number of message parts
     *
     * @return void
     */
    public function setNumberOfParts($numberOfParts)
    {
        $this->_numberOfParts = $numberOfParts;
    }

    /**
     * Get the message body sent to this recipient.
     *
     * @return string message body
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the message body sent to this recipient.
     *
     * @param string $body message body
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get the text encoding used for this recipient.
     *
     * This is one of "text" or "unicode".
     *
     * @return string text encoding used for this recipient
     *
     * @see DryRunPerRecipient::ENCODING_TEXT
     * @see DryRunPerRecipient::ENCODING_UNICODE
     */
    public function getEncoding()
    {
        return $this->_encoding;
    }

    /**
     * Set the text encoding used for this recipient.
     *
     * This is one of "text" or "unicode".
     *
     * @param string $encoding text encoding used for this recipient
     *
     * @return void
     *
     * @see DryRunPerRecipient::ENCODING_TEXT
     * @see DryRunPerRecipient::ENCODING_UNICODE
     */
    public function setEncoding($encoding)
    {
        $this->_encoding = $encoding;
    }

}

?>