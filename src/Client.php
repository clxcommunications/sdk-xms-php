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
 * Holder of the library version.
 */
class Version
{

    /**
     * The version string for this library.
     *
     * @var string version string
     */
    private static $_version;

    /**
     * Returns the library version.
     *
     * @return string a version string
     */
    public static function version()
    {
        if (self::$_version == null) {
            // Note! Need to bump this value after tagging a release.
            $v = new \SebastianBergmann\Version("1.0", __DIR__);
            self::$_version = $v->getVersion();
        }

        return self::$_version;
    }

}

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

    /**
     * The user agent string that is included in each request.
     *
     * @var string the user agent string
     */
    private $_userAgent;

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
        $this->_userAgent = 'cURL/' . curl_version()['version']
                          . ' PHP/' . PHP_VERSION;

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
        $headers = [
            'Accept: application/json',
            'Accept-Encoding: gzip, deflate',
            'Connection: keep-alive',
            'Authorization: Bearer ' . $this->_token,
            'X-CLX-SDK-Version: ' . Version::version()
        ];

        if ($hasBody) {
            array_push($headers, 'Content-Type: application/json');
        }

        curl_setopt($this->_curl_handle, CURLOPT_URL, $url);
        curl_setopt($this->_curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->_curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->_curl_handle, CURLOPT_USERAGENT, $this->_userAgent);

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
        $json = Serialize::textBatch($batch);
        $result = $this->_post($this->_url('/batches'), $json);
        return Deserialize::batchResponse($result);
    }

    public function createBinaryBatch(MtBinarySmsBatchCreate $batch)
    {
        $json = Serialize::binaryBatch($batch);
        $result = $this->_post($this->_url('/batches'), $json);
        return Deserialize::batchResponse($result);
    }

    public function fetchBatch(string $batchId)
    {
        $result = $this->_get($this->_url('/batches/' . $batchId));
        return Deserialize::batchDeliveryReport($result);
    }

    public function fetchBatches(BatchFilter $filter = null)
    {
        return new Pages(
            function ($page) use ($filter) {
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

                    if (isset($filter->startDate)) {
                        $val = $filter->startDate->format('Y-m-d');
                        array_push($params, 'start_date=' . $val);
                    }

                    if (isset($filter->endDate)) {
                        $val = $filter->endDate->format('Y-m-d');
                        array_push($params, 'end_date=' . $val);
                    }
                }

                $q = join('&', $params);
                $result = $this->_get($this->_url('/batches?' . $q));
                return Deserialize::batchesPage($result);
            }
        );
    }

    /**
     * Fetches the tags associated with the given batch.
     *
     * @param string $batchId the batch identifier
     *
     * @return string[] a list of tags
     */
    public function fetchBatchTags(string $batchId)
    {
        $result = $this->_get($this->_url("/batches/$batchId/tags"));
        return Deserialize::tags($result);
    }

    public function fetchGroup(string $groupId)
    {
        $result = $this->_get($this->_url('/groups/' . $groupId));
        return Deserialize::groupResult($result);
    }

    public function fetchGroups(GroupFilter $filter = null)
    {
        return new Pages(
            function ($page) use ($filter) {
                $params = ["page=$page"];

                if (!is_null($filter)) {
                    if (isset($filter->pageSize)) {
                        array_push($params, 'page_size=' . $filter->pageSize);
                    }

                    if (isset($filter->tags)) {
                        $val = urlencode(join(',', $filter->tags));
                        array_push($params, 'tags=' . $val);
                    }
                }

                $q = join('&', $params);
                $result = $this->_get($this->_url('/groups?' . $q));
                return Deserialize::groupsPage($result);
            }
        );
    }

    /**
     * Fetches the tags associated with the given group.
     *
     * @param string $groupId the group identifier
     *
     * @return string[] a list of tags
     */
    public function fetchGroupTags(string $groupId)
    {
        $result = $this->_get($this->_url("/groups/$groupId/tags"));
        return Deserialize::tags($result);
    }

}

?>
