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


    /**
     * List all SOLR clusters
     * @return mixed
     */
    public function listSolrCluster()
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->get($url);

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

    /**
     * Delete a SOLR cluster
     * @param $clusterId
     * @return bool
     */
    public function deleteSolrCluster($clusterId)
    {

        $endpoint = 'solr_clusters/' . $clusterId;
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->delete($url);

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

    /**
     * Create a new SOLR cluster
     * @param $size
     * @param $name
     * @return mixed
     */
    public function addSolrCluster($size, $name)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $body = array();
            $body['cluster_name'] = $name;
            $body['cluster_size'] = $size;

            $response = $this->post($url, [
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


    /**
     * @param $clusterId
     * @return mixed
     */
    public function getSolrClusterInfo($clusterId){

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s', $url, $clusterId);

        try {

            $response = $this->get($urlWithParams);

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


    /**
     * Upload SOLR cluster's config
     * @param $clusterId
     * @param $configName
     * @param $filePath
     * @return bool
     */
    public function uploadSolrConfig($clusterId, $configName, $filePath)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/config/%s', $url, $clusterId, $configName);

        try {

            $response = $this->post($urlWithParams, [
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


    /**
     * Delete SOLR config
     * @param $clusterId
     * @param $configName
     * @return bool
     */
    public function deleteSolrConfig($clusterId, $configName)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/config/%s', $url, $clusterId, $configName);

        try {

            $response = $this->delete($urlWithParams);

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


    /**
     * List Al SOLR configs
     * @param $clusterId
     * @return mixed
     */
    public function listSolrConfigs($clusterId)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/config', $url, $clusterId);

        try {

            $response = $this->get($urlWithParams);

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

    /**
     * Create a new SOLR collection
     * @param $clusterId
     * @param $configName
     * @param $collectionName
     * @return mixed
     */
    public function addSolrCollection($clusterId,$configName,$collectionName)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/admin/collections', $url, $clusterId);

        try {

            $params = array();
            $params['action'] = 'CREATE';
            $params['name'] = $collectionName;
            $params['collection.configName'] = $configName;
            $params['wt'] = 'json';

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
                'form_params' => $params
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


    /**
     * Delte a SOLR collection
     * @param $clusterId
     * @param $collectionName
     * @return mixed
     */
    public function deleteSolrCollection($clusterId,$collectionName)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/admin/collections', $url, $clusterId);

        try {

            $params = array();
            $params['action'] = 'DELETE';
            $params['name'] = $collectionName;
            $params['wt'] = 'json';

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
                'form_params' => $params
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


    /**
     * @param $clusterId
     * @param $collectionName
     * @param array $data
     * @return mixed
     */
    public function indexDocuments($clusterId,$collectionName,array $data = ['source'=>'stream']){


        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/update', $url, $clusterId,$collectionName);

        echo $urlWithParams."\r\n";

        try {

            if($data['source'] == 'stream'){
                $body = file_get_contents($data['path']);
            }else{
                $body = json_encode($data['raw']);
            }

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                'body' => $body,
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                $responseData = json_decode($content, true);

                if($responseData['responseHeader']['status'] !== 0){

                    Helper::log('Index failed with status code ' . $responseData['responseHeader']['status'] . ' : ' . var_export($responseData,true), Logger::CRITICAL);

                    return false;

                }else{

                    return true;

                }

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


    /**
     * @param $clusterId
     * @param $collectionName
     * @param $question
     * @param array $fieldList
     * @return mixed
     */
    public function solrSearch($clusterId,$collectionName,$question,array $fieldList = []){

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/select', $url, $clusterId,$collectionName);

        try {

            $params = array();
            $params['q'] = $question;
            $params['name'] = $collectionName;
            $params['wt'] = 'json';
            $params['fl'] =  implode(',',$fieldList);

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
                'form_params' => $params,
                'debug' => 1,
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

}