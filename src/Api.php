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

?>