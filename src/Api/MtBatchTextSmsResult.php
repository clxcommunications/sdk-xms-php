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
     * @var string the textual batch message.
     */
    public $body;

    /**
     * The template parameters.
     *
     * @see MtBatchSmsTextCreate::$parameters For an in-depth
     *     description.
     *
     * @var [] the template parameter definition
     */
    public $parameters;

}

?>