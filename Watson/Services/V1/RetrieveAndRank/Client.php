<?php

namespace Watson\Services\V1\RetrieveAndRank;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class Client extends GuzzleHttp\Client
{

    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

}