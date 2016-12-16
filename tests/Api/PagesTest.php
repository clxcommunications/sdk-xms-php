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

                $page->page = $pageno;
                $page->content = $results[$pageno];
                $page->size = count($results[$pageno]);
                $page->totalSize = 3;

                return $page;
            }
        );

        foreach ($pages as $pageno => $page) {
            $this->assertSame($results[$pageno], $page->content);

            foreach ($page as $k => $v) {
                $this->assertSame($results[$pageno][$k], $v);
            }
        }
    }

}

?>