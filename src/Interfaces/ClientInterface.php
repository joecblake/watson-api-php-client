<?php

namespace Watson\Interfaces;

interface ClientInterface
{

    /**
     * @return mixed
     */
    public function getServiceUsername();

    /**
     * @param mixed $serviceUsername
     */
    public function setServiceUsername($serviceUsername);

    /**
     * @return mixed
     */
    public function getServicePassword();

    /**
     * @param mixed $servicePassword
     */
    public function setServicePassword($servicePassword);

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