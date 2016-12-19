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