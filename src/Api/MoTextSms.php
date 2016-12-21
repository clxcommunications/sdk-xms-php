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
    public $body;

    /**
     * The message keyword, if available.
     *
     * @var string|null message keyword
     */
    public $keyword;

}

?>