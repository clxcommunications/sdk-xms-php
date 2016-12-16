<?php

namespace Clx\Xms;

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

?>