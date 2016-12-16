<?php

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
    public function __construct(string $message, string $body)
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