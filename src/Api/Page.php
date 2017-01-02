<?php

/**
 * Contains a class that represents a page.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

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
    private $_page;

    /**
     * The number of elements on this page.
     *
     * @var int the number of page elements
     */
    private $_size;

    /**
     * The total number of elements across all fetched pages.
     *
     * @var int the total number of elements
     */
    private $_totalSize;

    /**
     * The page elements.
     *
     * @var mixed[] the page elements
     */
    private $_content;

    /**
     * Returns an iterator over the content of this page. For example,
     * if the page is the result of a batch listing then this iterator
     * will yield batch results.
     *
     * @return Traversable the page iterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->_content);
    }

    /**
     * Get the page number, starting from zero.
     *
     * @return int this page's number
     */
    public function getPage()
    {
        return $this->_page;
    }

    /**
     * Set the page number, starting from zero.
     *
     * @param int $page this page's number
     *
     * @return void
     */
    public function setPage($page)
    {
        $this->_page = $page;
    }

    /**
     * Get the number of elements on this page.
     *
     * @return int the number of page elements
     */
    public function getSize()
    {
        return $this->_size;
    }

    /**
     * Set the number of elements on this page.
     *
     * @param int $size the number of page elements
     *
     * @return void
     */
    public function setSize($size)
    {
        $this->_size = $size;
    }

    /**
     * Get the total number of elements across all fetched pages.
     *
     * @return int the total number of elements
     */
    public function getTotalSize()
    {
        return $this->_totalSize;
    }

    /**
     * Set the total number of elements across all fetched pages.
     *
     * @param int $totalSize the total number of elements
     *
     * @return void
     */
    public function setTotalSize($totalSize)
    {
        $this->_totalSize = $totalSize;
    }

    /**
     * Get the page elements.
     *
     * @return mixed[] the page elements
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Set the page elements.
     *
     * @param mixed[] $content the page elements
     *
     * @return void
     */
    public function setContent(array $content)
    {
        $this->_content = $content;
    }

}

?>