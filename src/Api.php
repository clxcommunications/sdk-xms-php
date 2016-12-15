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
 * A collection of known delivery report types.
 *
 * These values are known to be valid in MtSmsBatch::$deliveryReport.
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
 *
 * Note, new statuses may be introduced to the XMS API.
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
 * Describes error responses given by XMS.
 */
class Error
{

    /**
     * A code that can be used to programmatically recognize the code.
     *
     * @var string error code
     */
    public $code;

    /**
     * Human readable description of the error.
     *
     * @var string error description
     */
    public $text;

}

/**
 * Base class for all SMS batch classes.
 *
 * Holds fields that are common to both the create and response
 * classes.
 */
class MtSmsBatch
{

    /**
     * The batch recipients
     *
     * @var string[] one or more MSISDNs
     */
    public $recipients;

    /**
     * The batch sender.
     *
     * @var string a short code or long number
     */
    public $sender;

    /**
     * The type of delivery report to use for this batch.
     *
     * @var ReportType the report type
     */
    public $deliveryReport;

    /**
     * The time at which this batch should be sent.
     *
     * @var \DateTime the send date and time
     */
    public $sendAt;

    /**
     * The time at which this batch should expire.
     *
     * @var \DateTime the expiry date and time
     */
    public $expireAt;

    /**
     * The URL to which callbacks should be sent.
     *
     * @var string a valid URL
     */
    public $callbackUrl;

    /**
     * Prevent introduction of new fields.
     *
     * Typically this would happen when a misspelling a real field.
     * Will always throw an `\InvalidArgumentException`.
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
 * Describes parameters available during batch creation.
 *
 * We can create two kinds of batches, textual and binary, described
 * in the child classes `MtTextSmsBatchCreate` and
 * `MtBinarySmsBatchCreate`, respectively.
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
     * The message body or template.
     *
     * @var string the textual batch message.
     */
    public $body;

    /**
     * The template parameters.
     *
     * This property is only relevant is the `$body` property is a
     * template. This is expected to be an associative array mapping
     * parameter keys to associative arrays themselves mapping
     * recipient numbers to substitution strings.
     *
     * More concretely we may have for the parameterized message
     * "Hello, ${name}!" have
     *
     * ```php
     * $parameters = [
     *     'name' => [
     *         '123456789' => 'Mary',
     *         '987654321' => 'Joe',
     *         'default' => 'valued customer'
     *     ]
     * ];
     * ```
     *
     * And the recipient with MSISDN "123456789" would then receive
     * the message "Hello, Mary!".
     *
     * Note the use of "default" to indicate the substitution for
     * recipients not explicitly given. For example, the recipient
     * "555555555" would receive the message "Hello, valued
     * customer!".
     *
     * @var [] the template parameter definition
     */
    public $parameters;

}

class MtBinarySmsBatchCreate extends MtSmsBatchCreate
{

    /**
     * The body of this binary message.
     *
     * @var string a binary string
     */
    public $body;

    /**
     * The User Data Header of this binary message.
     *
     * @var string a binary string
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

/**
 * A textual batch as returned by the XMS endpoint.
 *
 * This differs from the batch creation definition by the addition of,
 * for example, the batch identifier and the creation time.
 */
class MtTextSmsBatchResponse extends MtSmsBatchResponse
{

    /**
     * The message body or template.
     *
     * @var string the textual batch message.
     */
    public $body;

    /**
     * The template parameters.
     *
     * @see MtTextSmsBatchCreate::$parameters For an in-depth
     *     description.
     *
     * @var [] the template parameter definition
     */
    public $parameters;

}

/**
 * A binary SMS batch as returned by XMS.
 */
class MtBinarySmsBatchResponse extends MtSmsBatchResponse
{

    /**
     * The body of this binary message.
     *
     * @var string a binary string
     */
    public $body;

    /**
     * The User Data Header of this binary message.
     *
     * @var string a binary string
     */
    public $udh;

}

/**
 * Aggregated statistics for a given batch.
 */
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

/**
 * A batch delivery report.
 */
class BatchDeliveryReport
{

    /**
     * Identifier of the batch that this report covers.
     *
     * @var string batch identifier
     */
    public $batchId;

    /**
     * The total number of messages sent as part of this batch.
     *
     * @var int the batch size
     */
    public $totalMessageCount;

    /**
     * The batch status buckets.
     *
     * This array describes the aggregated status for the batch where
     * each array element contains information about messages having a
     * certain delivery status and delivery code.
     *
     * @var BatchDeliveryReportStatus[] status buckets
     */
    public $statuses;

}

/**
 * Filter to use when listing batches.
 */
class BatchFilter
{

    /**
     * The maximum number of batches to retrieve per page.
     *
     * @var int page size
     */
    public $pageSize;

    /**
     * Fetch only batches having one of these senders.
     *
     * @var string[] list of short codes and long numbers
     */
    public $senders;

    /**
     * Fetch only batches having one or more of these tags.
     *
     * @var string[] list of tags
     */
    public $tags;

    /**
     * Fetch only batches sent at or after this date.
     *
     * @var \DateTime start date filter
     */
    public $startDate;

    /**
     * Fetch only batches sent before this date.
     *
     * @var \DateTime end date filter
     */
    public $endDate;

}

/**
 * Filter to use when listing groups.
 */
class GroupFilter
{

    /**
     * The maximum number of groups to retrieve per page.
     *
     * @var int page size
     */
    public $pageSize;

    /**
     * Fetch only groups having or or more of these tags.
     *
     * @var string[] tags
     */
    public $tags;

}

/**
 * A description of automatic group updates.
 *
 * An automatic update is triggered by a mobile originated message to
 * a given number containing special keywords.
 *
 * The possible actions are to add or remove the sending number to or
 * from the group, respectively.
 */
class GroupAutoUpdate
{

    /**
     * The recipient of the mobile originated message.
     *
     * @var string a short code or long number
     */
    public $recipient;

    /**
     * Add the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    public $addFirstWord;

    /**
     * Add the sender to the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    public $addSecondWord;

    /**
     * Remove the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    public $removeFirstWord;

    /**
     * Remove the sender from the group.
     *
     * A `null` value indicates that this keyword is not used.
     *
     * @var string|null a keyword
     */
    public $removeSecondWord;

    /**
     * Creates a new group auto update rule.
     *
     * When the given recipient receives a mobile originated SMS
     * containing keywords (first and/or second) matching the given
     * `add` pair then the sender MSISDN is added to the group.
     * Similarly, if the MO is matching the given `remove` keyword
     * pair then the MSISDN is removed from the group.
     *
     * The add and remove keyword pair may contain `null` for those
     * keywords that should not be considered. For example,
     *
     * ```php
     * new GroupAutoUpdate('12345', ['add', null], ['remove', null])
     * ```
     *
     * or equivalently
     *
     * ```php
     * new GroupAutoUpdate('12345', ['add'], ['remove'])
     * ```
     *
     * would trigger based solely on the first keyword of the MO
     * message. On the other hand,
     *
     * ```php
     * new GroupAutoUpdate('12345', ['alert', 'add'], ['alert', 'remove'])
     * ```
     *
     * would trigger only when both keywords are given in the MO
     * message.
     *
     * @param string   $recipient      recipient that triggers this rule
     * @param string[] $addWordPair    pair containing the `add` keywords
     * @param string[] $removeWordPair pair containing the `remove` keywords
     *
     * @return GroupAutoUpdate the constructed object
     *
     * @throws \DomainException if recipient is `null`
     */
    public function __construct(
        string $recipient,
        array $addWordPair = [null, null],
        array $removeWordPair = [null, null]
    ) {
        if ($recipient === null) {
            throw new DomainException('recipient is null');
        }

        $this->recipient = $recipient;

        $this->addFirstWord = isset($addWordPair[0])
                            ? $addWordPair[0]
                            : null;

        $this->addSecondWord = isset($addWordPair[1])
                             ? $addWordPair[1]
                             : null;

        $this->removeFirstWord = isset($removeWordPair[0])
                               ? $removeWordPair[0]
                               : null;

        $this->removeSecondWord = isset($removeWordPair[1])
                                ? $removeWordPair[1]
                                : null;
    }

}

/**
 * A description of the fields necessary to create a group.
 */
class GroupCreate
{

    /**
     * The group name.
     *
     * @var string group name
     */
    public $name;

    /**
     * A list of MSISDNs that belong to this group.
     *
     * @var string[] zero or more MSISDNs
     */
    public $members;

    /**
     * A list of groups that in turn belong to this group.
     *
     * @var string[] group identifiers of the child groups
     */
    public $childGroups;

    /**
     * Describes how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * value is `null`.
     *
     * @var GroupAutoUpdate the auto update definition
     */
    public $autoUpdate;

}

class GroupResponse
{

    /**
     * The unique group identifier.
     *
     * @var string group identifier
     */
    public $groupId;

    /**
     * The group name.
     *
     * @var string group name
     */
    public $name;

    /**
     * The number of members of this group.
     *
     * @var int number of group members
     */
    public $size;

    /**
     * A list of groups that in turn belong to this group.
     *
     * @var string[] group identifiers of the child groups
     */
    public $childGroups;

    /**
     * Describes how this group should be auto updated.
     *
     * If no auto updating should be performed for the group then this
     * value is `null`.
     *
     * @var GroupAutoUpdate the auto update definition
     */
    public $autoUpdate;

    /**
     * The time at which this group was created.
     *
     * @var \DateTime the time of creation
     */
    public $createdAt;

    /**
     * The time when this group was last modified.
     *
     * @var \DateTime the time of modification
     */
    public $modifiedAt;

}

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