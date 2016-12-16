<?php

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