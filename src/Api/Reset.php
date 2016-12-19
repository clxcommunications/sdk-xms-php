<?php

/**
 * Contains a class used to mark value resets when updating XMS
 * batches and groups.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * Values of this class indicate that a value should be reset to its
 * default.
 *
 * Practically, this is used when updating batches and groups on the
 * XMS server.
 */
class Reset
{

    /**
     * The one instance of the Reset class.
     *
     * @var Reset the one instance of the Reset class
     */
    private static $_instance;

    /**
     * Returns the sentinel value that indicates that a field should
     * be reset.
     *
     * @return Reset the reset value
     */
    public static function reset()
    {
        if (self::$_instance === null) {
            self::$_instance = new Reset();
        }

        return self::$_instance;
    }

}

?>