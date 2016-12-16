<?php

namespace Clx\Xms\Api;

/**
 * An iterator over a paged result.
 *
 * The key is the page number and the value corresponds to the content
 * of the pages.
 *
 * @api
 */
class PagesIterator implements \Iterator
{
    private $_pages;

    private $_curPage = null;

    private $_position = 0;

    /**
     * Creates a new pages iterator for the given object.
     *
     * @param Pages $pages the pages to iterate over
     */
    public function __construct(Pages $pages)
    {
        $this->_pages = $pages;
    }

    /**
     * Rewinds the iterator.
     *
     * @return void
     *
     * @api
     */
    function rewind()
    {
        $this->_curPage = null;
        $this->_position = 0;
    }

    /**
     * Returns the current page.
     *
     * @return Page the current page
     *
     * @api
     */
    function current()
    {
        if (!isset($this->_curPage) || $this->_curPage->page != $this->_position) {
            $this->_curPage = $this->_pages->get($this->_position);
        }
        return $this->_curPage;
    }

    /**
     * Returns the current page number.
     *
     * @return int the current page number
     *
     * @api
     */
    function key()
    {
        return $this->_position;
    }

    /**
     * Steps this iterator to the next page.
     *
     * @return void
     *
     * @api
     */
    function next()
    {
        ++$this->_position;
    }

    /**
     * Whether this iterator is currently valid.
     *
     * @return bool `true` if valid, `false` otherwise
     *
     * @api
     */
    function valid()
    {
        return $this->_position == 0 || $this->_curPage->size > 0;
    }

}

?>