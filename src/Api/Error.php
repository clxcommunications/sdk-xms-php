<?php

/**
 * Contains a class holding an XMS error response.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Describes error responses given by XMS.
 */
class Error
{

    /**
     * A code that can be used to programmatically recognize the
     * error.
     *
     * @var string error code
     */
    private $_code;

    /**
     * Human readable description of the error.
     *
     * @var string error description
     */
    private $_text;

    /**
     * Get the error code that can be used to programmatically
     * recognize the error.
     *
     * @return string error code
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * Set the error code that can be used to programmatically
     * recognize the error.
     *
     * @param string $code error code
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->_code = $code;
    }

    /**
     * Get a human readable description of the error.
     *
     * @return string error description
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * Set the human readable description of the error.
     *
     * @param string $text error description
     *
     * @return void
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

}

?>