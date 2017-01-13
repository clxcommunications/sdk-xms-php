<?php

/**
 * Contains an iterator over paged results.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

/**
 * An iterator over a paged result.
 *
 * The key is the page number and the value corresponds to the content
 * of the pages.
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
     */
    function current()
    {
        if (!isset($this->_curPage)
            || $this->_curPage->getPage() != $this->_position
        ) {
            $this->_curPage = $this->_pages->get($this->_position);
        }
        return $this->_curPage;
    }

    /**
     * Returns the current page number.
     *
     * @return int the current page number
     */
    function key()
    {
        return $this->_position;
    }

    /**
     * Steps this iterator to the next page.
     *
     * @return void
     */
    function next()
    {
        ++$this->_position;
    }

    /**
     * Whether this iterator is currently valid.
     *
     * @return bool `true` if valid, `false` otherwise
     */
    function valid()
    {
        return $this->_position == 0 || $this->_curPage->getSize() > 0;
    }

}

?>