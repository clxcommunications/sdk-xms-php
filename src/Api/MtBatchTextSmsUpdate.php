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
    public $body;

    /**
     * Description of how to update the batch parameters.
     *
     * @var []|null|Reset an update description
     */
    public $parameters;

    /**
     * Resets the parameters field to the XMS default value.
     *
     * @return MtBatchTextSmsUpdate this object for use in a chained
     *     invocation
     */
    public function resetParameters()
    {
        $this->parameters = Reset::reset();
        return $this;
    }

}

?>