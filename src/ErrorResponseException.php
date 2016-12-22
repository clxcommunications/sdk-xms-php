<?php

/**
 * Contains the XMS error exception class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Exception used when XMS responded with an error message.
 */
class ErrorResponseException extends \Exception implements ApiException
{

    /**
     * The machine readable error code.
     *
     * @var string error code
     */
    private $_code;

    /**
     * Creates a new XMS error exception.
     *
     * @param string $code    the machine readable error code
     * @param string $message the human readable error message
     */
    public function __construct($code, $message)
    {
        parent::__construct($message);
        $this->_code = $code;
    }

    /**
     * Retrieves the machine readable error code.
     *
     * @return string an XMS error code
     */
    public function getErrorCode()
    {
        return $this->_code;
    }

}

?>