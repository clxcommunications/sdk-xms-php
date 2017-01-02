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
    private $_body;

    /**
     * The User Data Header of this binary message.
     *
     * @var string a binary string
     */
    private $_udh;

    /**
     * Get the body of this binary message.
     *
     * @return string a binary string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the body of this binary message.
     *
     * @param string $body a binary string
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get the User Data Header of this binary message.
     *
     * @return string a binary string
     */
    public function getUdh()
    {
        return $this->_udh;
    }

    /**
     * Set the User Data Header of this binary message.
     *
     * @param string $udh a binary string
     *
     * @return void
     */
    public function setUdh($udh)
    {
        $this->_udh = $udh;
    }

}

?>