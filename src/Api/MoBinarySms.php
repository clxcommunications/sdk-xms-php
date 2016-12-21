<?php

/**
 * Contains the class for binary SMS mobile originated messages.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * An SMS mobile originated message with binary content.
 */
class MoBinarySms extends MoSms
{

    /**
     * The binary message body.
     *
     * @var string binary string
     */
    public $body;

    /**
     * The user data header.
     *
     * @var string user data header
     */
    public $udh;

}

?>