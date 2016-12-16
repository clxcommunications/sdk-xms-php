<?php

namespace Clx\Xms;

/**
 * Exception used to signal that XMS refused a request.
 */
class XmsErrorException extends \Exception implements ApiException
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
    public function __construct(string $code, string $message)
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