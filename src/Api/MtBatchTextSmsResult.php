<?php

/**
 * Contains the class that describes text SMS batch results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * A textual batch as returned by the XMS endpoint.
 *
 * This differs from the batch creation definition by the addition of,
 * for example, the batch identifier and the creation time.
 */
class MtBatchTextSmsResult extends MtBatchSmsResult
{

    /**
     * The message body or template.
     *
     * @var string the textual batch message
     */
    private $_body;

    /**
     * The template parameters.
     *
     * @see MtBatchSmsTextCreate::$parameters For an in-depth
     *     description.
     *
     * @var [] the template parameter definition
     */
    private $_parameters;

    /**
     * Get the message body or template.
     *
     * @return string the textual batch message
     */
    public function getBody()
    {
        return $this->_body;
    }

    /**
     * Set the message body or template.
     *
     * @param string $body the textual batch message
     *
     * @return void
     */
    public function setBody($body)
    {
        $this->_body = $body;
    }

    /**
     * Get the template parameters.
     *
     * @see MtBatchTextSmsCreate::setParameters() For an in-depth
     *     description.
     *
     * @return [] the template parameter definition
     */
    public function getParameters()
    {
        return $this->_parameters;
    }

    /**
     * Set the template parameters.
     *
     * @param [] $parameters the template parameter definition
     *
     * @return void
     *
     * @see MtBatchTextSmsCreate::setParameters() For an in-depth
     *     description.
     */
    public function setParameters($parameters)
    {
        $this->_parameters = $parameters;
    }

}

?>