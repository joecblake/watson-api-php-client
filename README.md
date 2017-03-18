# Watson PHP Client
A simple PHP library for accessing IBM's Watson API services


## Basic Setup:

<pre>

$service = 'retrieve-and-rank';
$config = new \stdClass();
$config->username = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxx';
$config->password = 'xxxxxxxxxxxxxx';
$config->url = sprintf('https://gateway.watsonplatform.net/%s/api',$service);
$config->version = 'v1';

$transportConfig = ['auth' =>  [$config->username, $config->password]];

$client = new Watson\Client($service,$config,$transportConfig);

$clientInstance = $client->getClientInstance();
$clientInstance->setServiceUrl($config->url);
$clientInstance->setServiceVersion($config->version);

</pre>

### TODO

 -- Implement unit tests</br>
 -- Improve method returning and exception throwing</br>
 -- Get rid of Monolog dependency (error logging should not be handled by library)</br>