<?php

use Clx\Xms\Api as XA;

class GroupUpdateTest extends PHPUnit\Framework\TestCase
{

    public function testResetMethods()
    {
        $groupUpdate = (new XA\GroupUpdate())
                     ->resetName()
                     ->resetAutoUpdate();

        $this->assertSame(XA\Reset::reset(), $groupUpdate->name);
        $this->assertSame(XA\Reset::reset(), $groupUpdate->autoUpdate);
    }

}

?>