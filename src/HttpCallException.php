<?php

/**
 * Contains the HTTP call exception class
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Exception thrown when HTTP fails catastrophically.
 *
 * This happens, for example, when cURL cannot be initialized or the
 * HTTP server didn't respond.
 */
class HttpCallException extends \RuntimeException implements ApiException
{
}

?>