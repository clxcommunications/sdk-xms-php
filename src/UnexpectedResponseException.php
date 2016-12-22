<?php

/**
 * Contains the unexpected response exception class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Exception that indicates that XMS responded unexpectedly.
 *
 * This may mean that the server responded with an unexpected status
 * code or with, e.g., JSON of an unexpected format.
 */
class UnexpectedResponseException
    extends \UnexpectedValueException
    implements ApiException
{

    private $_httpBody;

    /**
     * Creates a new unexpected response exception.
     *
     * @param string $message the error message
     * @param string $body    the HTTP response body
     */
    public function __construct($message, $body)
    {
        parent::__construct($message);

        $this->_httpBody = $body;
    }

    /**
     * Returns the HTTP response body of the unexpected response.
     *
     * @return string the HTTP response body
     */
    public function getHttpBody()
    {
        return $this->_httpBody;
    }

}

?>