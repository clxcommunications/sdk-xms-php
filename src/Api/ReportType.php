<?php

/**
 * Contains an enumeration of delivery report types.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms\Api;

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

?>