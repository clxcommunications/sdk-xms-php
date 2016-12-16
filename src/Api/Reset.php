<?php

namespace Clx\Xms\Api;

class Reset
{

    private static $_instance;

    public static function reset()
    {
        if (self::$_instance === null) {
            self::$_instance = new Reset();
        }

        return self::$_instance;
    }

}

?>