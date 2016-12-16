<?php

namespace Clx\Xms\Api;

/**
 * A page of elements.
 *
 * The element type depends on the type of page that has been
 * retrieved. Typically it is one of `MtSmsBatchResponse` or
 * `GroupResponse`.
 */
class Page implements \IteratorAggregate
{

    /**
     * The page number, starting from zero.
     *
     * @var int this page's number
     */
    public $page;

    /**
     * The number of elements on this page.
     *
     * @var int the number of page elements
     */
    public $size;

    /**
     * The total number of elements across all fetched pages.
     *
     * @var int the total number of elements
     */
    public $totalSize;

    /**
     * The page elements.
     *
     * @var mixed[] the page elements
     */
    public $content;

    /**
     * Returns an iterator over the content of this page. For example,
     * if the page is the result of a batch listing then this iterator
     * will yield batch results.
     *
     * @return Traversable the page iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->content);
    }

}

?>