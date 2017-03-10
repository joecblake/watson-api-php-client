<?php

namespace Watson;

use Watson\Services\V1\RetrieveAndRank;

class Client
{

    public function __construct()
    {
        return new RetrieveAndRank\Client();
    }

}