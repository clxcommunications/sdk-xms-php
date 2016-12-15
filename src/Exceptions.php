<?php

/**
 * Contains the exceptions used within the XMS SDK.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

/**
 * Base interface for exceptions thrown by the XMS SDK.
 */
interface ApiException
{
}

/**
 * Exception used to signal that XMS refused a request.
 */
class XmsErrorException extends \Exception implements ApiException
{

    /**
     * The machine readable error code.
     *
     * @var string error code
     */
    private $_code;

    /**
     * Creates a new XMS error exception.
     *
     * @param string $code    the machine readable error code
     * @param string $message the human readable error message
     */
    public function __construct(string $code, string $message)
    {
        parent::__construct($message);
        $this->_code = $code;
    }

    /**
     * Retrieves the machine readable error code.
     *
     * @return string an XMS error code
     */
    public function getErrorCode()
    {
        return $this->_code;
    }

}

/**
 * Exception indicating that a requested resources did not exist in
 * XMS.
 *
 * This exception is thrown, for example, when attempting to retrieve
 * a batch with an invalid batch identifier.
 */
class NotFoundException extends \Exception implements ApiException
{

    /**
     * URL to the missing resource.
     *
     * @var string URL to missing resource.
     */
    private $_url;

    /**
     * Creates a new resource not found exception.
     *
     * @param string $url URL to the missing resource
     */
    public function __construct(string $url)
    {
        parent::__construct("No resource found at '$url'");
        $this->_url = $url;
    }

    /**
     * Returns the URL of the missing resource.
     *
     * @return string an URL
     */
    public function getUrl()
    {
        return $this->_url;
    }

}

/**
 * Exception indicating that XMS did not accept the service plan ID
 * and authentication token.
 */
class UnauthorizedException extends \Exception implements ApiException
{

    private $_servicePlanId;

    private $_token;

    /**
     * Creates a new unauthorized exception.
     *
     * @param string $servicePlanId the service plan identifier
     * @param string $token         the authentication token
     */
    public function __construct(string $servicePlanId, string $token)
    {
        $this->_servicePlanId = $servicePlanId;
        $this->_token = $token;
    }

    /**
     * Returns the service plan identifier that was rejected.
     *
     * @return string a service plan identifier
     */
    public function getServicePlanId()
    {
        return $this->_servicePlanId;
    }

    /**
     * Returns the authentication token that was rejected.
     *
     * @return string an authentication token
     */
    public function getToken()
    {
        return $this->_token;
    }

}

class InvalidJsonException
    extends \InvalidArgumentException
    implements ApiException
{
}

class HttpCallException
    extends \UnexpectedValueException
    implements ApiException
{
}

/**
 * Exception that indicates that XMS responded unexpectedly.
 */
class UnexpectedResponseException
    extends \UnexpectedValueException
    implements ApiException
{

    private $_httpStatus;

    private $_httpBody;

    /**
     * Creates a new unexpected response exception.
     *
     * @param int    $status the HTTP status of the response
     * @param string $body   the HTTP response body
     */
    public function __construct(int $status, string $body)
    {
        parent::__construct("Received unexpected response with status '$status'");

        $this->_httpStatus = $status;
        $this->_httpBody = $body;
    }

    /**
     * Returns the HTTP status code of the response.
     *
     * @return int an HTTP status code
     */
    public function getHttpStatus()
    {
        return $this->_httpStatus;
    }

    /**
     * Returns the HTTP response body of the response
     *
     * @return string the HTTP response body
     */
    public function getHttpBody()
    {
        return $this->_httpBody;
    }

}

?>