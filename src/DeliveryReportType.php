<?php

/**
 * Contains constants for the different delivery reports that can be
 * fetched.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * The types of delivery reports that can be retrieved.
 */
class DeliveryReportType
{

    /**
     * Indicates a summary batch delivery report.
     *
     * The summary delivery report does not include the per-recipient
     * result but rather aggregated statistics about the deliveries.
     */
    const SUMMARY = "summary";

    /**
     * Indicates a full batch delivery report.
     *
     * This includes per-recipient delivery results. For batches with
     * many destinations such reports may be very large.
     */
    const FULL = "full";

}

?>