<?php

/**
 * Contains a class describing parameters used in creating a binary
 * batch.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Describes all parameters that can be used when creating a binary
 * SMS batch.
 */
class MtBatchBinarySmsCreate extends MtBatchSmsCreate
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