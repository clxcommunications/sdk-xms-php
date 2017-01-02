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
    private $_body;

    /**
     * The user data header.
     *
     * @var string user data header
     */
    private $_udh;

    /**
     * Get the binary message body.
     *
     * @return string binary string
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the binary message body.
     *
     * @param string $body binary string
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get the user data header.
     *
     * @return string user data header
     */
    public function getUdh()
    {
        return $this->_udh;
    }

    /**
     * Set the user data header.
     *
     * @param string $udh user data header
     *
     * @return void
     */
    public function setUdh($udh)
    {
        $this->_udh = $udh;
    }

}

?>