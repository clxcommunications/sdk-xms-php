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
    public $body;

    /**
     * The updated binary User Data Header.
     *
     * If `null` then the existing UDH is left as-is.
     *
     * @var string|null the UDH
     */
    public $udh;

}

?>