<?php

/**
 * Contains library version information.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Holder of the library version.
 */
class Version
{

    /**
     * Returns the library version.
     *
     * @return string a version string
     */
    public static function version()
    {
        // Note! Need to bump this value after tagging a release.
        return "1.1.2-dev";
    }

}

?>