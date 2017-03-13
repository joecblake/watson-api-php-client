<?php

namespace Watson\Services\V1\RetrieveAndRank;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

use Monolog\Logger;
use Watson\Interfaces\ClientInterface;
use Watson\Helpers\ClientHelper as Helper;


class Client extends GuzzleHttp\Client implements ClientInterface
{

    protected $_serviceUsername;
    protected $_servicePassword;
    protected $_serviceUrl;
    protected $_serviceEndpoint;
    protected $_serviceVersion;

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @return mixed
     */
    public function getServiceUsername()
    {
        return $this->_serviceUsername;
    }

    /**
     * @param mixed $serviceUsername
     */
    public function setServiceUsername($serviceUsername)
    {
        $this->_serviceUsername = $serviceUsername;
    }

    /**
     * @return mixed
     */
    public function getServicePassword()
    {
        return $this->_servicePassword;
    }

    /**
     * @param mixed $servicePassword
     */
    public function setServicePassword($servicePassword)
    {
        $this->_servicePassword = $servicePassword;
    }

    /**
     * @return mixed
     */
    public function getServiceUrl()
    {
        return $this->_serviceUrl;
    }

    /**
     * @param mixed $serviceUrl
     */
    public function setServiceUrl($serviceUrl)
    {
        $this->_serviceUrl = $serviceUrl;
    }


    /**
     * @return mixed
     */
    public function getServiceVersion()
    {
        return $this->_serviceVersion;
    }

    /**
     * @param mixed $serviceVersion
     */
    public function setServiceVersion($serviceVersion)
    {
        $this->_serviceVersion = $serviceVersion;
    }


    public function listSolrCluster()
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->request('GET', $url, ['auth' => [$this->getServiceUsername(), $this->getServicePassword()]]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        }

    }

    public function deleteSolrCluster($clusterId)
    {

        $endpoint = 'solr_clusters/' . $clusterId;
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->request('DELETE', $url, ['auth' => [$this->getServiceUsername(), $this->getServicePassword()]]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        }

    }

    public function addSolrCluster($size, $name)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $body = array();
            $body['cluster_name'] = $name;
            $body['cluster_size'] = $size;

            $response = $this->request('POST', $url,
                [
                    'auth' => [$this->getServiceUsername(), $this->getServicePassword()],
                    'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                    'body' => json_encode($body)
                ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        }

    }


    public function uploadSolrConfig($clusterId, $configName, $filePath)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/config/%s', $url, $clusterId, $configName);

        try {

            $response = $this->request('POST', $urlWithParams,
                [
                    'auth' => [$this->getServiceUsername(), $this->getServicePassword()],
                    'headers' => ['content-type' => 'application/zip'],
                    'body' => file_get_contents($filePath)
                ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

                return false;

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL);

        }

    }

}