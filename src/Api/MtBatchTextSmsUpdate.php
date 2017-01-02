<?php

/**
 * Contains the class that describes text SMS batch updates.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Class that the update operations that can be performed on a text
 * batch.
 */
class MtBatchTextSmsUpdate extends MtBatchSmsUpdate
{

    /**
     * The updated batch message body.
     *
     * @var string|null the batch message body
     */
    private $_body;

    /**
     * Description of how to update the batch parameters.
     *
     * @var []|null|Reset an update description
     */
    private $_parameters;

    /**
     * Get the updated batch message body or template.
     *
     * @return string|null the batch message body
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the updated batch message body or template.
     *
     * @param string|null $body the batch message body
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get description of how to update the batch parameters.
     *
     * If `null` then the parameters are kept as is, if
     * `Reset::reset()` then the value is reset to XMS default,
     * otherwise update the parameters.
     *
     * @see MtBatchTextSmsCreate::setParameters() For an in-depth
     *     description.
     *
     * @return []|null|Reset an update description
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Resets the parameters field to the XMS default value.
     *
     * @return void
     */
    public function resetParameters()
    {
        $this->_parameters = Reset::reset();
    }

    /**
     * Set description of how to update the batch parameters.
     *
     * If `null` then the parameters are kept as is, otherwise update
     * the parameters.
     *
     * @param []|null $parameters an update description
     *
     * @return void
     *
     * @see MtBatchTextSmsCreate::setParameters() For an in-depth
     *     description.
     */
    public function setParameters(array $parameters)
    {
        $this->_parameters = $parameters;
    }

}

?>