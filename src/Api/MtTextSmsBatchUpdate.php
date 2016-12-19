<?php

namespace Clx\Xms\Api;

class MtTextSmsBatchUpdate extends MtSmsBatchUpdate
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
     * @return MtTextSmsBatchUpdate this object for use in a chained
     *     invocation
     */
    public function resetParameters()
    {
        $this->parameters = Reset::reset();
        return $this;
    }

}

?>