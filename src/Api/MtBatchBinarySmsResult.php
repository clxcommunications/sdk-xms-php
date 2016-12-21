<?php

/**
 * Contains the class that describes binary SMS batch results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A binary SMS batch as returned by XMS.
 */
class MtBatchBinarySmsResult extends MtBatchSmsResult
{

    /**
     * The body of this binary message.
     *
     * @var string a binary string
     */
    public $body;

    /**
     * The User Data Header of this binary message.
     *
     * @var string a binary string
     */
    public $udh;

}

?>