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
     * The version string for this library.
     *
     * @var string version string
     */
    private static $_version;

    /**
     * Returns the library version.
     *
     * @return string a version string
     */
    public static function version()
    {
        if (self::$_version == null) {
            // Note! Need to bump this value after tagging a release.
            $v = new \SebastianBergmann\Version("1.0", __DIR__);
            self::$_version = $v->getVersion();
        }

        return self::$_version;
    }

}

?>