<?php

/**
 * Contains a class describing a binary batch update operation.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Describes updates to a binary SMS batch.
 */
class MtBatchBinarySmsUpdate extends MtBatchSmsUpdate
{

    /**
     * The updated binary batch body.
     *
     * If `null` then the existing body is left as-is.
     *
     * @var string|null the batch body
     */
    private $_body;

    /**
     * The updated binary User Data Header.
     *
     * If `null` then the existing UDH is left as-is.
     *
     * @var string|null the UDH
     */
    private $_udh;

    /**
     * Get the updated binary batch body.
     *
     * If `null` then the existing body is left as-is.
     *
     * @return string|null the batch body
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the updated binary batch body.
     *
     * If `null` then the existing body is left as-is.
     *
     * @param string|null $body the batch body
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get the updated binary User Data Header.
     *
     * If `null` then the existing UDH is left as-is.
     *
     * @return string|null the UDH
     */
    public function getUdh()
    {
        return $this->_udh;
    }

    /**
     * Set the updated binary User Data Header.
     *
     * If `null` then the existing UDH is left as-is.
     *
     * @param string|null $udh the UDH
     *
     * @return void
     */
    public function setUdh($udh)
    {
        $this->_udh = $udh;
    }

}

?>