<?php

namespace Clx\Xms\Api;

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