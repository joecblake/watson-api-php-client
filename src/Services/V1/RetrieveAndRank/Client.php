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


final class Client extends GuzzleHttp\Client implements ClientInterface
{

    protected $_serviceUrl;
    protected $_serviceEndpoint;
    protected $_serviceVersion;

    const DEBUG = 0;
    const LOG_FILE = 'retrieve-and-rank';

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
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#create_solr_cluster
     * Create a new SOLR cluster
     * @param $size
     * @param $name
     * @return mixed
     */
    public function createSolrCluster($size, $name)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $body = array();
            $body['cluster_name'] = $name;
            $body['cluster_size'] = $size;

            $response = $this->post($url, [
                'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                'body' => json_encode($body),
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#list_solr_clusters
     * List all SOLR clusters
     * @return mixed
     */
    public function listSolrCluster()
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->get($url, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL, self::LOG_FILE);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#get_cluster_status
     * Get SOLR cluster information from a given cluster ID
     * @param $clusterId
     * @return mixed
     */
    public function getSolrClusterInfo($clusterId)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s', $url, $clusterId);

        try {

            $response = $this->get($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }


    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#delete_cluster
     * Delete a SOLR cluster
     * @param $clusterId
     * @return bool
     */
    public function deleteSolrCluster($clusterId)
    {

        $endpoint = 'solr_clusters/' . $clusterId;
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s', $url, $clusterId);

        try {

            $response = $this->delete($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#upload_config
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
                'body' => file_get_contents($filePath),
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

                return false;

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#list_configs
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

            $response = $this->get($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#method_list_configurations
     * @param $clusterId
     * @param $configName
     * @param $destinationFolder
     * @return mixed
     */
    public function downloadSolrConfig($clusterId, $configName, $destinationFolder)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/config/%s', $url, $clusterId, $configName);

        try {

            $response = $this->get($urlWithParams, [
                'sink' => $destinationFolder . DIRECTORY_SEPARATOR . $configName . '.zip',
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

                return false;

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

        return false;

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#delete_configuration
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

            $response = $this->delete($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#create_solr_collection
     * Create a new SOLR collection
     * @param $clusterId
     * @param $configName
     * @param $collectionName
     * @return mixed
     */
    public function createSolrCollection($clusterId, $configName, $collectionName)
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
                'form_params' => $params,
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#delete_solr_collection
     * Delete a SOLR collection
     * @param $clusterId
     * @param $collectionName
     * @return mixed
     */
    public function deleteSolrCollection($clusterId, $collectionName)
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
                'form_params' => $params,
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#index_doc
     * Update SOLR index with a given set of documents
     * @param $clusterId
     * @param $collectionName
     * @param array $data
     * @return mixed
     */
    public function indexDocuments($clusterId, $collectionName, array $data = ['source' => 'stream'])
    {


        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/update', $url, $clusterId, $collectionName);

        try {

            if ($data['source'] == 'stream') {
                $body = file_get_contents($data['path']);
            } else {
                $body = json_encode($data['raw']);
            }

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                'body' => $body,
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                $responseData = json_decode($content, true);

                if ($responseData['responseHeader']['status'] !== 0) {

                    Helper::log('Index failed with status code ' . $responseData['responseHeader']['status'] . ' : ' . var_export($responseData, true), Logger::CRITICAL);

                    return false;

                } else {

                    return true;

                }

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#query_standard
     * Performs a SOLR un-ranked search
     * @param $clusterId
     * @param $collectionName
     * @param $question
     * @param array $fieldList
     * @return mixed
     */
    public function solrSearch($clusterId, $collectionName, $question, array $fieldList = [])
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/select', $url, $clusterId, $collectionName);

        try {

            $params = array();
            $params['q'] = $question;
            $params['name'] = $collectionName;
            $params['wt'] = 'json';
            $params['fl'] = implode(',', $fieldList);

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
                'form_params' => $params,
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#create_ranker
     * Creates a ranker and installs the base "Ground Truth"
     * @param $rankerName
     * @param $groundTruthFilePath
     * @param $clusterId
     * @param $collectionName
     * @param null $trainingDataFilePath
     * @return mixed
     */
    public function createRanker($rankerName, $groundTruthFilePath, $clusterId, $collectionName, $trainingDataFilePath = null)
    {

        $endpoint = 'rankers';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $trainingData = $this->_generateTrainingData($groundTruthFilePath, $clusterId, $collectionName, $trainingDataFilePath);

        try {

            $response = $this->post($url, [
                'multipart' => [
                    [
                        'name' => 'training_data',
                        'contents' => $trainingData
                    ],
                    [
                        'name' => 'training_metadata',
                        'contents' => json_encode(['name' => $rankerName])
                    ]
                ],
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL, self::LOG_FILE);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }


    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#get_rankers
     * List Rankers
     * @return mixed
     */
    public function listRankers()
    {

        $endpoint = 'rankers';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);

        try {

            $response = $this->get($url, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#get_status
     * Get information of a specific ranker
     * @param $rankerId
     * @return mixed
     */
    public function getRankerInfo($rankerId)
    {

        $endpoint = 'rankers';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s', $url, $rankerId);

        try {

            $response = $this->get($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#delete_ranker
     * Deletes the ranker with the given ID
     * @param $rankerId
     * @return bool
     */
    public function deleteRanker($rankerId)
    {

        $endpoint = 'rankers';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s', $url, $rankerId);

        try {

            $response = $this->delete($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                return true;

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#rank
     * Rank / Re-Rank answers
     * @param $rankerId
     * @param $answerData
     * @return mixed
     */
    public function rank($rankerId, $answerData)
    {

        $endpoint = 'rankers';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/rank', $url, $rankerId);

        try {

            $response = $this->post($urlWithParams, [
                'multipart' => [
                    [
                        'name' => 'answer_data',
                        'contents' => $answerData
                    ]
                ],
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL, self::LOG_FILE);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#query_ranker
     * Search and Rank
     * @param $clusterId
     * @param $collectionName
     * @param $rankerId
     * @param $question
     * @param $fieldList
     * @return mixed
     */
    public function searchAndRank($clusterId, $collectionName, $rankerId, $question, $fieldList)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/fcselect', $url, $clusterId, $collectionName);

        try {

            $params = array();
            $params['q'] = $question;
            $params['ranker_id'] = $rankerId;
            $params['wt'] = 'json';
            $params['fl'] = implode(',', $fieldList);

            $response = $this->post($urlWithParams, [
                'headers' => ['content-type' => 'application/x-www-form-urlencoded', 'Accept' => 'application/json'],
                'form_params' => $params,
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }


    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#get_statistics
     * Get SOLR statistics
     * @param $clusterId
     * @return mixed
     */
    public function getSolrStats($clusterId)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/stats', $url, $clusterId);

        try {

            $response = $this->get($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#resize_cluster
     * Resize a SOLR cluster
     * @param $clusterId
     * @param $clusterSize (1-7)
     * @return mixed
     */
    public function resizeSolrCluster($clusterId, $clusterSize)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/cluster_size', $url, $clusterId);

        try {

            $body = json_encode(['cluster_size' => (int)$clusterSize]);

            if ($clusterSize > 0 && $clusterSize < 8) {

                $response = $this->put($urlWithParams, [
                    'headers' => ['content-type' => 'application/json', 'Accept' => 'application/json'],
                    'body' => $body,
                    'debug' => self::DEBUG
                ]);

                if ($response->getStatusCode() == 200) {

                    $stream = $response->getBody();
                    $content = $stream->getContents();

                    return json_decode($content, true);

                } else {

                    Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

                }

            } else {

                throw new \Exception('Cluster size needs to be between 1 and 7 units');

            }


        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * https://www.ibm.com/watson/developercloud/retrieve-and-rank/api/v1/?curl#get_resize_cluster
     * Get the status of a cluster resize operation
     * @param $clusterId
     * @return mixed
     */
    public function getSolrClusterResizeStatus($clusterId)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/cluster_size', $url, $clusterId);

        try {

            $response = $this->get($urlWithParams, [
                'debug' => self::DEBUG
            ]);

            if ($response->getStatusCode() == 200) {

                $stream = $response->getBody();
                $content = $stream->getContents();

                return json_decode($content, true);

            } else {

                Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL);

            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

    }

    /**
     * Generates training data from a given "Ground Truth" csv file
     * @param $groundTruthFilePath
     * @param $clusterId
     * @param $collectionName
     * @param null $trainingDataFilePath
     * @return array
     */
    private function _generateTrainingData($groundTruthFilePath, $clusterId, $collectionName, $trainingDataFilePath = null)
    {

        $endpoint = 'solr_clusters';
        $url = Helper::buildRequestUrl($this->getServiceUrl(), $this->getServiceVersion(), $endpoint);
        $urlWithParams = sprintf('%s/%s/solr/%s/fcselect', $url, $clusterId, $collectionName);

        $groundTruthRows = Helper::readCsv($groundTruthFilePath);
        $trainingDataArray = array();
        $trainingData = '';

        try {

            $groundTruthRowCount = 0;

            foreach ($groundTruthRows as $groundTruthRow) {

                $question = array_shift($groundTruthRow);
                $relevance = implode(',', $groundTruthRow);

                $params = array();
                $params['q'] = $question;
                $params['gt'] = $relevance;
                $params['generateHeader'] = ($groundTruthRowCount === 0) ? 'true' : 'false';
                $params['rows'] = 10; // Rows per query. 10 is fine?
                $params['returnRSInput'] = 'true';
                $params['wt'] = 'json';

                $response = $this->get($urlWithParams, [
                    'query' => $params,
                    'debug' => self::DEBUG
                ]);

                if ($response->getStatusCode() == 200) {

                    $stream = $response->getBody();
                    $content = $stream->getContents();

                    $responseObj = json_decode($content);

                    if (isset($responseObj->RSInput)) {

                        if (!is_null($trainingDataFilePath)) {
                            file_put_contents($trainingDataFilePath, $responseObj->RSInput, FILE_APPEND);
                        }

                        array_push($trainingDataArray, $responseObj->RSInput);

                    } else {

                        throw new \Exception('RSInput is not present.');

                    }

                } else {

                    Helper::log('Unexpected status code ' . $response->getStatusCode() . ': ' . $response->getBody()->getContents(), Logger::CRITICAL, self::LOG_FILE);

                }

                $groundTruthRowCount++;

            }

            if (!empty($trainingDataArray)) {
                $trainingData = implode('', $trainingDataArray);
            }

        } catch (ClientException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (BadResponseException $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        } catch (\Exception $e) {

            Helper::log($e->getMessage(), Logger::CRITICAL, self::LOG_FILE);

        }

        return $trainingData;

    }

}