<?php

namespace Watson\Interfaces;

interface ClientInterface
{

    /**
     * @return mixed
     */
    public function getServiceUrl();

    /**
     * @param mixed $serviceUrl
     */
    public function setServiceUrl($serviceUrl);

    /**
     * @return mixed
     */
    public function getServiceVersion();

    /**
     * @param mixed $serviceVersion
     */
    public function setServiceVersion($serviceVersion);


}