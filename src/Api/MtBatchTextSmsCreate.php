<?php

/**
 * Contains the class that describes text SMS batch creation.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Class whose fields describe a text batch.
 */
class MtBatchTextSmsCreate extends MtBatchSmsCreate
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
     * This property is only relevant is the `$body` property is a
     * template. This is expected to be an associative array mapping
     * parameter keys to associative arrays themselves mapping
     * recipient numbers to substitution strings.
     *
     * More concretely we may have for the parameterized message
     * "Hello, ${name}!" have
     *
     * ```php
     * $parameters = [
     *     'name' => [
     *         '123456789' => 'Mary',
     *         '987654321' => 'Joe',
     *         'default' => 'valued customer'
     *     ]
     * ];
     * ```
     *
     * And the recipient with MSISDN "123456789" would then receive
     * the message "Hello, Mary!".
     *
     * Note the use of "default" to indicate the substitution for
     * recipients not explicitly given. For example, the recipient
     * "555555555" would receive the message "Hello, valued
     * customer!".
     *
     * @var [] the template parameter definition
     */
    public $parameters;

}

?>