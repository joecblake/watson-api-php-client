<?php

namespace Watson;

use Watson\Services\V1\RetrieveAndRank;

/**
 * Class Client
 * @package Watson
 * Just a facade for the service classes
 */
class Client
{

    private $_service;
    private $_config;
    private $_transportConfig;


    /**
     * Client constructor.
     * @param $service
     * @param \stdClass $config
     * @param array $transportConfig
     */
    public function __construct($service,\stdClass $config,array $transportConfig = [])
    {

        $this->_service = $service;
        $this->_config = $config;
        $this->_transportConfig = $transportConfig;

    }

    public function getClientInstance(){
        /**
         * @TODO: switch/case $service to match the requested service
         */

        $client = new RetrieveAndRank\Client($this->_transportConfig);
        $client->setServiceUsername($this->_config->username);
        $client->setServicePassword($this->_config->password);
        $client->setServiceUrl($this->_config->url);
        $client->setServiceVersion($this->_config->version);

        return $client;
    }

}