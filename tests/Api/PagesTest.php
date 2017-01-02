<?php

use Clx\Xms\Api as XA;

class PagesTest extends PHPUnit\Framework\TestCase
{

    public function testIterationOverPages()
    {
        $results = [
            ["element0", "element1"],
            ["element2"],
            []
        ];

        $pages = new XA\Pages(
            function ($pageno) use ($results) {
                $page = new XA\Page();

                $page->setPage($pageno);
                $page->setContent($results[$pageno]);
                $page->setSize(count($results[$pageno]));
                $page->setTotalSize(3);

                return $page;
            }
        );

        foreach ($pages as $pageno => $page) {
            $this->assertSame($results[$pageno], $page->getContent());

            foreach ($page as $k => $v) {
                $this->assertSame($results[$pageno][$k], $v);
            }
        }
    }

}

?>