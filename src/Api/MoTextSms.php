<?php

/**
 * Contains the class for textual SMS mobile originated messages.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * An SMS mobile originated message with textual content.
 */
class MoTextSms extends MoSms
{

    /**
     * The message body.
     *
     * @var string message body
     */
    private $_body;

    /**
     * The message keyword, if available.
     *
     * @var string|null message keyword
     */
    private $_keyword;

    /**
     * Get the message body.
     *
     * @return string message body
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the message body.
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
     * Get the message keyword, if available.
     *
     * @return string|null message keyword
     */
    public function getKeyword()
    {
        return $this->_keyword;
    }

    /**
     * Set the message keyword.
     *
     * @param string|null $keyword message keyword
     *
     * @return void
     */
    public function setKeyword($keyword)
    {
        $this->_keyword = $keyword;
    }

}

?>