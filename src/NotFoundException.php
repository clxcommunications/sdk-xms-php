<?php

/**
 * Contains the not found exception class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

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
    public function __construct($url)
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

?>