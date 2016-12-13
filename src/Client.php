<?php

/**
 * Contains the XMS client class.
 *
 * PHP versions 5 and 7
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0  Apache License 2.0
 */

namespace Clx\Xms;

require_once "Exceptions.php";
require_once "Api.php";
require_once "Deserialize.php";
require_once "Serialize.php";

/**
 * Client used to communicate with the XMS server.
 *
 * @api
 */
class Client
{

    /**
     * The default XMS endpoint.
     */
    const DEFAULT_ENDPOINT = "http://localhost:8000/xms";

    private static $_version;

    private static $_userAgent;

    /**
     * An initialized cURL handle.
     *
     * TODO: Consider making this variable static because PHP will
     * treat it as thread-local. As a result, this class would become
     * thread safe. But presumably could not have multiple instances
     * in one thread?
     */
    private $_curl_handle;

    /**
     * The user service plan identifier.
     */
    private $_service_plan_id;

    /**
     * The user authentication token.
     */
    private $_token;

    /**
     * The base endpoint URL.
     */
    private $_endpoint;

    /**
     * Constructs a new XMS client.
     *
     * The constructed client will communicate with the given endpoint
     * using the given credentials. A default endpoint at CLX
     * Communications is used if no endpoint is explicitly provided.
     *
     * This client is _not_ thread-safe.
     *
     * @param string $service_plan_id the service plan identifier
     * @param string $token           the authentication token
     * @param string $endpoint        the XMS endpoint URL
     *
     * @return Client a new XMS client
     *
     * @api
     */
    public function __construct(
        string $service_plan_id,
        string $token,
        string $endpoint = Client::DEFAULT_ENDPOINT
    ) {
        $this->_service_plan_id = $service_plan_id;
        $this->_token = $token;
        $this->_endpoint = $endpoint;

        if (!($this->_curl_handle = curl_init())) {
            throw new HttpCallException("failed to initialize cURL");
        }
    }

    public function __destruct()
    {
        if ($this->_curl_handle) {
            curl_close($this->_curl_handle);
            $this->_curl_handle = null;
        }
    }

    /**
     * Builds an endpoint URL for the given sub-path.
     *
     * @param string $sub_path the sub-path
     *
     * @return string an URL
     */
    private function _url(string $sub_path)
    {
        return $this->_endpoint . '/v1' . $sub_path;
    }

    private function _curlHelper(&$url, $hasBody)
    {
        if (!isset(self::$_version)) {
            self::$_version = new \SebastianBergmann\Version("1.0", __DIR__);
        }

        if (!isset(self::$_userAgent)) {
            self::$_userAgent = 'cURL/' . curl_version()['version']
                              . ' PHP/' . PHP_VERSION;
        }

        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Authorization: Bearer ' . $this->_token,
            'X-CLX-SDK-Version: ' . self::$_version->getVersion()
        ];

        if ($hasBody) {
            array_push($headers, 'Content-Type: application/json');
        }

        curl_setopt($this->_curl_handle, CURLOPT_URL, $url);
        curl_setopt($this->_curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->_curl_handle, CURLOPT_USERAGENT, self::$_userAgent);

        $result = curl_exec($this->_curl_handle);

        if ($result === false) {
            throw new HttpCallException(curl_error($this->_curl_handle));
        }

        return $result;
    }

    private function _get($url)
    {
        return $this->_curlHelper($url, false);
    }

    private function _post($url, &$json)
    {
        curl_setopt($this->_curl_handle, CURLOPT_POST, true);
        curl_setopt($this->_curl_handle, CURLOPT_POSTFIELDS, $json);

        return $this->_curlHelper($url, true);
    }

    /**
     * Creates a new text batch. The text batch will be created as
     * described in the given object.
     *
     * @param MtTextSmsBatchCreate $batch the batch description
     *
     * @return MtTextSmsBatchResponse the creation result
     *
     * @api
     */
    public function createTextBatch(MtTextSmsBatchCreate $batch)
    {
        $json = Serialize::writeTextBatch($batch);
        $result = $this->_post($this->_url('/batches'), $json);
        return Deserialize::readBatchResponse($result);
    }

    public function createBinaryBatch(MtBinarySmsBatchCreate $batch)
    {
        $json = Serialize::writeBinaryBatch($batch);
        $result = $this->_post($this->_url('/batches'), $json);
        return Deserialize::readBatchResponse($result);
    }

    public function fetchBatch(string $batchId)
    {
        $result = $this->_get($this->_url('/batches/' . $batchId));
        return Deserialize::readBatchDeliveryReport($result);
    }

    public function fetchBatches(int $page, BatchFilter $filter = null)
    {
        $params = ["page=$page"];

        if (!is_null($filter)) {
            if (isset($filter->pageSize)) {
                array_push($params, 'page_size=' . $filter->pageSize);
            }

            if (isset($filter->senders)) {
                $val = urlencode(join(',', $filter->senders));
                array_push($params, 'from=' . $val);
            }

            if (isset($filter->tags)) {
                $val = urlencode(join(',', $filter->tags));
                array_push($params, 'tags=' . $val);
            }
        }

        $q = join('&', $params);
        $result = $this->_get($this->_url('/batches?' . $q));
        return Deserialize::readBatchesPage($result);
    }

}

?>
