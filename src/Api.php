<?php

/**
 * Contains the XMS API object classes. These classes represent the
 * objects that are transmitted to and from the XMS endpoint.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * A collection of known delivery report types. These values are known
 * to be valid in MtSmsBatch#deliveryReport.
 */
class ReportType
{

    const NONE = 'none';
    const SUMMARY = 'summary';
    const FULL = 'full';
    const PER_RECIPIENT = 'per_recipient';

}

/**
 * A collection of known delivery statuses.
 */
class DeliveryStatus
{

    /**
     * Message is queued within REST API system and will be dispatched
     * according to the rate of the account.
     */
    const QUEUED = "Queued";

    /**
     * Message has been dispatched and accepted for delivery by the
     * SMSC.
     */
    const DISPATCHED = "Dispatched";

    /**
     * Message was aborted before reaching SMSC.
     */
    const ABORTED = "Aborted";

    /**
     * Message was rejected by SMSC.
     */
    const REJECTED = "Rejected";

    /**
     * Message has been delivered.
     */
    const DELIVERED = "Delivered";

    /**
     * Message failed to be delivered.
     */
    const FAILED = "Failed";

    /**
     * Message expired before delivery.
     */
    const EXPIRED = "Expired";

    /**
     * It is not known if message was delivered or not.
     */
    const UNKNOWN = "Unknown";

}

/**
 * Base class for all SMS batch classes. Holds fields that are common
 * to both create and result classes.
 */
class MtSmsBatch
{

    public $recipients;

    public $sender;

    public $deliveryReport;

    public $sendAt;

    public $expireAt;

    public $callbackUrl;

    /**
     * Prevent introduction of new fields. Typically this would happen when a
     * misspelling a real field. Will always throw an
     * `\InvalidArgumentException`.
     *
     * @param string $name  the field name
     * @param string $value the value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        $msg = "Attempt to set unknown field '$name'";
        throw new \InvalidArgumentException($msg);
    }

}

/**
 * Describes parameters available during batch creation. We can create
 * two kinds of batches, textual and binary, described in the child
 * classes MtTextSmsBatchCreate and MtBinarySmsBatchCreate,
 * respectively.
 */
class MtSmsBatchCreate extends MtSmsBatch
{

    /**
     * The initial set of tags to give the batch.
     */
    public $tags;

}

class MtTextSmsBatchCreate extends MtSmsBatchCreate
{

    /**
     * The message text. May be a message template.
     */
    public $body;

    public $parameters = array();

}

class MtBinarySmsBatchCreate extends MtSmsBatchCreate
{

    /**
     * The binary SMS body.
     */
    public $body;

    /**
     * The SMS user data header.
     */
    public $udh;

}

class MtSmsBatchResponse extends MtSmsBatch
{

    public $batchId;

    public $createdAt;

    public $modifiedAt;

    public $canceled;

}

class MtTextSmsBatchResponse extends MtSmsBatchResponse
{

    public $body;

    public $parameters;

}

class MtBinarySmsBatchResponse extends MtSmsBatchResponse
{

    public $body;

    public $udh;

}

class BatchDeliveryReportStatus
{

    /**
     * The delivery status code for this recipient bucket.
     */
    public $code;

    /**
     * The delivery status for this recipient bucket.
     */
    public $status;

    /**
     * The number of recipients belonging to this bucket.
     */
    public $count;

    /**
     * The recipients having this status. Note, this is non-empty only
     * if a `full` delivery report has been requested.
     */
    public $recipients;

}

class BatchDeliveryReport
{

    public $batchId;

    public $totalMessageCount;

    public $statuses;

}

class BatchFilter
{

    public $pageSize;

    public $senders;

    public $tags;

    public $startDate;

    public $endDate;

}

class Page implements \IteratorAggregate
{

    public $page;

    public $size;

    public $totalSize;

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

/**
 * A paged result.
 *
 * It is possible to, e.g., fetch individual pages or iterate over all
 * pages.
 *
 * @api
 */
class Pages implements \IteratorAggregate
{

    private $_worker;

    /**
     * Creates a new pages object with the given page fetcher. This is
     * mainly intended for internal use.
     *
     * @param callable $worker a page fetcher
     *
     * @return Pages
     */
    public function __construct(callable $worker)
    {
        $this->_worker = $worker;
    }

    /**
     * Downloads a specific page.
     *
     * @param int $page number of the page to fetch
     *
     * @return Page a page
     *
     * @api
     */
    public function get(int $page)
    {
        return call_user_func($this->_worker, $page);
    }

    /**
     * Returns an iterator over these pages.
     *
     * @return \Iterator an iterator
     *
     * @api
     */
    public function getIterator()
    {
        return new PagesIterator($this);
    }

}

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
     *
     * @return PagesIterator an iterator
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