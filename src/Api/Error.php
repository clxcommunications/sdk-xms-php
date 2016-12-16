<?php

namespace Clx\Xms\Api;

/**
 * Describes error responses given by XMS.
 */
class Error
{

    /**
     * A code that can be used to programmatically recognize the code.
     *
     * @var string error code
     */
    public $code;

    /**
     * Human readable description of the error.
     *
     * @var string error description
     */
    public $text;

}

?>