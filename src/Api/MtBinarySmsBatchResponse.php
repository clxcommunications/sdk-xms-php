<?php

namespace Clx\Xms\Api;

/**
 * A binary SMS batch as returned by XMS.
 */
class MtBinarySmsBatchResponse extends MtSmsBatchResponse
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