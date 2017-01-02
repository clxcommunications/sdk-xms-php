<?php

use Clx\Xms\Api as XA;

class GroupUpdateTest extends PHPUnit\Framework\TestCase
{

    public function testResetMethods()
    {
        $groupUpdate = new XA\GroupUpdate();
        $groupUpdate->resetName();
        $groupUpdate->resetAutoUpdate();

        $this->assertSame(XA\Reset::reset(), $groupUpdate->getName());
        $this->assertSame(XA\Reset::reset(), $groupUpdate->getAutoUpdate());
    }

}

?>